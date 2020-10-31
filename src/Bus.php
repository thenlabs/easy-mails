<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use ThenLabs\StratusPHP\Bus\BusInterface;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class Bus implements BusInterface
{
    protected $socket;

    public function __construct($socket)
    {
        $this->socket = $socket;
    }

    public function open()
    {
    }

    public function write(array $data)
    {
        $json = json_encode($data);

        socket_write($this->socket, $json, strlen($json));
    }

    public function close()
    {
        @socket_close($this->socket);
    }

    public function __sleep()
    {
        return [];
    }
}
