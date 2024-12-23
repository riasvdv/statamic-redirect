<?php

namespace Rias\StatamicRedirect\Blueprints;

use Closure;
use Illuminate\Support\Facades\Session;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Site;

class RedirectBlueprint extends Blueprint
{
    public function __invoke()
    {
        return Blueprint::make()->setContents([
            'sections' => [
                'main' => [
                    'fields' => [
                        [
                            'handle' => 'source',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Source',
                                'instructions' => 'Enter the URL pattern that Redirect should match. This matches against the path only e.g.: Exact Match: `/recipes/`, or RegEx Match: `.*RecipeID=(.*)`',
                                'listable' => true,
                                'validate' => ['required', 'string', function (string $attribute, $value, Closure $fail) {
                                    $selectedSite = Session::get('statamic.cp.selected-site', Site::current()->handle());

                                    $existing = Redirect::query()
                                        ->where('source', $value)
                                        ->when(request()->route('id'), fn ($query) => $query->where('id', '!=', request()->route('id')))
                                        ->where('site', $selectedSite)
                                        ->first();

                                    if ($existing) {
                                        $fail(__("This source already has a redirect associated with it."));
                                    }
                                }],
                            ],
                        ],
                        [
                            'handle' => 'destination',
                            'field' => [
                                'type' => 'text',
                                'display' => 'Destination',
                                'instructions' => 'Enter the destination URL that should be redirected to.  This can either be a fully qualified URL or a relative URL.  e.g.: Exact Match: `/new-recipes/` or RegEx Match: `/new-recipes/$1`',
                                'listable' => true,
                                'validate' => 'required_unless:type,410|nullable|string',
                            ],
                        ],
                        [
                            'handle' => 'match_type',
                            'field' => [
                                'type' => 'select',
                                'display' => 'Match type',
                                'instructions' => 'Details on RegEx matching can be found at [regexr.com](http://regexr.com/)',
                                'listable' => true,
                                'validate' => 'required|string',
                                'options' => [
                                    MatchTypeEnum::EXACT => 'Exact Match',
                                    MatchTypeEnum::REGEX => 'RegEx Match',
                                ],
                                'default' => MatchTypeEnum::EXACT,
                            ],
                        ],
                        [
                            'handle' => 'type',
                            'field' => [
                                'type' => 'select',
                                'display' => 'Redirect type',
                                'listable' => true,
                                'validate' => 'required',
                                'options' => [
                                    302 => 'Temporary (302)',
                                    301 => 'Permanent (301)',
                                    410 => 'Gone (410)',
                                ],
                                'default' => (int) config('statamic.redirect.default_redirect_type', 301),
                            ],
                        ],
                        [
                            'handle' => 'description',
                            'field' => [
                                'type' => 'textarea',
                                'display' => 'Description',
                                'instructions' => 'Extra information about this redirect. This is for internal use only.',
                                'listable' => true,
                                'validate' => 'nullable|string',
                            ],
                        ],
                    ],
                ],
                'sidebar' => [
                    'fields' => [
                        [
                            'handle' => 'enabled',
                            'field' => [
                                'type' => 'toggle',
                                'default' => true,
                                'display' => 'Enabled',
                                'listable' => true,
                                'validate' => 'required|boolean',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
