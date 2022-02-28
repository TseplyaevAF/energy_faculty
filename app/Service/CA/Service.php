<?php


namespace App\Service\CA;

use App\Mail\User\PasswordMail;
use App\Models\CertApp;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function accept($data) {
        try {
            DB::beginTransaction();
            $certApp = CertApp::find($data['certAppId']);
            $ca_cert = Storage::disk('public')->get('ca/cert.dat');
            $file = $data['private_key']->openFile();
            $private_key = $file->fread($file->getSize());

            // выдаем сертификат преподавателю
            $teacherCert = self::createUserCert($certApp->id, $certApp->teacher, $private_key, $ca_cert);

            // генерируем пару ключей - открытый/закрытый, которые будут принадлежать преподавателю
            $newPair = self::createNewPair();

            $certPath = 'ca/certs/teachers/' . $certApp->teacher->id . '/cert.dat';
            $publicKeyPath = 'ca/certs/teachers/' . $certApp->teacher->id . '/public_key.pem';

            Certificate::create([
                'cert_path' => json_encode($certPath),
                'public_key_path' => json_encode($publicKeyPath),
                'teacher_id' => $certApp->teacher->id
            ]);

            Storage::disk('public')->put($certPath, $teacherCert['cert']);
            Storage::disk('public')->put($publicKeyPath, $newPair['public']);

            $filename =  'private_key.pem';
            $file = fopen('php://temp', 'w+');
            fwrite($file, $newPair['private']);
            rewind($file);

            $teacherData["email"] = $certApp->teacher->user->email;
            $teacherData["title"] = "Здравствуйте!";
            $teacherData["body"] = "Это закрытый ключ Вашей электронной подписи.\n
                            Пожалуйста, никому не передавайте данный файл!";

            Mail::send('mail.teacher.signature', $teacherData, function($message) use($teacherData, $file, $filename)
            {
                $message->to($teacherData["email"])
                    ->subject($teacherData["title"]);
                $message->attachData(stream_get_contents($file), $filename);
            });

            fclose($file);

            $certApp->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
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

    static private function createUserCert($serialNumber, $teacher, $private, $ca_cert)
    {
        $arr = array(
            "organizationName" => "Энергетический факультет, кафедра: " . $teacher->chair->title,
            "commonName" => $teacher->user->surname . ' ' . $teacher->user->name . ' ' . $teacher->user->patronymic,
            "UID" => $teacher->id,
            "countryName" => "RU",
            "emailAddress" => $teacher->user->email,
            "serialNumber" => $serialNumber
        );
        $csr = openssl_csr_new($arr, $private);
        $cert = openssl_csr_sign($csr, $ca_cert, $private, $days = 30);
        openssl_x509_export($cert, $str_cert);
        return array('cert' => $str_cert);
    }
}
