<?php

namespace App\Http\Controllers\Personal\Cert;

use App\Http\Controllers\Controller;
use App\Models\Cert\CertApp;
use App\Models\Cert\Certificate;
use App\Service\Personal\Schedule\Service;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
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
        return view('personal.cert.create');
    }

    public function store(Request $request) {
        try {
            CertApp::create([
                'teacher_id' => $request->teacher_id
            ]);
            return redirect()->route('personal.cert.index');
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
