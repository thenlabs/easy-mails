<?php

use ThenLabs\EasyMails\Page;
use ThenLabs\EasyMails\Bus;
use ThenLabs\HttpServer\HttpServer;
use ThenLabs\HttpServer\Event\RequestEvent;
use ThenLabs\StratusPHP\StratusRequest;
use ThenLabs\ComposedViews\Event\RenderEvent;
use ThenLabs\Components\Event\FilterDependenciesEvent;
use ThenLabs\ComposedViews\Asset\Script;
use function Opis\Closure\{serialize as s, unserialize as u};

$httpServer = new HttpServer([
    'host' => '127.0.0.1',
    'port' => 8800,
    'document_root' => __DIR__.'/public',
]);

$dispatcher = $httpServer->getDispatcher();

$appSource = null;

$dispatcher->addListener(RequestEvent::class, function ($event) use (&$appSource, &$emails) {
    $request = $event->getRequest();
    $response = $event->getResponse();

    $uri = $event->getRequestUri();

    if ($uri == '/') {
        $page = new Page('/controller');

        $page->on('update', [$page, 'onUpdate']);

        $response->setContent($page->render());
        $appSource = s($page);

        $event->stopPropagation();
    } elseif (0 === strpos($uri, '/controller')) {
        $page = u($appSource);

        $page->setEmails($emails);

        $clientSocket = $event->getClientSocket();
        $bus = new Bus($clientSocket);
        $page->setBus($bus);

        $stratusRequest = StratusRequest::createFromJson(
            $request->request->get('stratus_request')
        );

        $stratusResponse = $page->run($stratusRequest);

        if ($stratusResponse->isSuccessful()) {
            $appSource = s($page);
        }

        $bus->close();

        $event->stopPropagation();
    }
});

return $httpServer;
