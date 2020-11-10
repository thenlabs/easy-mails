<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use ThenLabs\StratusPHP\AbstractAppWithSElements;
use ThenLabs\ComposedViews\Event\RenderEvent;
use ThenLabs\ComposedViews\Asset\Script;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class Page extends AbstractAppWithSElements
{
    public function getView(array $data = []): string
    {
        return file_get_contents(__DIR__.'/../templates/index.html');
    }

    public function onUpdate(): void
    {
        if ($totalOfUnreadsInbox = count(Folder::inbox()->getUnreads())) {
            $this->badgeInbox->removeClass('d-none');
            $this->badgeInbox->textContent = $totalOfUnreadsInbox;
        } else {
            $this->badgeInbox->addClass('d-none');
        }

        if ($totalOfUnreadsTrash = count(Folder::trash()->getUnreads())) {
            $this->badgeTrash->removeClass('d-none');
            $this->badgeTrash->textContent = $totalOfUnreadsTrash;
        } else {
            $this->badgeTrash->addClass('d-none');
        }
    }

    public function getOwnDependencies(): array
    {
        $script = new Script('easy-mails', null, '');
        $script->setSource("
            setInterval(() => {
                stratusAppInstance.dispatch('update', {}, false);
            }, 2000);
        ");

        $dependencies = parent::getOwnDependencies();
        $dependencies[] = $script;

        return $dependencies;
    }
}
