<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $data = 'my data';

//        //Создает сертификат
//        $keys = self::getCACert();
//        openssl_x509_verify($keys['cert'], $keys['public']);
//        file_put_contents('private_key.pem', $keys['private']);
//        file_put_contents('public_key.pem', $keys['public']);
//        file_put_contents('cert.crt', $keys['cert']);

//        //Ставим подпись для данных $data
//        $private  = file_get_contents('private_key.pem');
//        openssl_sign($data, $signature, $private, OPENSSL_ALGO_SHA256);
//        file_put_contents('signature333.dat', $signature);

//        // Проверяем подлинность подписи
//        $signature = file_get_contents('signature333.dat');
//        $public_key_pem = file_get_contents('public_key.pem');
//        $r = openssl_verify($data, $signature, $public_key_pem, "sha256WithRSAEncryption");
//        dd($r);

//        // Проверяем подлинность сертификата
//        $cert = file_get_contents('cert.crt');
//        $public_key = openssl_pkey_get_public($cert);
//        $public_key_details = openssl_pkey_get_details($public_key);
//        $public_key_string = $public_key_details['key'];
//        $r2 = openssl_x509_parse($cert);
//        dd($r2);
        return view('main.index');
    }

    static private function getCACert()
    {
        $config = array(
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
            "private_key_bits" => 512
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $private);
        $arr = array(
            "organizationName" => "ЗабГУ, Энергетический факультет",
            "commonName" => "Машкин Владимир Анатольвеич",
            "organizationalUnitName" => "Кафедра ИВТиПМ",
            "businessCategory" => "доцент",
            "UID" => 1,
            "countryName" => "RU",
            "emailAddress" => 'mashkin@mail.ru',
            "serialNumber" => '432423'
        );
        $csr = openssl_csr_new($arr, $private);
        $cert = openssl_csr_sign($csr, null, $private, $days = 365);
        openssl_x509_export($cert, $str_cert);
        $public_key = openssl_pkey_get_public($str_cert);
        $public_key_details = openssl_pkey_get_details($public_key);
        $public_key_string = $public_key_details['key'];
        return array('private' => $private, 'cert' => $str_cert, 'public' => $public_key_string);
    }
}
