<?php

namespace App\Http\Controllers\Personal\Cert;

use App\Http\Controllers\Controller;
use App\Models\Cert\CertApp;
use App\Models\Cert\Certificate;
use App\Models\Teacher\Teacher;
use App\Service\CA\CentreAuthority;
use App\Service\Personal\Schedule\Service;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CertController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

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
            // генерируем пару ключей - открытый/закрытый, которые будут принадлежать преподавателю
            $newPair = CentreAuthority::getNewPair();
            $teacher = Teacher::find($request->teacher_id);
            $request->validate([
                'data' => 'required|array',
                'data.*' => 'required|string',
            ]);
            $data = $request->data;
            CertApp::create([
                'teacher_id' => $teacher->id,
                'public_key' => $newPair['public'],
                'data' => json_encode($data)
            ]);
            $filename =  'private_key_' . $teacher->user->surname . '.key';
            return redirect()->route('personal.cert.index')
                ->withSuccess('Заявка была успешно отправлена!
                После её одобрения вы сможете пользоваться ключом, который был скачан.')
                ->with('data', [
                    'private_key' => $newPair['private'],
                    'filename' => $filename
                ]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
