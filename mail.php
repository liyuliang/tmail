<?php
date_default_timezone_set("Asia/Calcutta");
require_once './config.php';
require_once './core/PhpImap/__autoload.php';
require_once './functions.php';
require_once './options.php';
session_start();
error_reporting(E_ALL);
$unseen = filter_input(INPUT_GET, 'unseen', FILTER_SANITIZE_STRING);
$count = 0;
if($option['ssl'] == "yes") {
    $mailbox = new PhpImap\Mailbox('{'.$config['host'].'/imap/ssl}INBOX', $config['user'], $config['pass'], __DIR__);
} else {
    $mailbox = new PhpImap\Mailbox('{'.$config['host'].'/imap/novalidate-cert}INBOX', $config['user'], $config['pass'], __DIR__);
}
if(!isset($_SESSION["address"])) {
    die("Address not allocated. Please refresh page.");
}

$ids = $mailbox->searchMailbox('BEFORE ' . date('d-M-Y', strtotime($option['deleteDays']." days ago")));
foreach ($ids as $id) {
    $mailbox->deleteMail($id);
}
$mailbox->expungeDeletedMails();
$files = glob('downloads/*'); 
foreach($files as $file){
  if(is_file($file))
    unlink($file); 
}
$address = $_SESSION["address"];
$toList = "TO ".$address;
$ccList = "CC ".$address;
$bccList = "BCC ".$address;
$mailIdsTo = $mailbox->searchMailbox($toList);
$mailIdsCc = $mailbox->searchMailbox($ccList);
$mailIdsBcc = $mailbox->searchMailbox($bccList);
$mailsIds = array_reverse(array_unique(array_merge($mailIdsTo,$mailIdsCc,$mailIdsBcc)));
if($unseen == 1) {
    $unseenIds = $mailbox->searchMailbox("UNSEEN");
    $mailsIds = array_intersect($mailsIds,$unseenIds);
}
foreach ($mailsIds as $mailID) {
    $mail = $mailbox->getMail($mailID);
    ?>
    <div class="searchPanel" id="mail<?php echo $mailID; ?>">
        <button class="accordion"><?php echo $mail->subject ?><br>From : <?php echo $mail->fromName; ?>&lt;<?php echo $mail->fromAddress; ?>&gt;</button>
        <div class="panel">    
            <button type="button" class="btn btn-danger deleteButton" onclick="deleteMail(<?php echo $mailID; ?>)">Delete</button>
            <button id="downloadBtn<?php echo $mailID; ?>" type="button" class="btn btn-info downloadButton" onclick="downloadMail(<?php echo $mailID; ?>)">Download</button><br><br><br>
            <p>Date : <?php echo $mail->date; ?><br><br></p>
            <?php if ($mail->textHtml == "") { ?>
            <div><?php echo $mail->textPlain; ?></div><br><br>
            <?php } else { ?>
            <div><?php echo $mail->textHtml; ?></div><br><br>
            <?php } ?>
            To : <?php echo $mail->toString; ?><br><br>
        </div>
    </div>
    <?php
    $count++;
}
if($count==0 && $unseen!=1) {
    echo '<div class="cssload-container"><ul class="cssload-flex-container"><li><span class="cssload-loading"></span></li></div></div>';
}
?>