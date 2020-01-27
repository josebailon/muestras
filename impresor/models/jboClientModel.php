<?php

defined("NODIRECT") or die;

class jboClientModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

    public function getAll() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'clients WHERE client=1');
            $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_ASSOC);
            
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
        }
    }

    public function getById($id) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'clients WHERE id = :id AND client=1');
            $gsent->bindValue(':id', $id, PDO::PARAM_INT);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_ASSOC);
            $this->autoDisconect();

            return $result;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
        }
    }

 
    
    public function getByHWId($hardwareId) {

        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'clients WHERE hardwareId = :hardwareId AND client=1');
            $gsent->bindValue(':hardwareId', $hardwareId, PDO::PARAM_STR);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_ASSOC);
            $this->autoDisconect();
            
            if (!$result){
                echo "ID: ".$hardwareId."<BR>";
            }
            return $result;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
        }
    }

    public function autoIdentifyClient() {
        $currentip = $_SERVER['REMOTE_ADDR'];
        $this->autoConect();
        
        if ($currentip != "127.0.0.1" && $currentip != "::1") {
            return $this->identifyByHWId();
         } else {
            $out = $this->getById(1);
            $this->autoDisconect();
            return $out;
        }
        
    }


    private function identifyByHWId(){
            $hardwareId = jboTools::getGetVar("i");
            $out = $this->getByHWId($hardwareId);
            $this->autoDisconect();
            
            return $out;
    }
    
    }
    
 

 

//fin de clase