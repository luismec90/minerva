<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Perfil extends CI_Controller {

    public function __construct() {
        parent::__construct();
        session_start();
        $this->estoyLogueado();
        $this->load->model('usuario_model');
    }

    public function index() {
        $this->load->model('afiliacion_model');

        $data["tab"] = "perfil";
        $data["css"] = array("css/registrase");
        $data["usuario"] = $this->usuario_model->obtenerUsuario(array("id_usuario" => $_SESSION["idUsuario"]));
        $data["afiliaciones"] = $this->afiliacion_model->obtenerAfiliaciones();
        $this->load->view('include/header', $data);
        $this->load->view('perfil_view');
        $this->load->view('include/footer');
    }

    public function actualizar() {
        $this->escapar($_POST);

        $estudiante = $this->usuario_model->obtenerUsuario(array("id_usuario" => $_SESSION["idUsuario"]));
        if (!empty($_POST["currentPassword"]) && !empty($_POST["password"]) && !empty($_POST["rePassword"])) {
            if ($_POST["password"] == $_POST["rePassword"]) {

                if ($estudiante[0]->password == sha1($_POST["currentPassword"])) {
                    $this->usuario_model->actualizar(array("password" => sha1($_POST["password"])), array("id_usuario" => $_SESSION["idUsuario"]));
                } else {
                    $this->mensaje("La contraseña no es correcta", "error", "perfil");
                }
            } else {
                $this->mensaje("Las contraseñas no coinciden", "error", "perfil");
            }
        }

        if (!isset($_POST["nombres"]) || !isset($_POST["apellidos"]) || !isset($_POST["email"]) || !isset($_POST["afiliacion"])) {
            $this->mensaje("Datos incompletos", "error", "perfil");
        }

        $data = array(
            "id_afiliacion" => $_POST["afiliacion"],
            "nombres" => $_POST["nombres"],
            "apellidos" => $_POST["apellidos"],
            "correo" => $_POST["email"],
            "imagen" => $this->avatar($estudiante)
        );

        $this->usuario_model->actualizar($data, array("id_usuario" => $_SESSION["idUsuario"]));

        $_SESSION["nombre"] = $_POST["nombres"] . " " . $_POST["apellidos"];
        $this->mensaje("Datos actualizados exitosamente", "success", "perfil");
    }

    private function avatar($usuario) {
        if ($_FILES["file"]["name"]) {
            if ($usuario[0]->imagen != "default.png" && file_exists("assets/img/avatares/{$usuario[0]->imagen}")) {
                unlink("assets/img/avatares/{$usuario[0]->imagen}");
            }
            $extension = explode(".", $_FILES["file"]["name"]);
            $extension = end($extension);
            $nombreAvatar = $_SESSION["idUsuario"] . "." . $extension;
            move_uploaded_file($_FILES["file"]["tmp_name"], "assets/img/avatares/$nombreAvatar");
            return $nombreAvatar;
        } else {
            return $usuario[0]->imagen;
        }
    }

}
