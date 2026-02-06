<?php

namespace Rias\StatamicRedirect\GraphQL;

use GraphQL\Type\Definition\Type;
use Rias\StatamicRedirect\Facades\Redirect;
use Statamic\Facades\GraphQL;
use Statamic\GraphQL\Queries\Query;

class RedirectsQuery extends Query
{
    protected $attributes = [
        'name' => 'redirects',
        'description' => 'Query all redirects',
    ];

    public function type(): Type
    {
        return GraphQL::paginate(GraphQL::type(RedirectType::NAME));
    }

    public function args(): array
    {
        return [
            'limit' => [
                'type' => Type::int(),
                'description' => 'Number of redirects to return',
                'defaultValue' => 20,
            ],
            'page' => [
                'type' => Type::int(),
                'description' => 'Page number',
                'defaultValue' => 1,
            ],
            'site' => [
                'type' => Type::string(),
                'description' => 'Filter by site handle',
            ],
            'enabled' => [
                'type' => Type::boolean(),
                'description' => 'Filter by enabled status',
            ],
            'match_type' => [
                'type' => Type::string(),
                'description' => 'Filter by match type (exact or regex)',
            ],
            'type' => [
                'type' => Type::int(),
                'description' => 'Filter by redirect type (301, 302, 410)',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Redirect::query();

        if (isset($args['site'])) {
            $query->where('site', $args['site']);
        }

        if (isset($args['enabled'])) {
            $query->where('enabled', $args['enabled']);
        }

        if (isset($args['match_type'])) {
            $query->where('match_type', $args['match_type']);
        }

        if (isset($args['type'])) {
            $query->where('type', $args['type']);
        }

        $query->orderBy('order', 'asc');

        $limit = $args['limit'] ?? 20;
        $page = $args['page'] ?? 1;

        return $query->paginate($limit, ['*'], 'page', $page);
    }
}
