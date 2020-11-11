<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails\View;

use ThenLabs\ComposedViews\AbstractView;
use ThenLabs\EasyMails\Mail;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 */
class MailListItemView extends AbstractView
{
    protected $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function getView(array $data = []): string
    {
        return <<<HTML
            <tr>
                <td>
                  <div class="icheck-primary">
                    <input type="checkbox" value="" id="check1">
                    <label for="check1"></label>
                  </div>
                </td>
                <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to this problem...</td>
                <td class="mailbox-attachment"></td>
                <td class="mailbox-date">5 mins ago</td>
            </tr>
        HTML;
    }
}
