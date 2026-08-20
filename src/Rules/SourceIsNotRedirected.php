<?php

namespace Rias\StatamicRedirect\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\Site;

class SourceIsNotRedirected implements ValidationRule
{
    public function validate(string $attribute, $value, Closure $fail): void
    {
        $site = Redirect::find(request()->route('id'))?->site() ?? Site::selected()->handle();

        $existing = Redirect::query()
            ->where('source', $value)
            ->when(request()->route('id'), fn ($query) => $query->where('id', '!=', request()->route('id')))
            ->where('site', $site)
            ->first();

        if ($existing) {
            $fail(__('This source already has a redirect associated with it.'));
        }
    }
}
