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

    protected function getCallbacks(): array
    {
        return [
            self::CONTENT => [$this, 'content'],
            self::CATEGORY_ID => [$this, 'categoryId'],
            self::IS_SLIDER_ITEM => [$this, 'isSliderItem'],
            self::DATE => [$this, 'date1'],
        ];
    }

    public function content(Builder $builder, $value)
    {
        $value = strtolower($value);
        $builder->where(function($query) use($value) {
            $query->where('title', 'like', "%{$value}%")
            ->orWhere('content', 'like', "%{$value}%");
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
        $startDate = isset($value[0]) ? Carbon::createFromFormat('d.m.Y H:i', $value[0] . ' 00:00') : Carbon::createFromFormat('d.m.Y', '01.01.1970');
        $endDate = isset($value[1]) ? Carbon::createFromFormat('d.m.Y H:i', $value[1] . ' 00:00') : Carbon::createFromFormat('d.m.Y', date("d.m.Y"));
        $builder->whereBetween('created_at', [$startDate, $endDate])->get();
    }

}
