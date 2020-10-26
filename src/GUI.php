<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use ThenLabs\StratusPHP\AbstractApp;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class GUI extends AbstractApp
{
    public function getView(array $data = []): string
    {
        return file_get_contents(TEMPLATES_DIR.'/mailbox.html');
    }
}
