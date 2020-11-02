<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use ThenLabs\StratusPHP\AbstractAppWithSElements;
use ThenLabs\ComposedViews\Event\RenderEvent;
use ThenLabs\ComposedViews\Asset\Script;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class StratusApp extends AbstractAppWithSElements
{
    public function __construct(string $controllerUri)
    {
        parent::__construct($controllerUri);

        $this->addFilter([$this, '_addCustomScript']);
    }

    public function getView(array $data = []): string
    {
        return file_get_contents(__DIR__.'/../templates/index.html');
    }

    public function _addCustomScript(RenderEvent $event): void
    {
        $script = new Script('easy-mails', null, '');
        $script->setSource(<<<JAVASCRIPT
            setInterval(() => {
                stratusAppInstance.dispatch('update', {}, false);
            }, 1000);
        JAVASCRIPT);

        $event->filter('body')->append($script->render());
    }

    public function onClickBody(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->contentTitle->textContent .= $this->contentTitle->textContent;
            sleep(1);
        }
    }
}
