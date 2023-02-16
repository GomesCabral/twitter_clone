<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

    public function timeline()
    {

        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            // RECUPERAR OS TWEETS
            $tweet = Container::getModel('Tweet');

            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweets = $tweet->getAll();

            // echo '<pre>';
            // print_r($tweets);
            // echo '</pre>';

            $this->view->tweets = $tweets;

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets = $usuario->getTotalTweets();
            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            $this->view->total_seguidores = $usuario->totalSeguidores();

            $this->render('timeline');
        } else {

            header('Location: /?erro=login');
        }
    }

    public function tweet()
    {

        session_start();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            $tweet = Container::getModel('Tweet');
            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweet->gravar();

            header('Location: /timeline');
        } else {

            header('Location: /?erro=login');
        }
    }

    // POSSO CRIAR O METODO validaAutenticacao() E APAGAR O IF DO TIMELINE E DO TWEET  if(!empty($_SESSION['id']) && !empty($_SESSION['nome'])) E CHAMAR O this->validaAutenticacao(); ASSIM O CODIGO FICA MELHOR
    public function validaAutenticacao()
    {

        session_start();

        if (!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome'])) {
            header('Location: /?erro=login');
        }
    }

    public function quemSeguir()
    {

        $this->validaAutenticacao();

        // echo '<pre>';
        // print_r($_GET);
        // echo '</pre>';

        $pesquisarPor = isset($_GET["pesquisarPor"]) ? $_GET["pesquisarPor"] : '';

        echo 'A pesquisar por: ' . $pesquisarPor;

        $usuarios = [];

        if (!empty($pesquisarPor)) {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();

            // echo '<pre>';
            // print_r($usuarios);
            // echo '</pre>';
        }

        $this->view->usuarios = $usuarios;

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->totalSeguidores();

        $this->render('quemSeguir');
    }

    public function acao()
    {

        $this->validaAutenticacao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';


        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        if ($acao == 'seguir') {

            $usuario->seguirUsuario($id_usuario_seguindo);
            header('Location: /quem_seguir');
        } else if ($acao == 'deixar_de_seguir') {

            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
            header('Location: /quem_seguir');
        }
    }
}
