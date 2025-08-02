<?php

namespace Rias\StatamicRedirect\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Rias\StatamicRedirect\Facades\Redirect;

class SourceIsNotRedirected implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    public function validate(string $attribute, $value, Closure $fail): void
    {
        $existing = Redirect::query()
            ->where('source', $value)
            ->when(request()->route('id'), fn ($query) => $query->where('id', '!=', request()->route('id')))
            ->where('site', $this->data['site'][0])
            ->first();

        if ($existing) {
            $fail(__('This source already has a redirect associated with it.'));
        }
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
