<?php


function getAccountPassword($username): string
{
    return queryAccountData("SELECT password FROM account WHERE username='" . $username . "'", "password");
}

function getAccountEmail($username): string
{
    return queryAccountData("SELECT email FROM account WHERE username='" . $username . "'", "email");
}

function getAccountUid($username): string
{
    return queryAccountData("SELECT uid FROM account WHERE username='" . $username . "'", "uid");
}

function getEmailUsername($email): string
{
    return queryAccountData("SELECT username FROM account WHERE email='" . $email . "'", "username");
}

function getAccountPhone($username): string
{
    return queryAccountData("SELECT phone FROM account WHERE username='" . $username . "'", "phone");
}


function getUsernameExists($username): bool
{
    $res = queryAccountData("SELECT username FROM account WHERE username='" . $username . "'", "username");
    return $res != "";
}

function getUidExists($uid): bool
{
    $res = queryAccountData("SELECT uid FROM account WHERE uid=$uid", "uid");
    return $res != "";
}


function getNewUid(): int
{
    do {
        $uid = rand(15000, 9999999);
    } while (getUidExists($uid));
    return $uid;
}


function getEmailExists($email): bool
{
    $res = queryAccountData("SELECT email FROM account WHERE email='" . $email . "'", "email");
    return $res != "";
}

function getAccountAvatar($username): string
{
    if (getUsernameExists($username))
        return "https://user.antx.cc/users/$username/avatar/" . queryAccountData("SELECT avatar FROM info WHERE username='" . $username . "'", "avatar") . '.png';
    else
        return '';
}

function getAccountNickname($username): string
{
    return queryAccountData("SELECT nickname FROM info WHERE username='" . $username . "'", "nickname");
}

function getAccountBirthday($username): string
{
    return queryAccountData("SELECT birthday FROM info WHERE username='" . $username . "'", "birthday");
}

function getAccountGender($username): string
{
    return queryAccountData("SELECT gender FROM info WHERE username='" . $username . "'", "gender");
}

function getAccountAge($username): string
{
    $birthday = getAccountBirthday($username);
    return $birthday === '1000-01-01' ? '未知' : date("Y-m-d H:i:s") - $birthday;
}

function addUser($uid, $username, $password, $email, $phone): bool
{
    mkdir("/www/wwwroot/user/users/$username/avatar/", 0777, true);
    copy("/www/wwwroot/user/users/__default/avatar/1.png", "/www/wwwroot/user/users/$username/avatar/1.png");
    return (insertData("insert into account (uid, username, password, email, phone) values ($uid, '$username',  '$password', '$email', '$phone')") &&
        addUserInfo($uid, $username, '', "User_$uid", '', '', '', '', '', '', '', '', '', $email) &&
        addUserPrivacy($uid, $username) && addUserStatus($uid, $username));
}

function addUserInfo($uid, $username, $signature, $nickname, $gender, $birthday, $job, $primary_school, $middle_school, $university, $company, $location, $hometown, $email): bool
{
    if (!$birthday) $birthday = '1000-01-01';
    return insertData("INSERT INTO info (uid, username, ranking, avatar, signature, nickname, gender, birthday, job, primary_school,  middle_school, university, company, location, hometown, email) VALUES ($uid, '$username', default,  '1', '$signature', '$nickname', '$gender', '$birthday', '$job', '$primary_school', '$middle_school','$university', '$company', '$location', '$hometown', '$email')");
}

function addUserStatus($uid, $username): bool
{
    return insertData("INSERT INTO status (uid, username, status, reason, ip_1, ip_2, ip_3, ip_4, ip_5, ip_6, ip_7, ip_8, ip_9, ip_10) VALUES ($uid, '$username', 1, null, null, null, null, null, null, null, null, null, null, null)");
}

function addUserPrivacy($uid, $username): bool
{
    return insertData("INSERT INTO privacy (uid, username, gender, birthday, job, primary_school, middle_school, university, company, location, hometown, email, phone) VALUES ($uid, '$username', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);");
}

function queryAccountData($sql, $key): string
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


function insertData($sql): bool
{
    $conn = mysqli_connect("mysql.db.antx.cc", "root", "zhong0911MySQL", "antx");
    $res = false;
    if ($conn->query($sql) === TRUE) {
        $res = true;
    }
    $conn->close();
    return $res;
}