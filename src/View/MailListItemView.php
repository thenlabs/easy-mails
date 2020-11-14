<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails\View;

use ThenLabs\ComposedViews\AbstractView;
use ThenLabs\EasyMails\Mail;

class MailListItemView extends AbstractView
{
    protected $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function getView(array $data = []): string
    {
        ob_start();
        require __DIR__.'/views/mail/list-item.php';
        return ob_get_clean();
    }
}
