<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use Zend\Mail\Message;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class Mail
{
    protected $id;

    protected $message;

    protected $readed = false;

    public function __construct(Message $message)
    {
        $this->id = uniqid('mail-');

        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function isRead(): bool
    {
        return $this->readed;
    }

    public function setReaded(bool $readed): void
    {
        $this->readed = $readed;
    }
}
