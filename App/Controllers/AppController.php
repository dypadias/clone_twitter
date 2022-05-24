<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
    public function timeline()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            //recuperar tweets
            $tweet = Container::getModel('Tweet');
            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweets = $tweet->getAll();

            $this->view->tweets = $tweets;

            $this->render('timeline');
        } else {
            header("Location: /?login=erro");
        }
    }

    public function tweet()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $tweet = Container::getModel('Tweet');
            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweet->salvar();
            header("Location: /timeline");
        } else {
            header("Location: /?login=erro");
        }
    }

    public function quemSeguir()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';



            $usuarios = array();

            if ($pesquisarPor != '') {
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarPor);
                $usuario->__set('id', $_SESSION['id']);
                $usuarios = $usuario->getAll();
            }

            $this->view->usuarios = $usuarios;

            $this->render('quemSeguir');
        }
    }

    public function acao()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
            $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            if ($acao == 'seguir') {

                $usuario->seguirUsuario($id_usuario_seguindo);
            } else if ($acao == 'deixar_de_seguir') {
                $usuario->deixarSeguirUsuario($id_usuario_seguindo);
            }
        }
        header('Location: /quem_seguir');
    }
}
