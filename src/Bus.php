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
        $msg = json_encode($data).'%SSS%';

        socket_write($this->socket, $msg, strlen($msg));
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
