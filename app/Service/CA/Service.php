<?php


namespace App\Service\CA;

use App\Models\Cert\CertApp;
use App\Models\Cert\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Service\CA\CentreAuthority;

class Service
{
    public function accept($data) {
        $certApp = CertApp::find($data['certAppId']);
        $file = $data['private_key']->openFile();
        $private_key = $file->fread($file->getSize());

        $centreAuth = new CentreAuthority();

        // проверка, относится ли секретный ключ к корневому сертификату
        if (!$centreAuth->checkCaCert($private_key)) {
            throw new \Exception('Секретный ключ не соответствует корневому сертификату!');
        }

        // проверка на срок действия сертификата
        if (!$centreAuth->checkDateValidCaCert()) {
            throw new \Exception('Действие корневого сертификата окончено! Необходимо его перевыпустить.');
        }

        try {
            DB::beginTransaction();

            // выдаем сертификат преподавателю
            $teacherCert = $centreAuth->getTeacherCert($certApp->id, $certApp->teacher, $private_key, 60);

            $certPath = 'ca/certs/teachers/' . $certApp->teacher->id . '/cert.crt';
            $publicKeyPath = 'ca/certs/teachers/' . $certApp->teacher->id . '/public_key.key';

            Certificate::create([
                'cert_path' => json_encode($certPath),
                'public_key_path' => json_encode($publicKeyPath),
                'teacher_id' => $certApp->teacher->id
            ]);

            Storage::disk('public')->put($certPath, $teacherCert['cert']);
            Storage::disk('public')->put($publicKeyPath, $certApp->public_key);

//            $teacherData["email"] = $certApp->teacher->user->email;
//            $teacherData["title"] = "Здравствуйте!";
//            $teacherData["body"] = "Это сертификат, подтверждающий Вашу электронную подпись";
//
//            Mail::send('mail.teacher.signature', $teacherData, function($message) use($teacherData, $file, $filename)
//            {
//                $message->to($teacherData["email"])
//                    ->subject($teacherData["title"]);
//                $message->attachData(stream_get_contents($file), $filename);
//            });

            $certApp->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }
}
