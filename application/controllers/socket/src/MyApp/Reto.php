<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Reto implements MessageComponentInterface {

    protected $aulas;
    protected $json;
    protected $enDuelo;

    public function __construct() {
        $this->aulas = array();
        $this->json = array();
        $this->enDuelo = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);

        switch ($data->tipo) {
            case "inicio":
                //aula[1][59]=$from
                $this->aulas[$data->id_curso][$from->resourceId] = $from;
                $this->aulas[$data->id_curso][$from->resourceId]->id_usuario = $data->id_usuario;
                $this->aulas[$data->id_curso][$from->resourceId]->nombre_usuario = $data->nombre_usuario;

                /* Enviarle la lista de estudiantes conectados al estudiante que acabo de ingresar */
                if (isset($this->json[$data->id_curso])) {
                    $from->send(json_encode(array("tipo" => "inicio", "datos" => $this->json[$data->id_curso])));
                }

                /* Informale a los demas estudiantes del curso que un estudiante se acabo de conectar */
                if (!isset($this->json[$data->id_curso][$data->id_usuario])) {
                    $this->json[$data->id_curso][$data->id_usuario] = array("nombre_usuario" => $data->nombre_usuario, "id_resources" => "");
                    $this->json[$data->id_curso][$data->id_usuario]["id_resources"][$from->resourceId] = $from->resourceId;
                    $user = array();
                    $user[$data->id_usuario] = $data->nombre_usuario;
                    $data_to_user = json_encode(array("tipo" => "user_on", "datos" => $user));
                    foreach ($this->aulas[$data->id_curso] as $row) {
                        if ($data->id_usuario != $row->id_usuario) {
                            $row->send($data_to_user);
                        }
                    }
                }
                break;

            case "retar":
                if (isset($this->json[$data->id_curso][$data->usuario_retado])) {
                    $user = array();
                    $user[$data->id_usuario] = $data->nombre_usuario;
                    $data_to_user = json_encode(array("tipo" => "retado", "datos" => $user));
                    foreach ($this->json[$data->id_curso][$data->usuario_retado]["id_resources"] as $row) {
                        $this->aulas[$data->id_curso][$row]->send($data_to_user);
                    }
                }
                break;

            case "acpetar_reto":
                if (isset($this->json[$data->id_curso][$data->id_usuario]) && isset($this->json[$data->id_curso][$data->usuario_retador])) { // Si los dos usuarios etsan conectados se puede proceder
                    $user = array();
                    $user[$data->id_usuario] = $data->nombre_usuario;
                    $data_to_user = json_encode(array("tipo" => "reto_aceptado", "datos" => $user));
                    foreach ($this->json[$data->id_curso][$data->usuario_retador]["id_resources"] as $row) {
                        $this->aulas[$data->id_curso][$row]->send($data_to_user);
                    }
                    foreach ($this->json[$data->id_curso][$data->id_usuario]["id_resources"] as $row) {
                        $this->aulas[$data->id_curso][$row]->send($data_to_user);
                    }
                    $this->enDuelo[$data->usuario_retador] = $data->id_usuario;
                } else if (isset($this->json[$data->id_curso][$data->id_usuario])) { // De lo contrario se le informa al retado que el duelo ha sido cancelado
                    $user = array();
                    $user[$data->id_usuario] = $data->nombre_usuario;
                    $data_to_user = json_encode(array("tipo" => "desconectado_antes", "datos" => $user));
                    foreach ($this->json[$data->id_curso][$data->id_usuario]["id_resources"] as $row) {
                        $this->aulas[$data->id_curso][$row]->send($data_to_user);
                    }
                }
                break;

            case "rechazar_reto":
                if (isset($this->json[$data->id_curso][$data->usuario_retador])) {
                    $user = array();
                    $user[$data->id_usuario] = $data->nombre_usuario;
                    $data_to_user = json_encode(array("tipo" => "reto_rechazado", "datos" => $user));
                    foreach ($this->json[$data->id_curso][$data->usuario_retador]["id_resources"] as $row) {
                        $this->aulas[$data->id_curso][$row]->send($data_to_user);
                    }
                }
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        foreach ($this->aulas as $key => $row) {
            if (array_key_exists($conn->resourceId, $row)) {
                $idUsuario = $row[$conn->resourceId]->id_usuario;
                $nombreUsuario = $row[$conn->resourceId]->nombre_usuario;

                unset($this->json[$key][$idUsuario]["id_resources"][$conn->resourceId]); // Elimino la conexion del json
                if (sizeof($this->json[$key][$idUsuario]["id_resources"]) == 0) { // Si no hay mas conexiones de ese usuario en el json, eliminarlo y notificar
                    unset($row[$conn->resourceId]);
                    unset($this->json[$key][$idUsuario]);
                    $user = array();
                    $user[$idUsuario] = $nombreUsuario;
                    $data_to_user = json_encode(array("tipo" => "user_off", "datos" => $user));
                    foreach ($this->aulas[$key] as $row) {
                        $row->send($data_to_user);
                    }

                    /* Verificar si el suario estaba en duelo */
                    /* Era retador se le informa de la desconexion al retado */
                    if (isset($this->enDuelo[$idUsuario])) {
                        $idUsuarioRetado = $this->enDuelo[$idUsuario];
                        $user = array();
                        $user[$idUsuario] = $nombreUsuario;
                        $data_to_user = json_encode(array("tipo" => "desconectado", "datos" => $user));
                        foreach ($this->json[$key][$idUsuarioRetado]["id_resources"] as $row) {
                            $this->aulas[$key][$row]->send($data_to_user);
                        }
                        unset($this->enDuelo[$idUsuario]);
                    }

                    /* Era el retado se le informa de la desconexion al retador */
                    $idUsuarioRetador = array_search($idUsuario, $this->enDuelo);
                    if ($idUsuarioRetador) {
                        $user = array();
                        $user[$idUsuario] = $nombreUsuario;
                        $data_to_user = json_encode(array("tipo" => "desconectado", "datos" => $user));
                        foreach ($this->json[$key][$idUsuarioRetador]["id_resources"] as $row) {
                            $this->aulas[$key][$row]->send($data_to_user);
                        }
                        unset($this->enDuelo[$idUsuario]);
                    }
                }
            }
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

}
