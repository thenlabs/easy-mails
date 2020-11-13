<tr>
    <td>
        <div class="icheck-primary">
            <input type="checkbox" value="" id="check-<?= $this->mail->getId() ?>">
            <label for="check-<?= $this->mail->getId() ?>"></label>
        </div>
    </td>
    <td class="mailbox-star"><!-- <a href="javascript:;"></a> --></td>
    <td class="mailbox-name"><a href="javascript:;"><?= $this->mail->getMessage()->getSender() ?></a></td>
    <td class="mailbox-subject">
        <b><?= $this->mail->getMessage()->getSubject() ?></b>
        <!-- - Trying to find a solution to this problem... -->
    </td>
    <td class="mailbox-attachment"></td>
    <td class="mailbox-date"><?= $this->mail->getCreated()->format('l jS \of F Y h:i:s A') ?></td>
</tr>
