<?php

declare(strict_types=1);

namespace Ninjatt\Game;

use WebSocketClient\WebSocketClient;
use WebSocketClient\WebSocketClientInterface;

class FakePlayer implements WebSocketClientInterface
{
    private $client;

    private $id;

    private $x;

    private $y;

    private $ready = false;


    public function onWelcome(array $data)
    {
        $this->id = uniqid();
        $this->publish('char_add', [
            'id' => $this->id,
            'x' => $this->x = random_int(10, 400),
            'y' => $this->y = random_int(10, 400),
            'ninjaType' => 'ninja'
        ]);
    }

    public function move()
    {
        if ($this->ready) {

            $this->publish('char_move', [
                'id' => $this->id,
                'x' => $this->x,
                'y' => $this->y,
                'ninjaType' => 'ninja'
            ]);
        }
    }

    public function subscribe($topic)
    {
        $this->client->subscribe($topic);
    }

    public function unsubscribe($topic)
    {
        $this->client->unsubscribe($topic);
    }

    public function call($proc, $args, \Closure $callback = null)
    {
        $this->client->call($proc, $args, $callback);
    }

    public function publish($topic, $message)
    {
        $this->client->publish($topic, $message);
    }

    public function setClient(WebSocketClient $client)
    {
        $this->client = $client;
    }
}
