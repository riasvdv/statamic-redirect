<?php

namespace Rias\StatamicRedirect\Policies;

use Rias\StatamicRedirect\Data\Error;
use Statamic\Facades\User;

class ErrorPolicy
{
    public function before($user)
    {
        $user = User::fromUser($user);

        if ($user->isSuper()) {
            return true;
        }
    }

    public function view($user, Error $error)
    {
        return true;
    }

    public function create($user, Error $error)
    {
        return true;
    }

    public function edit($user, Error $error)
    {
        return true;
    }

    public function delete($user, Error $error)
    {
        return true;
    }

    public function publish($user, Error $error)
    {
        return true;
    }
}
