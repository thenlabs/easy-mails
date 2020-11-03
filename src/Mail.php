<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class Mail extends \Zend\Mail\Message
{
    protected $readed = false;

    public function setReaded(bool $readed): void
    {
        $this->readed = $readed;
    }

    public function isRead(): bool
    {
        return $this->readed;
    }
}
