<?php

namespace Rias\StatamicRedirect\Contracts;

interface RedirectRepository
{
    public function all();

    public function find($id): ?Redirect;

    public function findByUrl(string $siteHandle, string $url): ?Redirect;

    public function make(): Redirect;

    public function query();

    public function save($error);

    public function delete($error);
}
