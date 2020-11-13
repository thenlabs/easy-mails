<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use ThenLabs\StratusPHP\AbstractAppWithSElements;
use ThenLabs\StratusPHP\Annotation\StratusEventListener;
use ThenLabs\ComposedViews\Event\RenderEvent;
use ThenLabs\ComposedViews\Asset\Script;
use ThenLabs\EasyMails\View\MailListItemView;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class Page extends AbstractAppWithSElements
{
    protected $currentFolderName = 'inbox';

    public function getView(array $data = []): string
    {
        return file_get_contents(__DIR__.'/../templates/index.html');
    }

    public function onClickLinkInbox(): void
    {
        $this->currentFolderName = 'inbox';
        $this->onUpdate();
    }

    public function onClickLinkSent(): void
    {
        $this->currentFolderName = 'sent';
        $this->onUpdate();
    }

    public function onClickLinkTrash(): void
    {
        $this->currentFolderName = 'trash';
        $this->onUpdate();
    }

    /**
     * @StratusEventListener(frontListener="
     *     let selectedItems = [];
     *
     * ")
     */
    public function onClickButtonMoveToTrash($event): void
    {
        $selectedItems = $event->getEventData()['selectedItems'];

        $currentFolder = $this->getCurrentFolder();
        $mails = $currentFolder->getAll();

        foreach ($mails as $mail) {
            if (in_array($mail->getId(), $selectedItems)) {
                $currentFolder->drop($mail);
                Folder::trash()->add($mail);
            }
        }

        $this->update();
    }

    public function onUpdate(): void
    {
        $this->contentTitle->textContent = ucfirst($this->currentFolderName);

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

        $tbody = '';
        foreach ($this->getCurrentFolder()->getAll() as $mail) {
            $listItemView = new MailListItemView($mail);
            $tbody .= $listItemView->render();
        }
        $this->tbody->innerHTML = $tbody;
    }

    public function getCurrentFolder(): Folder
    {
        return Folder::getFolder($this->currentFolderName);
    }
}
