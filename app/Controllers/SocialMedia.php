<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SocialMediaDB;

class SocialMedia extends BaseController
{
    private $social_media_DB;

    public function __construct()
    {
        $this->social_media_DB = new SocialMediaDB();
    }

    public function index(){
        $dados = $this->social_media_DB->readAllMessages();
        return view('home',compact('dados'));
    }

    public function login()
    {
        return view('login');
    }
    public function cadastro()
    {
        return view('cadastro');
    }

    public function comentarios($params)
    {
        $dados = [
            'id_post' => $params,
            'post' => $this->social_media_DB->comentarios($params),
            'respostas' => $this->social_media_DB->get_ComentariosPost($params)
        ];
        return view("comentarios", compact('dados'));
    }

    public function insert_comentarios_do_post()
    {
        $dados = [
            'post_id' => $_POST['id_post'],
            'id_autor' => $_POST['id_user'],
            'mensagem' => $_POST['mensagem']
        ];
        
        $resultado = $this->social_media_DB->insert_comentarios_post($dados);

        if ($resultado > 0) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir os dados.';
        }
    }

    public function post()
    {
        $dados = [
            'id_autor' => $_POST['id_user'],
            'mensagem' => $_POST['mensagem'],
            'data_criacao' => date('Y-m-d H:i:s')
        ];

        $resultado = $this->social_media_DB->insert_data($dados);

        if ($resultado > 0) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir os dados.';
        }
    }

    public function excluiPost(){
        $post_id = $_POST['post_id'];

        $resultado = $this->social_media_DB->excluiPost($post_id);

        if ($resultado > 0) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir os dados.';
        }
    }

    public function editarPost(){
        $dados = [
            'id' => $_POST['post_id'],
            'mensagem' => $_POST['mensagem'],
            'data_alteracao' => date('Y-m-d H:i:s')
        ];

        $resultado = $this->social_media_DB->insert_data($dados);

        if ($resultado > 0) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir os dados.';
        }
    }

    public function getPostIdByAuthorId(){
        $dados = $_POST['id_autor'];

        $posts = $this->social_media_DB->getPostIdByAuthorId($dados);

        if ($posts) {
            $response = [
                'success' => true,
                'posts' => $posts
            ];

            return $this->response->setJSON($response);
        } else {
            $response = [
                'success' => false,
                'message' => 'Nenhum like encontrado.'
            ];

            return $this->response->setJSON($response)->setStatusCode(404);
        }
    }

    public function like()
    {
        $dados = [
            'id_autor' => $_POST['id_user'],
            'post_id' => $_POST['post_id'],
        ];

        $resultado = $this->social_media_DB->insert_like($dados);

        if ($resultado > 0) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir os dados.';
        }
    }

    public function deslike()
    {
        $dados = [
            'like_id' => $_POST['like_id'],
        ];

        $resultado = $this->social_media_DB->deslike($dados);

        if ($resultado > 0) {
            echo 'Dados inseridos com sucesso!';
        } else {
            echo 'Erro ao inserir os dados.';
        }
    }

    public function get_likes()
    {
        $dados = [
            'id_autor' => $_POST['id_user']
        ];
        $likes = $this->social_media_DB->get_likes($dados);

        if ($likes) {
            $response = [
                'success' => true,
                'likes' => $likes
            ];

            return $this->response->setJSON($response);
        } else {
            $response = [
                'success' => false,
                'message' => 'Nenhum like encontrado.'
            ];

            return $this->response->setJSON($response)->setStatusCode(404);
        }
    }

    public function cadastraUsuario()
    {
        $dados = [
            'login' => $_POST['login'],
            'senha' => $_POST['senha'],
            'email' => $_POST['email'],
            'imgPath' => null
        ];

        $resultado = $this->social_media_DB->insert_user($dados);

        if ($resultado > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function consultaUsuario()
    {
        $dados = [
            'id' => $_GET['id_user'],
        ];

        $resultado = $this->social_media_DB->getUser($dados);

        if($resultado){
            $response = [
                'success' => true,
                'usuario' => $resultado
            ];
            return $this->response->setJSON($response);
        } else {
            $response = [
                'success' => false,
                'message' => 'Nenhum usuario encontrado.'
            ];

            return $this->response->setJSON($response)->setStatusCode(404);
        }
    }
}
