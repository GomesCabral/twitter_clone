<?php
    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Model\Container;

    class IndexController extends Action{

        public function index(){

            // TRATAR DO ERRO NA VIEW INDEX
            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

            $this->render('index');
        }

        public function inscreverse(){

            $this->view->usuario = [
                'nome' => '',
                'email' => '',
                'password' => '',
            ];

            $this->view->erroCadastro = false;

            $this->render('inscreverse');
        }

        public function registar(){

            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';

            //RECEBER OS DADOS
            // $usuario = new Usuario();
            $usuario = Container::getModel('Usuario'); 

            $usuario->__set('nome', $_POST['nome']);
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('password', md5($_POST['password'])); 

            // echo '<pre>';
            // print_r($usuario);
            // echo '</pre>';

             //SUCESSO
            if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0){

                // echo '<pre>';
                // print_r($usuario->getUsuarioPorEmail());
                // echo '</pre>';
                    $usuario->salvar();

                    $this->render('cadastro');
             

            }else{

                $this->view->usuario = [
                    'nome' => $_POST['nome'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                ];

                $this->view->erroCadastro = true;

                $this->render('inscreverse'); 
            }
           
            
            //ERRO
        }
    }
?>