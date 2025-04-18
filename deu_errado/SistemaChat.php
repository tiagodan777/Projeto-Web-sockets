<?php

namespace Api\Websocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use ReflectionFunctionAbstract;

class SistemaChat implements MessageComponentInterface {
    protected $client;

    public function __construct()
    {
        $this->client = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->client->attach($conn);

        echo "Nova conexão: {$conn->resourceId}\n\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->client as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        echo "Utilizador {$from->resourceId} enviou uma mensagem \n\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->client->detach($conn);

        echo "O utilizador {$conn->resourceId} desconectou-se \n\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $conn->close();

        echo "Ocorreu um erro: {$e->getMessage()} \n\n";
    }
}