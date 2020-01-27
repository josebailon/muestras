<?php

defined("NODIRECT") or die;
/*
  STATUS:
 * 0:en cliente
 * 1:Aceptado por el cliente
 * 2:preparado
 * 3:imprimiendo
 * 4:imprimido
 * 
 */

class jboJobModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

    public function initJob($idclient) {

        $this->cleanJobsWithStatus0($idclient);
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'jobs (idClient, status, totalPrice, canceled, name, totalPages) VALUES (:idclient,0,0,0,"","")');
            $gsent->bindValue(':idclient', $idclient, PDO::PARAM_INT);
            $gsent->execute();
            $idJob = $this->con->db->lastInsertId();
            if (!$this->createJobFolder($idJob)) {
                echo "Error creando carpeta de trabajo";
                $this->autoDisconect();
                die;
            }
            $this->autoDisconect();
            return $idJob;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
        }
    }

//fin de initjob

    public function cleanJobsWithStatus0($idclient) {
        $this->autoConect();
        $jobsToDelete = $this->getAllJobsWithStatus0($idclient);
        foreach ($jobsToDelete as $j) {
            $this->deleteJob($j->id);
        }
        $this->autoDisconect();
    }

    public function getById($id) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM  ' . jboConf::$tablePrefix . 'jobs WHERE id = :id');
            $gsent->bindValue(':id', $id, PDO::PARAM_INT);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
            return false;
        }
    }

    public function updateJob($job, $updateDate = false) {
        $datestring = "";
        if ($updateDate) {
            $datestring = ", date=:date";
        }
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'jobs SET idClient=:idClient, status=:status, totalPrice=:totalPrice' . $datestring . ', canceled=:canceled, name=:name, totalPages=:totalPages WHERE id=:id');
            $gsent->bindValue(':idClient', $job->idClient, PDO::PARAM_INT);
            $gsent->bindValue(':status', $job->status, PDO::PARAM_INT);
            $gsent->bindValue(':totalPrice', $job->totalPrice, PDO::PARAM_STR);
            $gsent->bindValue(':id', $job->id, PDO::PARAM_INT);
            $gsent->bindValue(':canceled', $job->canceled, PDO::PARAM_INT);
            $gsent->bindValue(':name', $job->name, PDO::PARAM_STR);
            $gsent->bindValue(':totalPages', $job->totalPages, PDO::PARAM_STR);
            if ($updateDate) {
                $date = date('Y-m-d H:i:s');
                $gsent->bindParam(':date', $date, PDO::PARAM_STR);
            }
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return false;
        }
    }

     //getall
    public function getAll() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'jobs');
            $gsent->execute();
            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
        }
    }
    
    //trabajos en preparacion en el cliente
    public function getAllJobsWithStatus0($idclient) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'jobs WHERE status=0 AND idClient=:idClient');
            $gsent->bindValue(':idClient', $idclient, PDO::PARAM_INT);
            $gsent->execute();
            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
        }
    }
    //trabajos no finalizados
    public function getAcceptedJobsWithUnfinishedStatus() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'jobs WHERE status>0 AND status<100 ORDER BY date');
             $gsent->execute();
            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
        }
    }


    //todos los trabajos aceptados por los clientes
    public function getAllJobsAccepted($showcanceled = true) {
        try {
            $this->autoConect();
            $canceledSqlString = "";
            if (!$showcanceled) {
                $canceledSqlString = " AND canceled<3";
            }
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'jobs WHERE status>0'.$canceledSqlString);
            $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
            return false;
        }
    }

    //todos los trabajos aceptados por los clientes
    public function getAllJobsAcceptedAndNotPrepared() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'jobs WHERE status>0 AND status<3');
            $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
            return false;
        }
    }

    public function deleteJob($idJob) {

        if ($idJob == null || $idJob == "") {
            return;
        }
        $docM = new jboDocumentModel();
        $docM->deleteDocumentsByJob($idJob);
        //delete job files
        $pathToDelete = jboConf::$jobsPath . $idJob;
        if ($pathToDelete!=jboConf::$jobsPath ){
            jboTools::emptyDir(jboConf::$jobsPath . $idJob, true);
        }
        jboTools::deleteIconsByJob($idJob);
        $this->autoConect();

        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'jobs WHERE id = :id');
        $gsent->bindValue(':id', $idJob, PDO::PARAM_INT);
        $gsent->execute();
        $this->autoDisconect();
    }

    private function createJobFolder($idJob) {
        if (!mkdir(jboConf::$jobsPath . $idJob)) {
            return false;
        }
        chmod(jboConf::$jobsPath . $idJob, 0777);
        return true;
    }

    public function setStatus($id, $status) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'jobs SET status=:status WHERE id=:id');
            $gsent->bindValue(':id', $id, PDO::PARAM_INT);
            $gsent->bindValue(':status', $status, PDO::PARAM_INT);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }

    public function setCanceledLevel($id, $level) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'jobs SET canceled=:canceled WHERE id=:id');
            $gsent->bindValue(':id', $id, PDO::PARAM_INT);
            $gsent->bindValue(':canceled', $level, PDO::PARAM_INT);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            echo PDOException;
            $this->autoDisconect();
            return false;
        }
    }

    /* COLA DE IMPRESION */

    public function emptyPrintQueue(){
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'printqueue');
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
        
    }
    public function addToPrintQueue($idJob) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'printqueue (id,pos) VALUES (:id,(SELECT IFNULL(MAX(pos), 0) + 1 FROM ' . jboConf::$tablePrefix . 'printqueue ))');
            $gsent->bindValue(':id', $idJob, PDO::PARAM_INT);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

    public function removeFromPrintQueue($id) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'printqueue WHERE id = :id');
            $gsent->bindValue(':id', $id, PDO::PARAM_INT);
            $gsent->execute();
            $this->autoDisconect();
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

    public function getFirstNotPreparedInPrintQueue() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT pq.id FROM  ' . jboConf::$tablePrefix . 'printqueue pq LEFT JOIN ' . jboConf::$tablePrefix . 'jobs ij where pq.id = ij.id  AND ij.status=1 order by pq.pos');
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
        }
    }

    public function getFirstNotPrepared() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'jobs WHERE status=1 ORDER BY date');
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
        }
    }

    public function getPrintQueue() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM  ' . jboConf::$tablePrefix . 'printqueue order by pos');
            $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
        }
    }
    //mira si una id está en la print queue
    public function isInPrintQueue($id){
        $pq = $this->getPrintQueue();
        $found=false;
        foreach($pq as $job){
            if ($job->id==$id){
                $found=true;
            }
        }
        return $found;
    }

}

//fin de clase