<?php
require './php/Account.php';
require './php/Utils.php';
require './php/Mailer.php';
require './libs/mail/SMTP.php';
require './libs/mail/Exception.php';
require './libs/mail/PHPMailer.php';

error_reporting(E_ERROR | E_PARSE);
header('Access-Control-Allow-Origin: *');
session_set_cookie_params(0, '/', '.antx.cc');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $posts = $_POST;
    foreach ($posts as $key => $value) {
        $posts[$key] = trim($value);
    }
    $action = $posts['action'] ?? '';
    switch ($action) {
        case "account_login" :
        {
            $username = $posts['username'] ?? '';
            $password = hash("sha512", $posts['password'] ?? '');
            $correct_password = getAccountPassword($username);
            if (($correct_password !== "" && $password !== "") && ($correct_password == $password)) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['login_successful'] = true;
                echo json_encode(
                    array(
                        'success' => true,
                        'message' => 'Logged in successfully'
                    ),
                    true
                );
            } else {
                $_SESSION['username'] = '';
                $_SESSION['password'] = '';
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => 'Account or password error'
                    ),
                    true
                );
            }
            break;
        }
        case "email_login":
        {
            $email = $posts['email'] ?? '';
            $verified = $_SESSION['verified_login_code'] ?? '';
            $login_email = $_SESSION['login_email'] ?? '';
            if ($email == $login_email && $verified) {
                $username = getEmailUsername($email);
                $password = getAccountPassword($username);
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                echo json_encode(
                    array(
                        'success' => true,
                        'message' => 'Logged in successfully'
                    ),
                    true
                );
            } else {
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => 'Login failed'
                    ),
                    true
                );
            }
            break;
        }
        case "send_login_code":
        {
            $email = $posts['email'] ?? '';
            if ($email) {
                $code = rand(100000, 999999);
                if (sendLoginCode($email, $code)) {
                    $_SESSION['login_code'] = $code;
                    $_SESSION['login_email'] = $email;
                    $_SESSION['sent_login_code'] = true;
                    $_SESSION['login_code_error_count'] = 0;
                    $_SESSION['login_code_valid_time'] = date("Y-m-d H:i:s", strtotime("+5 minutes"));
                    echo json_encode(
                        array(
                            'success' => true,
                            'message' => 'Verification code of login sent successfully'
                        ),
                        true
                    );
                } else {
                    echo json_encode(
                        array(
                            'success' => false,
                            'message' => 'Server failure, please contact the webmaster as soon as possible'
                        ),
                        true
                    );
                }
            } else {
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => 'Email cannot be empty'
                    ),
                    true
                );
            }
            break;
        }
        case "verify_login_code":
        {
            $email = $posts['email'] ?? '';
            $code = $posts['code'] ?? '';
            $correct_code = $_SESSION['login_code'] ?? '';
            $correct_email = $_SESSION['login_email'] ?? '';
            if ($code && $email && $correct_email && $correct_code) {
                $now_time = date('Y-m- d H:i:s');
                $valid_time = $_SESSION['login_code_valid_time'];
                $error_count = $_SESSION['login_code_error_count'] ?? 6;
                if (($email == $correct_email) && ($code == $correct_code) && ($now_time < $valid_time) && ($error_count < 5)) {
                    $_SESSION['login_code'] = '';
                    $_SESSION['login_email'] = '';
                    $_SESSION['verified_login_code'] = true;
                    $username = getEmailUsername($email);
                    $password = getAccountPassword($username);
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    echo json_encode(
                        array(
                            'success' => true,
                            'message' => 'Verify verification code successfully'
                        ),
                        true
                    );
                } else {
                    $_SESSION['login_code_error_count']++;
                    $_SESSION['verified_login_code'] = false;
                    echo json_encode(
                        array(
                            'success' => false,
                            'message' => 'Verify failed, Reason: verification code is invalid',
                            'error_count' => $error_count
                        ),
                        true
                    );
                }

            } else {
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => 'The verification code is empty or the email is empty or no verification code has been sent'
                    ),
                    true
                );
            }
            break;
        }
        default :
            echo json_encode(
                array(
                    'success' => false,
                    'message' => 'No such option'
                ),
                true
            );
    }
} else {
    echo json_encode(
        array(
            'success' => false,
            'message' => 'No post request'
        ),
        true
    );
}