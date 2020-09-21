<?php


namespace Rias\StatamicRedirect\Contracts;

interface RedirectRepository
{
    public function all();

    public function find($id);

    public function findByUrl(string $url);

    public function make();

    public function query();

    public function save($error);

    public function delete($error);
}
