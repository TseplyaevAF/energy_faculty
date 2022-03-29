<?php

namespace App\Http\Controllers\Personal\Cert;

use App\Http\Controllers\Controller;
use App\Models\Cert\CertApp;
use App\Models\Cert\Certificate;
use App\Models\Teacher\Teacher;
use App\Service\CA\CentreAuthority;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CertController extends Controller
{
    public function index()
    {
        Gate::authorize('isTeacher');
        $teacher = auth()->user()->teacher;
        $cert = Certificate::where('teacher_id', $teacher->id)->first();
        if (!isset($cert)) {
            $certApp = CertApp::all()->where('teacher_id', $teacher->id)->first();
            return view('personal.cert.index', compact('teacher','cert', 'certApp'));
        }
        try {
            $cert = Storage::disk('public')->get(json_decode($cert->cert_path));
            $cert = openssl_x509_parse($cert);
        } catch (FileNotFoundException $e) {

        }
        return view('personal.cert.index', compact('teacher','cert'));
    }

    public function create() {
        Gate::authorize('isTeacher');
        Gate::authorize('create-cert-app', [auth()->user()->teacher]);
        return view('personal.cert.create');
    }

    public function store(Request $request) {
        Gate::authorize('isTeacher');
        Gate::authorize('create-cert-app', [auth()->user()->teacher]);
        try {
            $request->validate([
                'data' => 'required|array',
                'data.*' => 'required|string',
            ]);
            $data = $request->data;

            // генерируем пару ключей - открытый/закрытый, которые будут принадлежать преподавателю
            $newPair = CentreAuthority::createNewPair();
            $teacher = Teacher::find($request->teacher_id);

            // генерируем csr - запрос на получение сертификата
            $csr = CentreAuthority::createCSR($teacher, $newPair['private']);

            CertApp::create([
                'teacher_id' => $teacher->id,
                'csr' => $csr,
                'data' => json_encode($data)
            ]);

            $filename =  'private_key_' . $teacher->user->surname . '.key';
            return redirect()->route('personal.cert.index')
                ->withSuccess('Заявка была успешно отправлена!
                После её одобрения вам станет доступен для использования Ваш закрытый ключ.')
                ->with('data', [
                    'private_key' => $newPair['private'],
                    'filename' => $filename
                ]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
