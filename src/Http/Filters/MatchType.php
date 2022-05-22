<?php

namespace Rias\StatamicRedirect\Http\Filters;

use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Statamic\Query\Scopes\Filter;

class MatchType extends Filter
{
    public static function title()
    {
        return __('Match type');
    }

    public function fieldItems()
    {
        return [
            'status' => [
                'type' => 'radio',
                'options' => $this->options()->all(),
            ],
        ];
    }

    public function apply($query, $values)
    {
        return $query->where('matchType', '=', $values['status']);
    }

    public function visibleTo($key)
    {
        return $key === 'redirects';
    }

    protected function options()
    {
        return collect([
            MatchTypeEnum::EXACT => __('Exact'),
            MatchTypeEnum::REGEX => __('Regex'),
        ]);
    }
}
