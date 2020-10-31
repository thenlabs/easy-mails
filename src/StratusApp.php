<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use ThenLabs\StratusPHP\AbstractAppWithSElements;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class StratusApp extends AbstractAppWithSElements
{
    public function getView(array $data = []): string
    {
        return file_get_contents(__DIR__.'/../templates/index.html');
    }

    public function onClickBody(): void
    {
        $this->showAlert('Hi');
    }
}
