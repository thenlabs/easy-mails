<?php

use TheFox\Smtp\Server;
use TheFox\Smtp\Event;
use Zend\Mail\Message;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$contextOptions = [];

$logger = new Logger('smtp_example');
$logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

$options = [
    'ip' => '127.0.0.1',
    'port' => 20026,
    'logger' => $logger,
];
$server = new Server($options);

$sendEvent = new Event(Event::TRIGGER_NEW_MAIL, null, function (Event $event, string $from, array $rcpts, Message $mail) use (&$emails) {
    $emails[] = $mail;
});

$server->addEvent($sendEvent);

return $server;
