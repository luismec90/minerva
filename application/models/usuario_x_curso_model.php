<?php

class Usuario_x_curso_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function obtenerRegistro($where) {
        return $this->db->get_where("usuario_x_curso", $where)->result();
    }

    public function crear($data) {
        $this->db->insert('usuario_x_curso', $data);
    }

    public function actualizar($data, $where) {
        $this->db->update('usuario_x_curso', $data, $where);
    }

}
