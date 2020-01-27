<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jboPrinter
 *
 * @author Jose
 */
class jboPrinter {

    public $name;
    public $code;
    public $comcopies;
    public $combn;
    public $comcolor;
    public $com2sidesbindingnormal;
    public $com2sidesbindingsmall;
    public $pslevel;
    public $printmode;
    public $fittopage;
    public $comsummaryjobprint;
    public function __construct() {
        $this->name="";
        $this->code="";
        $this->comcopies="-n %ncopies%";
        $this->combn="";
        $this->comcolor="";
        $this->com2sidesbindingnormal="";
        $this->com2sidesbindingsmall="";
        $this->pslevel=3;
        $this->printmode=0;
        $this->fittopage=true;
        $this->comsummaryjobprint="";
    }

}
