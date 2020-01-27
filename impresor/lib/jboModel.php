<?php
defined("NODIRECT") or die;

class jboModel {
    
        protected $con;
        protected $isConected;
        protected $allwaysConected;
	function __construct($allwaysConected=true) {
		$this->con = new jboConection ();
                $this->allwaysConected=$allwaysConected;
                if ($allwaysConected){
                    $this->conect();
                }
	}
        public function conect(){
            $this->con->conect();
            $this->isConected=true;
        }
        public function disconect(){
            $this->con->disconect();
            $this->isConected=false;
        }
        
        public function autoConect(){
            if (!$this->isConected){
                $this->conect();
            }
        }
        public function autoDisconect(){
            if (!$this->allwaysConected){
                $this->disconect();
            }
        }
}