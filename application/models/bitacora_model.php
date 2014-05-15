<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bitacora_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function crearRegistro() {
        $this->db->insert('bitacora', array("id_estudiante" => $_SESSION["idUsuario"], "fecha_ingreso" => date("Y-m-d H:i:s")));
        return $this->db->insert_id();
    }

    function actializarRegistro($where) {
        $this->db->update('bitacora', array("fecha_salida" => date("Y-m-d H:i:s")), $where);
        return $this->db->insert_id();
    }

    function obtenerTiempoLogueado($idUsuario) {
        $query = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(FECHA_SALIDA,FECHA_INGRESO))) tiempo FROM bitacora where FECHA_INGRESO is not NULL AND FECHA_SALIDA is not NULL and id_usuario='$idUsuario'";
        return $this->db->query($query)->result();
    }

    function lastLogin($idUsuario) {
        $query = "SELECT * FROM bitacora WHERE id_usuario='$idUsuario' ORDER BY fecha_ingreso DESC LIMIT 0,1";
        return $this->db->query($query)->result();
    }

}
