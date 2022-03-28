<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\NewsFilter;
use App\Http\Requests\News\FilterRequest;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsSliderResource;
use App\Models\News\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(FilterRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(NewsFilter::class, ['queryParams' => array_filter($data)]);
        if (isset($data['is_slider_item'])) {
            return NewsSliderResource::collection(News::filter($filter)->orderBy('updated_at', 'desc')->get());
        }
        return NewsResource::collection(News::filter($filter)->orderBy('updated_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return NewsResource
     */
    public function show($id)
    {
        return new NewsResource(News::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
