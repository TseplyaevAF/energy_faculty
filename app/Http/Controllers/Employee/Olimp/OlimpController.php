<?php

namespace App\Http\Controllers\Employee\Olimp;

use App\Http\Controllers\Controller;
use App\Models\News\Category;
use App\Models\Olimp;

class OlimpController extends Controller
{
    public function index()
    {
        $olimps = Olimp::with('news')->get();
        return view('employee.olimps.index',
            compact('olimps'));
    }

    public function createOlimp() {
        $categories = [
            'conferenc' => Category::find(Category::CONFERENCES),
            'olimp' => Category::find(Category::OLYMPICS),
        ];
        return view('employee.olimps.ajax-views.create-olimp', compact('categories'));
    }

    public function getOlimpTypes(Category $category) {
        $olimpTypes = [];
        $olimps = Olimp::with('news')
            ->with('olimp_type')
            ->whereHas('News', function($query) use($category){
            $query->where('category_id', $category->id);
         })->get();
        foreach ($olimps->unique('olimp_type_id') as $olimp) {
            $olimpTypes[] = $olimp->olimp_type;
        }
        echo json_encode($olimpTypes);
    }
}
