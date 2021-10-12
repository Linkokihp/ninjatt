<?php

declare(strict_types=1);

namespace Ninjatt\Game;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Server implements WampServerInterface
{
    /**
     * @var array[]
     */
    private $playerData = [];

    /**
     * @var ConnectionInterface[]
     */
    private $connections;

    public function __construct()
    {
        $this->connections = [];
    }

    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections[$conn->WAMP->sessionId] = $conn;
    }

    public function onClose(ConnectionInterface $conn)
    {
        $sessId = $conn->WAMP->sessionId;
        if (isset($this->playerData[$sessId])) {
            unset($this->playerData[$sessId]);
        }
        unset($this->connections[$conn->WAMP->sessionId]);
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        switch ($topic->getId()) {
            case "synchronize":
                $conn->callResult($id, ['players' => $this->playerData]);
                break;
            default:
                $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
        }
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $sessId = $conn->WAMP->sessionId;
        switch ($topic->getId()) {
            case "char_remove":
                if (isset($this->playerData[$sessId])) {
                    unset($this->playerData[$sessId]);
                }

                break;

            case "char_add":
                $this->playerData[$sessId] = [
                    'lastMove' => null
                ];
                break;

            case "char_move":
                $this->playerData[$sessId]['lastMove'] = $event;
                break;

            case "char_msg":
                if ($event['msg'][0] == "/") {
                    $event['ninjaType'] = substr($event['msg'], 1);
                    $this->playerData[$sessId]['lastMove']['ninjaType'] = $event['ninjaType'];
                    $event['msg'] = "";
                    break;
                }
                break;

        }

        $topic->broadcast($event, [$conn->WAMP->sessionId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    public function getPlayersCount()
    {
        return count($this->connections);
    }
}
