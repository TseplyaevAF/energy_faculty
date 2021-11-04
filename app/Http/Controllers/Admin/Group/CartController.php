<?php

namespace App\Http\Controllers\Admin\Group;

use App\Http\Controllers\Controller;
use App\Models\Group\Group;
use App\Models\Group\GroupDiscipline;

class CartController extends Controller
{
    public function index()
    {
        $groups = Group::onlyTrashed()->get();
        return view('admin.group.cart.index', compact('groups'));
    }

    public function show($groupId)
    {
        $group = Group::withTrashed()->find($groupId);
        return view('admin.group.cart.show', compact('group'));
    }

    public function restore($groupId)
    {
        $group = Group::withTrashed()->find($groupId);
        $group->restore();
        return redirect()->route('admin.group.cart.index');
    }

    public function delete($groupId)
    {
        try {
            $groupDisciplines = GroupDiscipline::where('group_id', $groupId)->get();
            foreach ($groupDisciplines as $groupDiscipline) {
                $groupDiscipline->delete();
            }
            $group = Group::withTrashed()->find($groupId);
            $group->forceDelete();
        } catch (\Exception $exception) {
            abort(500);
        }

        return redirect()->route('admin.group.cart.index');
    }
}
