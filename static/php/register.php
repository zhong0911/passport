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
    foreach ($posts as $key => $value) $posts[$key] = trim($value);
    $action = $posts['action'] ?? '';
    switch ($action) {
        case "send_register_code":
        {
            $email = $posts['email'] ?? '';
            if ($email) {
                $code = rand(100000, 999999);
                if (sendRegisterCode($email, $code)) {
                    $_SESSION['register_code'] = $code;
                    $_SESSION['register_email'] = $email;
                    $_SESSION['sent_register_code'] = true;
                    $_SESSION['register_code_error_count'] = 0;
                    $_SESSION['register_code_valid_time'] = date("Y-m-d H:i:s", strtotime("+5 minutes"));
                    echo json_encode(
                        array(
                            'success' => true,
                            'message' => 'Verification code of register sent successfully'
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
        case "verify_register_code":
        {
            $code = $posts['code'] ?? '';
            $email = $posts['email'] ?? '';
            $correct_code = $_SESSION['register_code'] ?? '';
            $correct_email = $_SESSION['register_email'] ?? '';
            if ($code && $email && $correct_email && $correct_code) {
                $now_time = date('Y-m- d H:i:s');
                $valid_time = $_SESSION['register_code_valid_time'];
                $error_count = $_SESSION['register_code_error_count'] ?? 6;
                if (($email == $correct_email) && ($code == $correct_code) && ($now_time < $valid_time) && ($error_count < 5)) {
                    $_SESSION['register_code'] = '';
                    $_SESSION['register_email'] = '';
                    $_SESSION['verified_register_code'] = true;
                    echo json_encode(
                        array(
                            'success' => true,
                            'message' => 'Verified verification code successfully'
                        ),
                        true
                    );
                } else {
                    $_SESSION['register_code_error_count']++;
                    $_SESSION['verified_register_code'] = false;
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
        case "register":
        {
            $username = $posts['username'] ?? '';
            $password = $posts['password'] ?? '';
            $email = $posts['email'] ?? '';
            $now_time = date('Y-m- d H:i:s');
            if (($username && $password && $email)) {
                $verified_register_code = $_SESSION['verified_register_code'] ?? false;
                if ($verified_register_code) {
                    if ((isUsername($username) && isPassword($password) && isEmail($email))) {
                        $uid = getNewUid();
                        if (addUser($uid, $username, hash("sha512", $password), $email, "")) {
                            $_SESSION['username'] = $username;
                            $_SESSION['password'] = hash("sha512", $password);
                            $_SESSION['register_code'] = '';
                            $_SESSION['register_successful'] = true;
                            echo json_encode(
                                array(
                                    'success' => true,
                                    'message' => 'Registered successfully',
                                    'uid' => $uid,
                                    'username' => $username
                                ),
                                true
                            );
                        } else {
                            echo json_encode(
                                array(
                                    'success' => false,
                                    'message' => "Register failed, Server failure, please contact the webmaster as soon as possible"
                                ),
                                true
                            );
                        }
                    } else {
                        echo json_encode(
                            array(
                                'success' => false,
                                'message' => 'Register failed, error about format of text(username, password or email)'
                            ),
                            true
                        );
                    }
                } else {
                    echo json_encode(
                        array(
                            'success' => false,
                            'message' => 'Register failed, verification code didnt verify'
                        ),
                        true
                    );
                }
            } else {
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => 'Register failed, username, password or email is empty'
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
