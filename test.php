<?php
/**
 * Created by PhpStorm.
 * User: liyuliang
 * Date: 2018/10/10
 * Time: 下午12:36
 */

/* connect to gmail */
$username = 'shenmedouyaoshi@outlook.com';
$password = '12345678aA!';
$hostname = '{outlook.office365.com:993/imap/ssl}INBOX';

/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

$mailIds = imap_search($inbox,'ALL',SE_UID);


/* grab emails */
$emails = imap_search($inbox,'ALL',SE_UID);

imap_expunge($inbox);

/* if emails are returned, cycle through each... */
if($emails) {

    /* begin output var */
    $output = '';

    /* put the newest emails on top */
    rsort($emails);

    /* for every email... */
    foreach($emails as $email_number) {

        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);
        $message = imap_fetchbody($inbox,$email_number,2);
        mb_detect_encoding($message);
        $message = mb_convert_encoding($message, "UTF-8");

        /* output the email header information */
        $output.= '<div class="toggler '.(imap_utf8($overview[0]->seen) ? 'read' : 'unread').'">';
        $output.= '<span class="subject">'.imap_utf8($overview[0]->subject).'</span> ';
        $output.= '<span class="from">'.imap_utf8($overview[0]->from).'</span>';
        $output.= '<span class="date">on '.imap_utf8($overview[0]->date).'</span>';
        $output.= '</div>';

        /* output the email body */
        $output.= '<div class="body">'.$message.'</div>';
    }

    echo $output;
}

/* close the connection */
imap_close($inbox);