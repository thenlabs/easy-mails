<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use Exception;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class App
{
    protected array $defaultConfig = [
        'server' => [
            'http' => [
                'host' => '127.0.0.1',
                'port' => 2020,
            ],
        ],
    ];

    protected GUI $gui;

    protected array $config;

    protected $httpServerSocket;

    public function __construct(array $config = [])
    {
        $this->gui = new GUI('/controller.php');
        $this->config = array_merge($this->defaultConfig, $config);
    }

    public function run(): void
    {
        $this->startHttpServer();

        $this->startLoop();
    }

    protected function startHttpServer(): void
    {
        $this->httpServerSocket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));

        extract($this->config['server']['http']);

        if (! @socket_bind($this->httpServerSocket, $host, $port)) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);

            throw new Exception($errormsg);
        }

        socket_listen($this->httpServerSocket, 1);
        socket_set_nonblock($this->httpServerSocket);
    }

    protected function processHttpServer(): void
    {
        $clientSocket = socket_accept($this->httpServerSocket);

        if (! $clientSocket) {
            return;
        }

        $requestMessage = socket_read($clientSocket, 1024, PHP_BINARY_READ);
        $parts = explode("\n\r", $requestMessage);
        $headers = explode("\n", $parts[0]);

        $firstLine = array_shift($headers);

        [$method, $url, $protocol] = explode(' ', $firstLine);

        $responseHeaders = [
            "{$protocol} 200 OK",
            'Content-Type: text/html',
            '',
        ];

        $responseMessage = implode("\n", $responseHeaders)."\n\r";

        if ($url === '/') {
            $responseMessage .= $this->gui;
        }

        socket_write($clientSocket, $responseMessage, strlen($responseMessage));
        socket_close($clientSocket);
    }

    protected function startLoop(): void
    {
        while (true) {
            $this->processHttpServer();
        }
    }
}
