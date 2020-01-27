<?php

/**
 * Description of jboClientBlocker
 *
 * @author Jose
 */
class jboClientBlocker {
    //ordena el bloqueo de los clientes
    public function blockClients(){
        $f=fopen("clientsoff.lock", "w") or die("Unable to open file!");
        fclose($f); 
                    
    }
    //ordena el desbloqueo de los clientes
    public function unblockClients(){
        if ($this->clientsBlocked()){
            unlink ('clientsoff.lock');
        }
    }
    
    public function clientsBlocked(){
        return file_exists('clientsoff.lock');
    }
    //devuelve si los clientes están ocupados
    public function clientsIddle(){
        $cm=new jboClientModel();
        $clients=$cm->getAll();
     
        foreach($clients as $c){
            if($this->clientWorking($c['id'])){
                return false;
            }
        }
        return true;
    }
    
    public function listClientsWorking(){
        $out=[];
        $cm=new jboClientModel();
        $clients=$cm->getAll();
     
        foreach($clients as $c){
            if($this->clientWorking($c['id'])){
                $out[]=$c;
            }
        }
        return $out;
    }
    
    //mira si un cliente esta trabajando
    private function clientWorking($id){
       return file_exists('clientworking'.$id.'.lock');
   
    }
    public function markClientAsWorking($id){
         $f=fopen('clientworking'.$id.'.lock', "w") or die("Unable to open file!");
        fclose($f); 
    }
    public function unmarkClientAsWorking($id){
        if ($this->clientWorking($id)){
            unlink('clientworking'.$id.'.lock');
        }
    }
    
    public function unmarkAllClientsAsWorking(){
        $cm=new jboClientModel();
        $clients=$cm->getAll();
     
        foreach($clients as $c){
            
        }
        
    }
}
