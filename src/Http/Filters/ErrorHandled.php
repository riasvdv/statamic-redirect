<?php

namespace Rias\StatamicRedirect\Http\Filters;

use Statamic\Query\Scopes\Filter;

class ErrorHandled extends Filter
{
    public $pinned = true;

    public static function title()
    {
        return __('Handled');
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

    public function apply($errors, $values)
    {
        return $errors->where('handled', '=', $values['status'] === 'true');
    }

    public function badge($values)
    {
        if ($values['status'] === 'true') {
            return __('Handled');
        } elseif ($values['status'] === 'false') {
            return __('Not handled');
        }
    }

    public function visibleTo($key)
    {
        return in_array($key, ['errors']);
    }

    protected function options()
    {
        return collect([
            'true' => __('Handled'),
            'false' => __('Not handled'),
        ]);
    }
}
