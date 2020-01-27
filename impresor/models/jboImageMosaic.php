<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jboImageMosaic
 *
 * @author Jose
 */
class jboImageMosaic {
        public $code;
        public $name;
        public $w;
        public $h;
        public $delta;
              
    public function __construct($code="",$name="",$w=0.0,$h=0.0,$delta=0.0) {
        $this->code = $code;
        $this->name  = $name;
        $this->w = $w;
        $this->h = $h;
        $this->delta = $delta;
    }
}



