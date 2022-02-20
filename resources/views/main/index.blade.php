<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
</head>
<body>
<?php
//Данные для генерации сигнатуры
//$data = 'my data';
//$public_key_pm = file_get_contents('public_key.pem');
//$signature = file_get_contents('signature.dat');
//$r = openssl_verify($data, $signature, $public_key_pm, "sha256WithRSAEncryption");

//$data2 = 'my data2';

//$private_key_pem = file_get_contents('private_key.pem');
//openssl_sign($data2, $signature2, $private_key_pem, OPENSSL_ALGO_SHA256);
//file_put_contents('signature2.dat', $signature2);

//$signature2 = file_get_contents('signature2.dat');
//$r2 = openssl_verify($data2, $signature2, $public_key_pm, "sha256WithRSAEncryption");
//dd($r2);


////Создаём новую пару открытый/закрытый ключ
//$new_key_pair = openssl_pkey_new(array(
//    "private_key_bits" => 2048,
//    "private_key_type" => OPENSSL_KEYTYPE_RSA,
//));
//openssl_pkey_export($new_key_pair, $private_key_pem);
//
//
//$details = openssl_pkey_get_details($new_key_pair);
//
//$public_key_pem = $details['key'];
//
////Вычисляем подпись
//openssl_sign($data, $signature, $private_key_pem, OPENSSL_ALGO_SHA256);
//
////Сохраняем
//file_put_contents('private_key.pem', $private_key_pem);
//file_put_contents('public_key.pem', $public_key_pem);
//file_put_contents('signature.dat', $signature);
//
////Сверяем подпись
//$r = openssl_verify($data, $signature, $public_key_pem, "sha256WithRSAEncryption");
//dd($r);
?>
</body>
</html>
