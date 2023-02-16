<?php
    namespace MF\Controller;

    abstract class Action{

        protected $view;

        public function __construct(){
            $this->view = new \stdClass();
        }

        protected function render($view, $layout = 'Layout'){

            $this->view->page = $view;

            if(file_exists("../App/Views/".$layout.".phtml")){
                require_once "../App/Views/".$layout.".phtml";
            }else{
                $this->content();
            } 
        }

        protected function content(){

            $classAtual = get_class($this); //App\Controllers\IndexController

            $classAtual = str_replace('App\\Controllers\\', '', $classAtual); //IndexController

            $classAtual = strtolower(str_replace('Controller', '', $classAtual));    //Index

            require_once "../App/Views/".$classAtual."/".$this->view->page.".phtml";
            // ASSIM É POSSIVEL TER VARIOS DIRETORIOS E COM OUTRAS VIEWS
        }
    }
?>