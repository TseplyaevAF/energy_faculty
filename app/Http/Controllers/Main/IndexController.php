<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $data = 'my data';

//        //Создает сертификат
//        $keys = self::get_keys(1, 'Машкин Владимир Анатольевич');
//        openssl_x509_verify($keys['cert'], $keys['public']);
//        file_put_contents('private_key.pem', $keys['private']);
//        file_put_contents('public_key.pem', $keys['public']);
//        file_put_contents('cert.dat', $keys['cert']);
//
//        //Ставим подпись для данных $data
//        $private_key_pem = file_get_contents('private_key.pem');
//        openssl_sign($data, $signature, $private_key_pem, OPENSSL_ALGO_SHA256);
//        file_put_contents('signature.dat', $signature);


        $public_key_pem = file_get_contents('public_key.pem');
        $signature = file_get_contents('signature.dat');
        $cert = file_get_contents('cert.dat');

        // Проверяем подлинность подписи
        $r = openssl_verify($data, $signature, $public_key_pem, "sha256WithRSAEncryption");

        // Проверяем подлинность сертификата
        $r2 = openssl_x509_parse($cert);
        dd($r2);
        return view('main.index');
    }

    static private function get_keys($login, $full_name)
    {
        $config = array(
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "private_key_bits" => 512
        );
        $res = openssl_pkey_new($config);
        $privKey = '';
        openssl_pkey_export($res, $privKey);
        $arr = array(
            "organizationName" => "Энергетический факультет",
            "commonName" => $full_name,
            "UID" => $login,
            "countryName" => "RU"
        );
        $csr = openssl_csr_new($arr, $privKey);
        $cert = openssl_csr_sign($csr, null, $privKey, $days = 365);
        openssl_x509_export($cert, $str_cert);
        $public_key = openssl_pkey_get_public($str_cert);
        $public_key_details = openssl_pkey_get_details($public_key);
        $public_key_string = $public_key_details['key'];
        return array('private' => $privKey, 'cert' => $str_cert, 'public' => $public_key_string);
    }
}
