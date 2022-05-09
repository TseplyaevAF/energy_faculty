<?php


namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class NewsFilter extends AbstractFilter
{
    public const CONTENT = 'content';
    public const CATEGORY_ID = 'category_id';
    public const IS_SLIDER_ITEM = 'is_slider_item';
    public const DATE = 'date';
    public const TAG = 'tag_id';

    protected function getCallbacks(): array
    {
        return [
            self::CONTENT => [$this, 'content'],
            self::CATEGORY_ID => [$this, 'categoryId'],
            self::IS_SLIDER_ITEM => [$this, 'isSliderItem'],
            self::DATE => [$this, 'date1'],
            self::TAG => [$this, 'tagId'],
        ];
    }

    public function content(Builder $builder, $value)
    {
        $builder->where(function($query) use($value) {
            $query->where('title', 'LIKE', "%{$value}%")
                ->orWhere('title', 'ILIKE', "%{$value}%")
                ->orWhere('content', 'LIKE', "%{$value}%")
                ->orWhere('content', 'ILIKE', "%{$value}%");
        });
    }

    public function tagId(Builder $builder, $value)
    {
        $builder->whereHas('tags', function($query) use($value) {
            $query->where('tag_id', $value);
        });
    }

    public function categoryId(Builder $builder, $value)
    {
        $builder->where('category_id', $value);
    }

    public function isSliderItem(Builder $builder)
    {
        $builder->where('is_slider_item', true);
    }

    public function date1(Builder $builder, $value)
    {
        $startDate = isset($value[0]) ? Carbon::createFromFormat('Y-m-d H:i', $value[0] . ' 00:00') : Carbon::createFromFormat('Y-m-d', '1970-01-01');
        $endDate = isset($value[1]) ? Carbon::createFromFormat('Y-m-d H:i', $value[1] . ' 00:00') : Carbon::createFromFormat('Y-m-d', date("Y-m-d"));
        $builder->whereBetween('created_at', [$startDate, $endDate])->get();
    }

}
