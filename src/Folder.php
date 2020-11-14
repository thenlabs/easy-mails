<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class Folder
{
    static protected $folders = [];

    protected $fullPath;

    public function __construct(string $path, string $name)
    {
        $this->fullPath = $path.'/.easy-mails/'.$name;

        if (! is_dir($this->fullPath)) {
            mkdir($this->fullPath, 0777, true);
        }
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function getAll(): array
    {
        $mails = [];

        foreach (glob($this->fullPath.'/*.mail') as $fileName) {
            $mail = unserialize(file_get_contents($fileName));

            if ($mail instanceof Mail) {
                $mails[] = $mail;
            }
        }

        return $mails;
    }

    public function getUnreads(): array
    {
        return array_filter($this->getAll(), function (Mail $mail) {
            if (! $mail->isRead()) {
                return true;
            }
        });
    }

    public function add(Mail $mail): void
    {
        $fileName = $this->fullPath.'/'.$mail->getId().'.mail';

        file_put_contents($fileName, serialize($mail));
    }

    public function drop(Mail $mail): void
    {
        $fileName = $this->fullPath.'/'.$mail->getId().'.mail';

        unlink($fileName);
    }

    public static function getFolder(string $name): self
    {
        if (! isset(static::$folders[$name])) {
            static::$folders[$name] = new self(getcwd(), $name);
        }

        return static::$folders[$name];
    }

    public static function inbox(): self
    {
        return static::getFolder('inbox');
    }

    public static function sent(): self
    {
        return static::getFolder('sent');
    }

    public static function trash(): self
    {
        return static::getFolder('trash');
    }
}
