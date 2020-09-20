<?php


namespace Rias\StatamicRedirect\Repositories;

use Rias\StatamicRedirect\DataTransferObjects\Error;
use Rias\StatamicRedirect\DataTransferObjects\ErrorCollection;

interface ErrorRepository
{
    public function save(Error $error): void;

    public function find(int $id): ?Error;

    public function nextId(): int;

    public function all(): ErrorCollection;

    public function delete(Error $error): void;
}
