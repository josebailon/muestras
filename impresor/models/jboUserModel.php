<?php

defined("NODIRECT") or die;

class jboUserModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

    public function deleteUser($id){
        if ($id == null || $id == "") {
            return;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'clients WHERE id = :id');
        $gsent->bindValue(':id', $id, PDO::PARAM_INT);
        $out = $gsent->execute();
        $this->autoDisconect();
        return $out;
    }

    public function getAll() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'clients ORDER BY name COLLATE NOCASE');
            $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            
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
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'clients WHERE id = :id');
            $gsent->bindValue(':id', $id, PDO::PARAM_INT);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
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
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'clients WHERE hardwareId = :hardwareId');
            $gsent->bindValue(':hardwareId', $hardwareId, PDO::PARAM_STR);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
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

    public function saveUser($client){
        $update = false;    
        if (isset($client->id)){
            if ($client->id>-1){$update=true;}
        }
         $this->autoConect();
        
         try {
            if ($update){
                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'clients SET name=:name, selectPrinter=:selectPrinter, notes=:notes, localPath=:localPath, id=:id, client=:client, manager=:manager, hardwareId=:hardwareId, transformTimeOutPerFile=:transformTimeOutPerFile, autoAcceptPrintConditions=:autoAcceptPrintConditions, whatsapp=:whatsapp, telegram=:telegram, addFileDefaultPath=:addFileDefaultPath WHERE id=:id  ');
            }else{
                $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'clients (name,  selectPrinter, notes, localPath, id, client, manager, hardwareId, transformTimeOutPerFile, autoAcceptPrintConditions, whatsapp, telegram, addFileDefaultPath) VALUES (:name, :selectPrinter, :notes, :localPath, :id, :client, :manager, :hardwareId, :transformTimeOutPerFile, :autoAcceptPrintConditions, :whatsapp, :telegram, :addFileDefaultPath)');
            }
            
            
            $gsent->bindValue(':name', $client->name, PDO::PARAM_STR);
             $gsent->bindValue(':selectPrinter', $client->selectPrinter, PDO::PARAM_BOOL);
            $gsent->bindValue(':notes', $client->notes, PDO::PARAM_STR);
            $gsent->bindValue(':localPath', $client->localPath, PDO::PARAM_STR);
            $gsent->bindValue(':client', $client->client, PDO::PARAM_BOOL);
            $gsent->bindValue(':manager', $client->manager, PDO::PARAM_BOOL);
            $gsent->bindValue(':hardwareId', $client->hardwareId, PDO::PARAM_STR);
            $gsent->bindValue(':transformTimeOutPerFile', $client->transformTimeOutPerFile, PDO::PARAM_INT);
            $gsent->bindValue(':autoAcceptPrintConditions', $client->autoAcceptPrintConditions, PDO::PARAM_BOOL);
            $gsent->bindValue(':whatsapp', $client->whatsapp, PDO::PARAM_BOOL);
            $gsent->bindValue(':telegram', $client->telegram, PDO::PARAM_BOOL);
            $gsent->bindValue(':addFileDefaultPath', $client->addFileDefaultPath, PDO::PARAM_STR);
            
            if ($update){
               $gsent->bindValue(':id', $client->id, PDO::PARAM_INT);
            }
            
            $out = $gsent->execute();
            if ($update){
                $id = $client->id;
            }else{
                $id= $this->con->db->lastInsertId();
            }
             $this->autoDisconect();
            if($out){
                return $id;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
    }
    
 

 

//fin de clase