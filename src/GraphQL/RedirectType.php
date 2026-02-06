<?php

namespace Rias\StatamicRedirect\GraphQL;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rias\StatamicRedirect\Data\Redirect;

class RedirectType extends GraphQLType
{
    const NAME = 'Redirect';

    protected $attributes = [
        'name' => self::NAME,
        'description' => 'A redirect configuration',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The unique identifier of the redirect',
                'resolve' => fn (Redirect $redirect) => $redirect->id(),
            ],
            'enabled' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Whether the redirect is enabled',
                'resolve' => fn (Redirect $redirect) => $redirect->enabled(),
            ],
            'source' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The source URL path to match',
                'resolve' => fn (Redirect $redirect) => $redirect->source(),
            ],
            'destination' => [
                'type' => Type::string(),
                'description' => 'The destination URL to redirect to',
                'resolve' => fn (Redirect $redirect) => $redirect->destination(),
            ],
            'destination_type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The type of destination (url or entry)',
                'resolve' => fn (Redirect $redirect) => $redirect->destination_type(),
            ],
            'destination_entry' => [
                'type' => Type::string(),
                'description' => 'The entry ID if destination_type is entry',
                'resolve' => fn (Redirect $redirect) => $redirect->destination_entry(),
            ],
            'type' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The HTTP redirect status code (301, 302, 410)',
                'resolve' => fn (Redirect $redirect) => $redirect->type(),
            ],
            'match_type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The match type (exact or regex)',
                'resolve' => fn (Redirect $redirect) => $redirect->matchType()->value,
            ],
            'site' => [
                'type' => Type::string(),
                'description' => 'The site handle this redirect belongs to',
                'resolve' => fn (Redirect $redirect) => $redirect->site(),
            ],
            'order' => [
                'type' => Type::int(),
                'description' => 'The priority order of the redirect',
                'resolve' => fn (Redirect $redirect) => $redirect->order(),
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'An optional description for the redirect',
                'resolve' => fn (Redirect $redirect) => $redirect->description(),
            ],
            'last_used_at' => [
                'type' => Type::string(),
                'description' => 'When the redirect was last used (ISO 8601 format)',
                'resolve' => fn (Redirect $redirect) => $redirect->lastUsedAt()?->toIso8601String(),
            ],
        ];
    }
}
