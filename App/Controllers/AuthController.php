<?php
    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action{

        public function autenticar(){

            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';

            $usuario = Container::getModel('Usuario');

            $usuario->__set('email', $_POST['email']);
            $usuario->__set('password', md5($_POST['password']));

            // echo '<pre>';
            // print_r($usuario);
            // echo '</pre>';

            $usuario->autenticar();

            // echo '<pre>';
            // print_r($usuario);
            // echo '</pre>';
            if(!empty($usuario->__get('id')) && !empty($usuario->__get('nome'))){
                
                session_start();
                $_SESSION['id'] = $usuario->__get('id');
                $_SESSION['nome'] = $usuario->__get('nome');

                // CRIAR A ROTA TIMELINE E TRABALHAR NO NOVO CONTROLLER
                header('Location: /timeline');

            }else{

                // TRATAR NA INDEXCONTROLLER->METODO INDEX
                header('Location: /?login=erro');
            }
        }

        public function sair(){

            session_start();
            session_destroy();
            header('Location: /');
        }
    }