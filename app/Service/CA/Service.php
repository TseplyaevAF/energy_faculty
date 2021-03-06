<?php


namespace App\Service\CA;

use App\Models\Cert\CertApp;
use App\Models\Cert\Certificate;
use App\Service\Dekanat\DocumentSigner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Service\CA\CentreAuthority;

class Service
{
    private $centreAuth;

    public function __construct(CentreAuthority $centreAuth)
    {
        $this->centreAuth = $centreAuth;
    }

    public function accept($data) {
        $certApp = CertApp::find($data['certAppId']);
        $file = $data['private_key']->openFile();
        $private_key = $file->fread($file->getSize());

        // проверка, относится ли секретный ключ к корневому сертификату
        if (!$this->centreAuth->isPrivateKeyToCaCert($private_key)) {
            throw new \Exception('Секретный ключ не соответствует корневому сертификату!');
        }

        // проверка на срок действия корневого сертификата
        if (!$this->centreAuth->isExpiredCert()) {
            throw new \Exception('Действие корневого сертификата окончено! Необходимо его перевыпустить.');
        }

        try {
            DB::beginTransaction();

            // выдаем сертификат преподавателю
            $teacherCert = $this->centreAuth->createTeacherCert($certApp->csr, $private_key, 365);

            $certPath = 'ca/certs/teachers/' . $certApp->teacher->id . '/cert.crt';

            Certificate::create([
                'cert_path' => json_encode($certPath),
                'teacher_id' => $certApp->teacher->id
            ]);

            Storage::disk('public')->put($certPath, $teacherCert['cert']);

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
