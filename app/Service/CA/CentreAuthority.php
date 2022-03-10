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

    /**
     * Создание новой пары - открытый/закрытый ключи
     */
    static public function getNewPair()
    {
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

    /**
     * Создание сертификата для преподавателя ЭФ
     * @param $serialNumber
     * @param $teacher
     * @param $caPrivateKey
     * @param int $days
     * @return array
     */
    public function getTeacherCert($serialNumber, $teacher, $caPrivateKey, $days = 365)
    {
        $arr = array(
            "organizationName" => "ЗабГУ, Энергетический факультет",
            "organizationalUnitName" => $teacher->chair->title,
            "commonName" => $teacher->user->surname . ' ' . $teacher->user->name . ' ' . $teacher->user->patronymic,
            "businessCategory" => $teacher->post,
            "UID" => $teacher->id,
            "countryName" => "RU",
            "emailAddress" => $teacher->user->email,
            "serialNumber" => $serialNumber
        );
        $csr = openssl_csr_new($arr, $caPrivateKey, [
            'digest_alg' => 'sha256'
        ]);
        $cert = openssl_csr_sign($csr, $this->caCert, $caPrivateKey, $days);
        openssl_x509_export($cert, $str_cert);
        return array('cert' => $str_cert);
    }

    public function checkCaCert($privateKey) {
        return openssl_x509_check_private_key($this->caCert, $privateKey);
    }

    public function checkDateValidCaCert($cert = null) {
        $cert = !isset($cert) ? $this->getCaCert() : openssl_x509_parse($cert);
        return date('Y-m-d H:i:s', $cert['validFrom_time_t'])
            < date('Y-m-d H:i:s');
    }

    public function getSignature($data, $privateKey) {
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return $signature;
    }

    public function checkSignature($data, $signature, $publicKey) {
        return openssl_verify($data, $signature, $publicKey, "sha256WithRSAEncryption");
    }
}
