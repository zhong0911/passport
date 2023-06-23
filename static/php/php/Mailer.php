<?php

use PHPMailer\PHPMailer\PHPMailer;
use com\plugins\mail\Exception;


function sendRegisterCode($to, $code): bool
{
    return send163Email($to, "Antx",
        "<!DOCTYPE html><html lang='zh_CN'><head><meta charset='UTF-8'><title>欢迎注册 | Antx</title><meta name='application-name' content='Antx'><meta http-equiv='X-UA-Compatible' content='IE=edge'><meta name='viewport' content='width=device-width, initial-scale=1.0'><meta http-equiv='Content-Security-Policy' content='upgrade-insecure-requests'></head><body><div style='text-align: center'><h3><img  src='https://image.antx.cc/logo/antx/favicon.ico' alt='' width='20px' height='20px'> 欢迎注册 Antx</h3><p>【Antx】验证码: $code, 此验证码用只于账号注册, 5分钟内有效, 请勿泄露和转发。如非本人操作，请忽略此邮件。</p></div></body></html>",
        "验证码: $code, 此验证码用只于账号注册, 5分钟内有效, 请勿泄露和转发。如非本人操作，请忽略此邮件。");
}

function sendLoginCode($to, $code): bool
{
    return send163Email($to, "Antx",
        "<!DOCTYPE html><html lang='zh_CN'><head><meta charset='UTF-8'><title>欢迎登录 | Antx</title><meta name='application-name' content='Antx'><meta http-equiv='X-UA-Compatible' content='IE=edge'><meta name='viewport' content='width=device-width, initial-scale=1.0'><meta http-equiv='Content-Security-Policy' content='upgrade-insecure-requests'></head><body><div style='text-align: center'><h3><img  src='https://image.antx.cc/logo/antx/favicon.ico' alt='' width='20px' height='20px'> 欢迎登录 Antx</h3><p>【Antx】验证码: $code, 此验证码用只于账号登录, 5分钟内有效, 请勿泄露和转发。如非本人操作，请忽略此邮件。</p></div></body></html>",
        "验证码: $code, 此验证码用只于账号登录, 5分钟内有效, 请勿泄露和转发。如非本人操作，请忽略此邮件。");
}

function sendAntxEmail($to, $subject, $body, $altBody): bool
{
    ini_set('SMTP', 'smtp.antx.cc');
    ini_set('smtp_port', 25);
    ini_set('username', 'zhong@antx.cc');
    ini_set('password', 'zhong0911AntxMail');
    return mail($to, $subject, $body);
    //$email = "passport@antx.cc";
    //return sendEmail($email, getEmailPassword($email), getEmailSMTPServer($email), getEmailPort($email), $to, $subject, $body, $altBody);
}

function send163Email($to, $subject, $body, $altBody): bool
{
    $email = "antxcc@163.com";
    return sendEmail($email, getEmailPassword($email), getEmailSMTPServer($email), getEmailPort($email), $to, $subject, $body, $altBody);
}

function sendQQEmail($to, $subject, $body, $altBody): bool
{
    $email = "adisaint@qq.com";
    return sendEmail($email, getEmailPassword($email), getEmailSMTPServer($email), getEmailPort($email), $to, $subject, $body, $altBody);
}

function sendEmail($username, $password, $host, $port, $to, $subject, $body, $altBody): bool
{
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //服务器配置
        $mail->CharSet = "UTF-8";                     //设定邮件编码
        $mail->SMTPDebug = 0;                        // 调试模式输出
        $mail->isSMTP();                             // 使用SMTP
        $mail->Host = $host;                // SMTP服务器
        $mail->SMTPAuth = true;                      // 允许 SMTP 认证
        $mail->Username = $username;                // SMTP 用户名  即邮箱的用户名
        $mail->Password = $password;             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
        $mail->Port = $port;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

        $mail->setFrom($username, 'Antx');  //发件人
        $mail->addAddress($to, 'You');  // 收件人
        //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
        $mail->addReplyTo($username, 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致
        //$mail->addCC('cc@example.com');                    //抄送
        //$mail->addBCC('bcc@example.com');                    //密送

        //发送附件
        // $mail->addAttachment('../xy.zip');         // 添加附件
        // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名

        //Content
        $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}


function getEmailSMTPServer($email): string
{
    return queryEmailData("SELECT server FROM mail WHERE mail.email='$email'", "server");
}

function getEmailPassword($email): string
{
    return queryEmailData("SELECT mail.password FROM mail WHERE mail.email='$email'", "password");
}

function getEmailPort($email): string
{
    return queryEmailData("SELECT mail.port FROM mail WHERE mail.email='$email'", "port");
}

function queryEmailData($sql, $key): string
{
    $conn = mysqli_connect("mysql.db.antx.cc", "root", "zhong0911MySQL", "antx");
    $result = mysqli_query($conn, $sql);
    $res = "";
    while ($row = mysqli_fetch_array($result)) {
        $res = $row[$key];
    }
    $conn->close();
    return $res;
}