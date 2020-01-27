<?php

defined("NODIRECT") or die;

class jboTemplate {

    protected $view = "default";
    protected $viewPath = "";
    protected $templateName = "";
    protected $templatePath = ""; //ruta relativa a la raiz web en la que se encuentra el template
    public $headerHtml = "";
    public $footerHtml = "";
    public $data = array();
    public $pageTitle = "";
    public $backUrl = "";

    public function __construct() {
        
    }

    public function setView($name) {

        $this->view = $name;
    }

    public function render() {

        if (is_array($this->data) && !empty($this->data)) {
            extract($this->data);
        }
        ob_start();
        include $this->templatePath . "/head.php";
        include $this->templatePath . "/views/" . $this->view . ".php";
        include $this->templatePath . "/footer.php";
        echo ob_get_clean();
    }

    protected function setTemplateName($name) {
        $this->templateName = $name;
        $this->templatePath = "templates/" . $this->templateName;
        $this->viewPath = $this->templatePath . "/views";
    }
    
    protected function getViewHtml($viewname,$data=true){//con true cogera la del template, si es array usa el cogido
        if ($data===true){
            $data = $this->data;
        }
        if (is_array($data) && !empty($data)) {
            extract($data);
        }
        ob_start();
        include $this->templatePath . "/views/" . $viewname . ".php";
        return ob_get_clean();
    }

}
