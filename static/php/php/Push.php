<?php

$urls = array(
    'https://passport.antx.cc//login/index.html',
    'https://passport.antx.cc/register/index.html',
);
$api = 'https://data.zz.baidu.com/urls?site=https://passport.antx.cc&token=fQjDVAdzZCCQdkpa';
$ch = curl_init();
$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
