<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Muro extends CI_Controller {

    public function __construct() {
        parent::__construct();
        session_start();
        $this->estoyLogueado();
    }

    public function index($idCurso = -1) {
        $this->verificarMatricula($idCurso);
        $data["tab"] = "muro";
        $data["idCurso"] = $idCurso;
        $this->load->view('include/header', $data);
        $this->load->view('muro_view');
        $this->load->view('include/footer');
    }

}
