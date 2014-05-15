<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logros extends CI_Controller {

    public function __construct() {
        parent::__construct();
        session_start();
        $this->load->model('curso_model');
        $this->load->model('usuario_model');
        $this->load->model('usuario_curso_logro_model');
    }

    public function index($idCurso) {
        $this->estoyLogueado();
        $this->verificarMatricula($idCurso);
        $data = array();
        $curso = $this->curso_model->obtenerCursoCompleto($idCurso);
        $data["tab"] = "logros";
        $data["nombre_curso"] = $curso[0]->nombre;
        $data["idCurso"] = $idCurso;
        $data["logros"] = $this->usuario_curso_logro_model->logrosPorCurso($idCurso);
        $this->load->view('include/header', $data);
        $this->load->view('listar_logros_view');
        $this->load->view('include/footer');
    }

    public function verLogro($idCurso, $idLogroUsuario) {
        $data = array();
        $data["tab"] = "logros";
        $data["css"] = array("css/ver_logro");
        $curso = $this->curso_model->obtenerCursoCompleto($idCurso);
        $data["nombre_curso"] = $curso[0]->nombre;
        $data["idCurso"] = $idCurso;
        $data["idLogroUsuario"] = $idLogroUsuario;
        $data["logro"] = $this->usuario_curso_logro_model->obtenerLogro($idLogroUsuario);
        if (sizeof($data["logro"]) == 0) {
            $this->mensaje("El logro no existe", "warning");
        }
        $data["usuario"] = $this->usuario_model->obtenerUsuario(array("id_usuario" => $data["logro"][0]->id_usuario));
        $data["logro"][0]->imagen = base_url() . "assets/img/logro/{$data["logro"][0]->id_logro}.png";
        $this->load->view('include/header', $data);
        $this->load->view('ver_logro_view');
        $this->load->view('include/footer');
    }

}

//http://leta.artrow.net/