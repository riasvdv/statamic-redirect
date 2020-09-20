<?php


namespace Rias\StatamicRedirect\Repositories;

use Rias\StatamicRedirect\DataTransferObjects\Redirect;
use Rias\StatamicRedirect\DataTransferObjects\RedirectCollection;

interface RedirectRepository
{
    public function save(Redirect $redirect): void;

    public function update(Redirect $redirect, array $data): Redirect;

    public function find(int $id): ?Redirect;

    public function findForUrl(string $url): ?Redirect;

    public function nextId(): int;

    public function all(): RedirectCollection;

    public function delete(Redirect $redirect): void;
}
