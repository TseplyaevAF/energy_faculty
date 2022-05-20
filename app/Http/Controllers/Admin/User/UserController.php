<?php

namespace App\Http\Controllers\Admin\User;

use App\Exports\StudentsCreateExport;
use App\Http\Controllers\Controller;
use App\Http\Filters\UserFilter;
use App\Http\Requests\Admin\User\FilterRequest;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\Student\ImportRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Imports\StudentsImport;
use App\Models\Chair;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\User;
use App\Service\User\Service;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\Models\Media;

class UserController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        $users = User::filter($filter)->paginate(10);
        $roles = User::getRoles();
        $chairs = Chair::all();
        return view('admin.user.index', compact('users', 'roles', 'chairs'));
    }

    public function search(Request $request) {
        $data = User::select('surname')
                ->where('surname', 'like', "%{$request->term}%")
                ->get();
        $dataModified = array();
        foreach ($data as $item)
        {
            $dataModified[] = $item->surname;
        }

        return response()->json($dataModified);
    }

    public function create()
    {
        $roles = User::getRoles();
        $groups = Group::all();
        $chairs = Chair::all();
        $disciplines = Discipline::all();
        return view('admin.user.create', compact('roles', 'groups', 'chairs', 'disciplines'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['phone_number'] = str_replace(array('+', ' ', '(' , ')', '-'), '', $data['phone_number']);

        $this->service->store($data);

        return redirect()->route('admin.user.index');
    }

    public function edit(User $user)
    {
        $user->phone_number = mb_substr($user->phone_number, 1, strlen($user->phone_number));
        $media = $user->getMedia()->first();
        if (isset($media)) {
            return redirect()->route('file.get', ['user' => $user, 'filename' => $media->id]);
        }

        if ($user->role_id == User::ROLE_TEACHER) {
            $chairs = Chair::all();
            $disciplines = Discipline::all();
            return view('admin.user.edit', compact('user', 'chairs', 'disciplines'));
        } else if ($user->role_id == User::ROLE_STUDENT) {
            $groups = Group::all();
            return view('admin.user.edit', compact('user', 'groups'));
        }
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $data['phone_number'] = str_replace(array('+', ' ', '(' , ')', '-'), '', $data['phone_number']);

        $this->service->update($data, $user);

        return redirect()->route('admin.user.index');
    }

    public function destroy(User $user)
    {
        if (!$user->role->teacher_id) {
            $user->role->teacher->delete();
        } else if ($user->role->student_id != null) {
            $user->role->student->delete();
        }
        $user->role->delete();
        $user->delete();
        return redirect()->route('admin.user.index');
    }

    public function studentsExport() {
        $fileName = 'Шаблон студентов.xlsx';
        $file =  Excel::raw(new StudentsCreateExport(), 'Xlsx');
        return response()->json([
            'file_name' => $fileName,
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($file)
        ]);
    }

    public function studentsImport(ImportRequest $request) {
        $data = $request->validated();
        try {
            ini_set('memory_limit', '-1');
            Excel::import(new StudentsImport($data), $data['excel_file']);
        } catch (\Exception $exception) {
            return response($exception->getMessage(), 405);
        }
        return response('Аккаунты для студентов успешно созданы!', 200);
    }
}
