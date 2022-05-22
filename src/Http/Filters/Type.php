<?php

namespace Rias\StatamicRedirect\Http\Filters;

use Statamic\Query\Scopes\Filter;

class Type extends Filter
{
    public static function title()
    {
        return __('Type');
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
        return $query->where('type', '=', $values['status']);
    }

    public function visibleTo($key)
    {
        return $key === 'redirects';
    }

    protected function options()
    {
        return collect([
            301 => '301',
            302 => '302',
            410 => '410',
        ]);
    }
}
