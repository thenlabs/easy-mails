<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use ThenLabs\StratusPHP\AbstractAppWithSElements;
use ThenLabs\ComposedViews\Event\RenderEvent;
use ThenLabs\ComposedViews\Asset\Script;
use SplObjectStorage;

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
        if ($totalOfUnreadsInbox = count($this->filterUnreads(Folder::inbox()))) {
            $this->badgeInbox->removeClass('d-none');
            $this->badgeInbox->textContent = $totalOfUnreadsInbox;
        } else {
            $this->badgeInbox->addClass('d-none');
        }

        if ($totalOfUnreadsTrash = count($this->filterUnreads(Folder::trash()))) {
            $this->badgeTrash->removeClass('d-none');
            $this->badgeTrash->textContent = $totalOfUnreadsTrash;
        } else {
            $this->badgeTrash->addClass('d-none');
        }
    }

    public function onClickInboxLink(): void
    {
        $this->tbody->innerHTML = <<<HTML
            <tr>
                <td>
                    <div class="icheck-primary">
                        <input type="checkbox" value="" id="check1">
                        <label for="check1"></label>
                    </div>
                </td>
                <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to this problem...
                </td>
                <td class="mailbox-attachment"></td>
                <td class="mailbox-date">5 mins ago</td>
            </tr>
        HTML;

        $this->onUpdate();
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

    private function filterUnreads(SplObjectStorage $folder): array
    {
        $result = [];
        $readedFolder = Folder::readed();

        foreach ($folder as $mail) {
            if (! $readedFolder->contains($mail)) {
                $result[] = $mail;
            }
        }

        return $result;
    }
}
