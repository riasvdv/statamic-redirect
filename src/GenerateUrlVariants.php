<?php

namespace Rias\StatamicRedirect;

class GenerateUrlVariants
{
    public function __invoke(string $url)
    {
        $variants = [
            $url,
            str($url)->start('/'),
            str($url)->finish('/'),
            str($url)->start('/')->finish('/'),
            str_starts_with($url, '/') ? str($url)->substr(1) : null,
        ];

        if (str_contains($url, '?')) {
            $url = str($url)->before('?')->value();

            $variants = array_merge($variants, [
                $url,
                str($url)->start('/'),
                str($url)->finish('/'),
                str($url)->start('/')->finish('/'),
                str_starts_with($url, '/') ? str($url)->substr(1) : null,
            ]);
        }

        return array_unique(array_filter($variants));
    }
}
