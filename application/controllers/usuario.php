<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        session_start();
        $this->load->model('curso_model');
        $this->load->model('usuario_model');
        $this->load->model('modulo_model');
        $this->load->model('usuario_x_curso_model');
        $this->load->model('usuario_x_evaluacion_model');
        $this->load->model('bitacora_model');
        $this->load->model('usuario_curso_logro_model');
    }

    public function info() {

        $this->escapar($_GET);
        if (empty($_SESSION["idUsuario"]) || empty($_GET["idCurso"]) || empty($_GET["idModulo"]) || empty($_GET["idUsuario"])) {
            exit();
        }
        $idCurso = $_GET["idCurso"];
        $idModulo = $_GET["idModulo"];
        $idUsuario = $_GET["idUsuario"];
        $usuario = $this->usuario_model->obtenerUsuario(array("id_usuario" => $idUsuario));
        $data = array("id_curso" => $idCurso, "id_usuario" => $idUsuario);
        $matricula = $this->usuario_x_curso_model->obtenerRegistro($data);
        $this->verificarMatricula($idCurso);
        $puntajePorModulo = $this->modulo_model->puntajePorModuloPorCurso($idCurso);
        $tiempoSegundos = $this->bitacora_model->obtenerTiempoLogueado($idUsuario);
        $tiempoHoras = round($tiempoSegundos[0]->tiempo / 3600, 1);
        $lastLogin = $this->bitacora_model->lastLogin($idUsuario);
        if (!$lastLogin) {
            $lastLogin = "n/a";
        } else {
            $lastLogin = $lastLogin[0]->fecha_ingreso;
        }
        $logros = $this->usuario_curso_logro_model->logrosPorUsuario($idUsuario);
        ?>
      

        <div class="no-mp col-xs-12">
            <table class="table table-condensed">
                  <?php if (sizeof($logros) > 0) { ?>
                <tr>
                    <td> Logros:</td>
                    <td> <?php foreach ($logros as $row) { ?>
                            <img height="60" src="<?= base_url() . "assets/img/logro/{$row->id_logro}.png"; ?>">
                        <?php } ?></td>
                <tr>
                  <?php } ?>
                <tr>
                    <td>Matriculado desde:</td>
                    <td><?= $matricula[0]->fecha ?></td>
                <tr>
                <tr>
                    <td>Último acceso:</td>
                    <td><?= $lastLogin ?></td>
                <tr>
                <tr>
                    <td>Tiempo logueado:</td>
                    <td><?= $tiempoHoras ?> horas</td>
                <tr>
                <tr>
                    <td>Correo:</td>
                    <td><?= $usuario[0]->correo ?></td>
                <tr>
                <tr>
                    <td>Módulo</td>
                    <td>Puntaje</td>
                <tr>
                    <?php
                    foreach ($puntajePorModulo as $row) {
                        ?>
                    <tr>
                        <td> <?= $row->nombre ?>:</td>
                        <td><?= $row->puntaje ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>

        <?php
    }

}
