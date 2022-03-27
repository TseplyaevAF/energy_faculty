<?php


namespace App\Service\CA;

use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;


class CentreAuthority
{
    private $caCert;

    public function __construct($caCertPath = 'assets/ca/ca.crt')
    {
        try {
            $this->caCert =  File::get(public_path($caCertPath));
        } catch (FileNotFoundException $exception) {
            return $exception->getMessage();
        }
    }

    public function getCaCert() {
        return openssl_x509_parse($this->caCert);
    }

    public function isVerifyCert($cert) {
        $publicKey = openssl_pkey_get_public($this->caCert);
        $details = openssl_pkey_get_details($publicKey);
        $public_key_pem = $details['key'];
        return openssl_x509_verify($cert, $public_key_pem);
    }

    /**
     * Создание новой пары - открытый/закрытый ключи
     */
    static public function createNewPair()
    {
        $new_key_pair = openssl_pkey_new(array(
            "private_key_bits" => 2048,
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

    static public function createCSR($subject, $privateKey) {
        $arr = array(
            "organizationName" => "ЗабГУ, Энергетический факультет",
            "organizationalUnitName" => $subject->chair->title,
            "commonName" => $subject->user->surname . ' ' . $subject->user->name . ' ' . $subject->user->patronymic,
            "businessCategory" => $subject->post,
            "UID" => $subject->id,
            "countryName" => "RU",
            "emailAddress" => $subject->user->email,
            "serialNumber" => $subject->id
        );
        $csr = openssl_csr_new($arr, $privateKey, [
            'digest_alg' => 'sha256'
        ]);
        openssl_csr_export($csr, $out);
        return $out;
    }

    /**
     * Создание сертификата для преподавателя ЭФ
     * @param $csr
     * @param $caPrivateKey
     * @param int $days
     * @return array
     */
    public function createTeacherCert($csr, $caPrivateKey, $days = 365)
    {
        $cert = openssl_csr_sign($csr, $this->caCert, $caPrivateKey, $days, array('digest_alg'=>'sha256'));
        openssl_x509_export($cert, $str_cert);
        return array('cert' => $str_cert);
    }

    public function isPrivateKeyToCaCert($privateKey) {
        return openssl_x509_check_private_key($this->caCert, $privateKey);
    }

    public function isExpiredCert($cert = null) {
        $cert = !isset($cert) ? $this->getCaCert() : openssl_x509_parse($cert);
        return date('Y-m-d H:i:s', $cert['validFrom_time_t'])
            < date('Y-m-d H:i:s');
    }

    public function sign($data, $privateKey) {
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return $signature;
    }

    public function signIsVerify($data, $signature, $cert) {
        $public_key = openssl_pkey_get_public($cert);
        $public_key_details = openssl_pkey_get_details($public_key);
        $public_key_string = $public_key_details['key'];
        return openssl_verify($data, $signature, $public_key_string, "sha256WithRSAEncryption");
    }
}
