<?php

namespace Rias\StatamicRedirect\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Rias\StatamicRedirect\Enums\MatchTypeEnum;
use Statamic\Facades\File;
use Statamic\Facades\Folder;
use Statamic\Facades\YAML;
use Statamic\Support\Str;

class Redirect extends Record
{
    /** @var string */
    public $slug;

    /** @var bool */
    public $enabled = true;

    /** @var string */
    public $source;

    /** @var string */
    public $destination;

    /** @var string */
    public $type;

    /** @var string */
    public $match_type = MatchTypeEnum::EXACT;

    protected function __construct(array $attributes)
    {
        $this->slug = Str::slug($attributes['source']);
        $this->enabled = $attributes['enabled'] ?? true;
        $this->source = $attributes['source'];
        $this->destination = $attributes['destination'];
        $this->type = $attributes['type'] ?? '301';
        $this->match_type = $attributes['match_type'] ?? MatchTypeEnum::EXACT;
    }

    public static function create(array $attributes): self
    {
        return static::make($attributes)->save();
    }

    public static function make(array $attributes)
    {
        return new static($attributes);
    }

    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'enabled' => $this->enabled,
            'source' => $this->source,
            'destination' => $this->destination,
            'type' => $this->type,
            'match_type' => $this->match_type,
        ];
    }

    public static function all(): Collection
    {
        $files = collect(Folder::getFiles(static::preparePath(), true));

        if ($files->isEmpty()) {
            return $files;
        }

        return $files
            ->map(function ($path) {
                return static::make(YAML::parse(File::get($path)));
            })
            ->filter()
            ->values();
    }

    public static function findForUrl(string $url): ?self
    {
        return Redirect::all()
            ->where('enabled', true)
            ->map(function (Redirect $redirect) use ($url) {
                if ($redirect->match_type === MatchTypeEnum::REGEX) {
                    $source = str_replace('/', "\/", $redirect->source);
                    $matchRegEx = '`'.$source.'`i';

                    if (preg_match($matchRegEx, $url) === 1) {
                        $redirect->destination = preg_replace(
                            $matchRegEx,
                            $redirect->destination,
                            $url
                        );

                        return $redirect;
                    }
                }

                if (strcasecmp(Str::start($redirect->source, '/'), Str::start($url, '/')) === 0) {
                    return $redirect;
                }

                return null;
            })
            ->filter()
            ->first();
    }

    public function path(): string
    {
        return base_path("content/redirects/{$this->slug}.yaml");
    }

    public static function preparePath(): string
    {
        $path = collect([base_path('content/redirects')])->filter()->implode('/');

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        return $path;
    }
}
