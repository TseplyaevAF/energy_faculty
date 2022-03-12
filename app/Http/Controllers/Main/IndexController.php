<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\CA\CentreAuthority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public function __invoke()
    {
//        $data = 'my data';
//        $ca = new CentreAuthority();
//        CentreAuthority::getNewPair();


//        $ca_cert = Storage::disk('public')->get('ca/ca.crt');
//        $private_key = file_get_contents('ca.key');
        //Создает сертификат
//        $keys = self::getCACert();
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
//        $public_key = file_get_contents('public_key.pem');
//        $r2 = openssl_x509_verify($cert, $public_key);
//        dd($r2);
//        $newPair = self::createNewPair();
//        file_put_contents('private_key.pem', $newPair['private']);
//        file_put_contents('public_key.pem', $newPair['public']);
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
            "organizationName" => "ЗабГУ, энергетический факультет",
            "countryName" => "RU",
            "emailAddress" => 'energo_zabgu@mail.ru',
        );
        $csr = openssl_csr_new($arr, $private);
        $cert = openssl_csr_sign($csr, null, $private, $days = 365);
        openssl_x509_export($cert, $str_cert);
        $public_key = openssl_pkey_get_public($str_cert);
        $public_key_details = openssl_pkey_get_details($public_key);
        $public_key_string = $public_key_details['key'];
        return array('private' => $private, 'cert' => $str_cert, 'public' => $public_key_string);
    }

    static private function createNewPair() {
        $new_key_pair = openssl_pkey_new(array(
            "private_key_bits" => 512,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ));
        openssl_pkey_export($new_key_pair, $private_key_pem);
        $details = openssl_pkey_get_details($new_key_pair);
        $public_key_pem = $details['key'];
        return [
            'public' => $public_key_pem,
            'private' => $private_key_pem
        ];
    }
}
