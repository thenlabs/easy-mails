<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class App
{
    protected array $defaultConfig = [
        'server' => [
            'http' => [
                'host' => '127.0.0.1',
                'port' => 9000,
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

        socket_bind($this->httpServerSocket, $host, $port);
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
        $parts = explode("\r\n", $requestMessage);
        $headers = explode("\n", $parts[0]);

        $firstLine = array_shift($headers);

        [$method, $url, $protocol] = explode(' ', $firstLine);

        $responseMessage = implode("\n", $headers)."\r\n";

        if ($url === '/') {
            $responseMessage .= $this->gui->render();
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
