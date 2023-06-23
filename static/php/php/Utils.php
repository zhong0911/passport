<?php

error_reporting(E_ERROR | E_PARSE);
function getWebLogo($url): string
{
    try {
        $html = file_get_contents($url);
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        return $xpath->query('//link[@rel="icon"]/@href')->item(0)->nodeValue;
    } catch (Exception $e) {
        return '';
    }
}

function redirect($url): void
{
    header('location:' . $url, false, 301);
    exit;
}

function isUsername($string): bool
{
    return (preg_match("/^[a-zA-Z][a-zA-Z0-9]{4,11}$/i", $string));
}

function isCode($string): bool
{
    return (preg_match("/^[0-9]{6}$/i", $string));
}

function isEmail($string): bool
{
    return (preg_match("/^\w+((-\w+)|(\.\w+))*@[A-Za-z0-9]+(([.\-])[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/", $string));
}

function isPassword($string): bool
{
    return (preg_match("/^[a-zA-Z0-9]{8,26}$/i", $string));
}

function isUrl($string): bool
{
    return (preg_match("/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/i", $string));
}

function generateRandomString($length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, $charactersLength - 1)];
    return $randomString;
}


function alertAndBack($message): void
{
    echo "<script>alert('$message')</script>";
    echo "<script>window.history.go(-1)</script>";
}

function alert($message): void
{
    echo "<script>alert('$message')</script>";
}

function into($url): void
{
    echo "<script>window.location.href='$url'</script>";
}

function back(): void
{
    echo "<script>window.history.go(-1)</script>";

}

function backAndAlert($message): void
{
    echo "<script>window.history.go(-1)</script>";
    echo "<script>alert('$message')</script>";
}

function isHttps()
{
    if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
        return true;
    } elseif (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) {
        return true;
    } elseif (isset($_SERVER['HTTP_X_CLIENT_SCHEME']) && $_SERVER['HTTP_X_CLIENT_SCHEME'] == 'https') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        return true;
    } elseif (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') {
        return true;
    } elseif (isset($_SERVER['HTTP_EWS_CUSTOME_SCHEME']) && $_SERVER['HTTP_EWS_CUSTOME_SCHEME'] == 'https') {
        return true;
    }
    return false;
}




