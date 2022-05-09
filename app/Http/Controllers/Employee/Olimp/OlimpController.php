<?php

namespace App\Http\Controllers\Employee\Olimp;

use App\Http\Controllers\Controller;
use App\Models\News\Category;
use App\Models\Olimp;
use App\Models\OlimpType;
use Illuminate\Http\Request;

class OlimpController extends Controller
{
    public function storeOlimpType(Request $request)
    {
        $olimpType = $request->input('olimp_type');

        $olimpType = OlimpType::firstOrCreate([
            'title' => $olimpType
        ]);

        echo json_encode($olimpType->id);
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
        $olimps = self::getOlimpsByCategory($category->id)->orderBy('updated_at', 'desc')->get();
        foreach ($olimps->unique(function ($item) {
            return $item['year'] . $item['olimp_type_id'];
        }) as $olimp) {
            $tempArray['olimp_type'] = $olimp->olimp_type;
            $tempArray['news_id'] = $olimp->news->id;
            $tempArray['year'] = $olimp->year;
            $olimpTypes[] = $tempArray;
        }
        echo json_encode($olimpTypes);
    }

    private function getOlimpsByCategory($categoryId) {
        return Olimp::with('news')
            ->with('olimp_type')
            ->whereHas('News', function($query) use($categoryId){
                $query->where('category_id', $categoryId);
            });
    }
}
