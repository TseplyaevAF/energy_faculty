<?php


namespace App\Service\Dekanat;


use App\Models\Cert\Certificate;
use App\Service\CA\CentreAuthority;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class DocumentSigner
{
    public function __construct(CentreAuthority $centreAuth)
    {
        $this->centreAuth = $centreAuth;
    }

    public function getCert($teacher) {
        //1. поиск сертификата для подписи документа
        $cert = Certificate::where('teacher_id', $teacher->id)->first();
        try {
            $certFile = Storage::disk('public')->get(json_decode($cert->cert_path));
        } catch (FileNotFoundException $e) {
            throw new \Exception('Сертификат не найден! Пожалуйста, обратитесь к администратору.');
        }

        //2. проверка на срок действия сертификата
        if (!$this->centreAuth->isExpiredCaCert($certFile)) {
            throw new \Exception('Действие сертификата окончено! Пожалуйста, обратитесь в УЦ ЭФ, чтобы
            перевыпустить сертификат.');
        }
        return $cert;
    }

    public function getSignature($dataForSign, $privateKey, $cert) {
        $file = $privateKey->openFile();
        $privateKey = $file->fread($file->getSize());

        //1. подписать документ электронной подписью
        try {
            $signature = $this->centreAuth->sign($dataForSign, $privateKey);
        } catch (\Exception $exception) {
            throw new \Exception('Секретный ключ некорректный!', -2);
        }

        //2. проверка, что подпись корректна для dataForSign и publicKey
        $publicKey = Storage::disk('public')->get(json_decode($cert->public_key_path));
        $res = $this->centreAuth->signIsVerify($dataForSign, $signature, $publicKey);
        if ($res === 0 || $res !== 1) {
            throw new \Exception('Секретный ключ не соответствует публичному ключу! Подписать невозможно.',-1);
        }
        return $signature;
    }
}
