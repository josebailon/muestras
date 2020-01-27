<?php

defined("NODIRECT") or die;

class jboBindingModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

    //add un encuadernado
    //$binding: valores del encuadernado
    //$initprices: determina si hay que iniciar los precios de este encuadernado
    public function addBinding($binding,$initprices=true){
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'bindings (name) VALUES (:name)');
            $gsent->bindValue(':name', $binding->name, PDO::PARAM_STR);
            if($gsent->execute()){
                $idBinding = $this->con->db->lastInsertId();
                if ($initprices){
                    if ($this->initPrices($idBinding)){
                        $this->autoDisconect();
                        return $idBinding;
                    }return false;
                }
                else{
                    $this->autoDisconect();
                    return $idBinding;
                }
            }
        } catch (PDOException $e) {
            //print $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }
    
    //inicia los precios para un binding
    public function initPrices($idBinding){
        if ($this->cleanPricesForBinding($idBinding)){
            try {
                $this->autoConect();
                $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'binding_price (idBinding,min,max,price) VALUES (:idBinding,1,1410065407,0)');
                $gsent->bindValue(':idBinding', $binding->name, PDO::PARAM_STR);
                $gsent->execute();
                return true;
            } catch (PDOException $e) {
                //print $e->getMessage();
                $this->autoDisconect();
                return false;
            }
        }
        else{
            return false;
        }
    }

    //borra todos los bindings y sus precios
    public function cleanBindings(){
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'binding_price');

            //si hemos limpiado bien los precios de bindings borramos los bindigs
            if ($gsent->execute()){
                $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'bindings');
                if (!$gsent->execute()){
                    return false;
                }
            }else{
                return false;
            }
            $this->autoDisconect();
            return true;
        } catch (PDOException $e) {
            //$e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }
    
    
    //borra los precios para un binding
    public function cleanPricesForBinding($idBinding){
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'binding_price WHERE idBinding=:idBinding');
            $gsent->bindValue(':idBinding', $idBinding, PDO::PARAM_INT);
            $out = $gsent->execute();
            $this->autoDisconect();
            return true;
        } catch (PDOException $e) {
            //$e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }
    
    //get todos los bindings y sus precios
    public function getAll() {
        $this->autoConect();
        $out = array();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'bindings');
            $gsent->execute();
            while ($binding = $gsent->fetch(PDO::FETCH_OBJ, 0)) {
                $out[$binding->id]= $binding;
            }
            foreach ($out as $i=>$currentbinding){
                $out[$i]->prices=$this->getPricesForBinding($currentbinding->id);
            }
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            //$e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }
    
    //devuelve un array de precios del binding pasando la id
    public function getPricesForBinding($idBinding){
        $this->autoConect();
        $out = array();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'binding_price WHERE idBinding=:idBinding');
            $gsent->bindValue(':idBinding', $idBinding, PDO::PARAM_INT);
            $gsent->execute();
            while ($price = $gsent->fetch(PDO::FETCH_OBJ, 0)) {
                $out[]= $price;
            }
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            //$e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }
    
        public function saveBindings($bindings) {
            //borramos los bindings y sus precios
           $this->cleanBindings();

            $success = true;
            foreach ($bindings as $b) {
                if (!is_null($b)){
                    if (!$this->saveBinding($b)) {
                        $success = false;
                    }
                }
            }
        return $success;
        }

        public function saveBinding($binding) {
            try {
                $this->autoConect();
                $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'bindings (id,name) VALUES (:id,:name)');
                $gsent->bindValue(':id', $binding->id, PDO::PARAM_INT);
                $gsent->bindValue(':name', $binding->name, PDO::PARAM_STR);
                $this->autoDisconect();
                if( $gsent->execute()){
                    if ($this->saveBindingPrices($binding)){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
                
            } catch (PDOException $e) {
                //print $e->getMessage();
                $this->autoDisconect();
                return false;
            }
        }
        
        public function saveBindingPrices($binding) {
            $success =true;
            foreach ($binding->prices as $p) {
                if (!$this->saveBindingPrice($p,$binding->id)) {
                    $success = false;
                }
            }
            return $success;
            
        }
        
        public function saveBindingPrice($price,$idBinding){
             try {
                $this->autoConect();
                $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'binding_price (idBinding,min,max,price) VALUES (:idBinding,:min,:max,:price)');

                $gsent->bindValue(':idBinding', $idBinding, PDO::PARAM_INT);
                $gsent->bindValue(':min', $price->min, PDO::PARAM_INT);
                $gsent->bindValue(':max', $price->max, PDO::PARAM_INT);
                $gsent->bindValue(':price', strval($price->price), PDO::PARAM_STR);
                $this->autoDisconect();
                return $gsent->execute();
            } catch (PDOException $e) {
                //print $e->getMessage();
                $this->autoDisconect();
                return false;
            }
        }
        
        
        
    //--------------------------------------------------------------------------
    public function getAll_old() {
        $this->autoConect();
        $out = array();
        $out['A4'] = Array();
        $out['A4']['color'] = Array();
        $out['A4']['bn'] = Array();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'prices WHERE format="A4" AND color="0" ORDER BY min');
            $gsent->execute();
            while ($priceRule = $gsent->fetch(PDO::FETCH_OBJ, 0)) {
                $out['A4']['bn'][] = $priceRule;
            }
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'prices WHERE format="A4" AND color="1" ORDER BY min');
            $gsent->execute();
            while ($priceRule = $gsent->fetch(PDO::FETCH_OBJ, 0)) {
                $out['A4']['color'][] = $priceRule;
            }
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            //$e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }

    public function savePrices($prices) {
        $this->cleanPrices();

        $pricesA4Color = $prices->A4->color;
        $pricesA4Bn = $prices->A4->bn;
        $success = true;
        foreach ($pricesA4Color as $p) {
            if (!$this->savePrice($p)) {
                $success = false;
            }
        }
        foreach ($pricesA4Bn as $p) {
            if (!$this->savePrice($p)) {
                $success = false;
            }
        }
        return $success;
    }

    public function savePrice_old($price) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'prices (color,format,min,max,price) VALUES (:color,:format,:min,:max,:price)');

            $gsent->bindValue(':color', $price->color, PDO::PARAM_STR);
            $gsent->bindValue(':format', $price->format, PDO::PARAM_STR);
            $gsent->bindValue(':min', $price->min, PDO::PARAM_INT);
            $gsent->bindValue(':max', $price->max, PDO::PARAM_INT);
            $gsent->bindValue(':price', strval($price->price), PDO::PARAM_STR);
            $this->autoDisconect();
            return $gsent->execute();
        } catch (PDOException $e) {
            //print $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }

    private function cleanPrices() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'prices');
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            //$e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }

}

//fin de clase