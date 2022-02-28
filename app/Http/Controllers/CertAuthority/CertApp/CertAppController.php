<?php

namespace App\Http\Controllers\CertAuthority\CertApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\CA\StoreRequest;
use App\Models\CertApp;
use Illuminate\Support\Facades\DB;
use App\Service\CA\Service;

class CertAppController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index() {
        $certApps = CertApp::all();
        return view('ca.cert-app.index', compact('certApps'));
    }

    public function accept(CertApp $certApp)
    {
        $teacher = $certApp->teacher;
        return view('ca.cert-app.accept', compact('certApp', 'teacher'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->service->accept($data);

        return redirect()->route('ca.cert_app.index');
    }
}
