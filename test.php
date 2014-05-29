<?php

$entryData = array(
    'cat' => 'Categoria'
    , 'title' => 'Titulo'
    , 'article' => 'Articulo'
    , 'when' => 'Ahora'
);


// This is our new stuff
$context = new ZMQContext();
$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
$socket->connect("tcp://localhost:8080");

$socket->send(json_encode($entryData));
