<?php
require '../php/Mailer.php';
require '../libs/mail/SMTP.php';
require '../libs/mail/Exception.php';
require '../libs/mail/PHPMailer.php';

$to = "zhong@antx.cc";               // 邮件接收者
$subject = "Antx";                    // 邮件主题
$message = "Hello! 这是邮件的内容。";   // 邮件正文
$from = "zhong@antx.cc";             // 邮件发送者
$headers = "From:" . $from;          // 头部信息设置
ini_set('SMTP', 'smtp.antx.cc');
ini_set('smtp_port', 25);
ini_set('username', 'admin@antx.cc');
ini_set('password', 'zhong0911AntxMail');
//while (true){
    $result = mail($to, $subject, $message, $headers);
    if ($result)
        echo "邮件已发送\n";
    else
        echo "邮件未发送\n";
//}