<?php

namespace Rias\StatamicRedirect\GraphQL;

use GraphQL\Type\Definition\Type;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\GraphQL;
use Statamic\GraphQL\Queries\Query;

class RedirectQuery extends Query
{
    protected $attributes = [
        'name' => 'redirect',
        'description' => 'Query a single redirect by ID',
    ];

    public function type(): Type
    {
        return GraphQL::type(RedirectType::NAME);
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The redirect ID',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Redirect::find($args['id']);
    }
}
