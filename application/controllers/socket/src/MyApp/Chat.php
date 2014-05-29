<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

    protected $usuarios;
    protected $json;

    public function __construct() {
        $this->usuarios = array();
        $this->json = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->usuarios[$conn->resourceId] = $conn;
        $conn->send(json_encode(array("tipo" => "inicio", "datos" => $this->json)));
        echo "Connection {$conn->resourceId} has conected\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $data = json_decode($msg);
        switch ($data->tipo) {
            case "inicio":
                $this->usuarios[$from->resourceId]->id_usuario = $data->id_usuario;
                $this->usuarios[$from->resourceId]->nombre_usuario = $data->nombre_usuario;
                if (!isset($this->json[$data->id_usuario])) {
                    $this->json[$data->id_usuario] = $data->nombre_usuario;
                    $user = array();
                    $user[$data->id_usuario] = $data->nombre_usuario;
                    $data_to_user = json_encode(array("tipo" => "user_on", "datos" => $user));
                    foreach ($this->usuarios as $row) {
                        if ($data->id_usuario != $row->id_usuario) {
                            $row->send($data_to_user);
                        }
                    }
                }
                break;
            case "retar":
                $user = array();
                $user[$data->id_usuario] = $data->nombre_usuario;
                $data_to_user = json_encode(array("tipo" => "retado", "datos" => $user));
                foreach ($this->usuarios as $row) {
                    if ($data->usuario_retado == $row->id_usuario) {
                        $row->send($data_to_user);
                    }
                }
                break;

            case "acpetar_reto":
                $user = array();
                $user[$data->id_usuario] = $data->nombre_usuario;
                $data_to_user = json_encode(array("tipo" => "reto_aceptado", "datos" => $user));
                foreach ($this->usuarios as $row) {
                    if ($data->usuario_retador == $row->id_usuario) {
                        $row->send($data_to_user);
                    }
                }
                break;
            default:
                break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $idUsuario = $this->usuarios[$conn->resourceId]->id_usuario;
        $nombreUsuario = $this->usuarios[$conn->resourceId]->nombre_usuario;
        unset($this->usuarios[$conn->resourceId]);
        $flag = true;
        foreach ($this->usuarios as $row) {
            if ($idUsuario == $row->id_usuario) {
                $flag = false;
                break;
            }
        }
        if ($flag) {
            unset($this->json[$idUsuario]);
            $user = array();
            $user[$idUsuario] = $nombreUsuario;
            $data_to_user = json_encode(array("tipo" => "user_off", "datos" => $user));
            foreach ($this->usuarios as $row) {
                $row->send($data_to_user);
            }
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

}
