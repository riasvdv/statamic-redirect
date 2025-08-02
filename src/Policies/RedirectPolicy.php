<?php

namespace Rias\StatamicRedirect\Policies;

use Rias\StatamicRedirect\Contracts\Redirect;
use Statamic\Facades\User;

class RedirectPolicy
{
    public function before($user)
    {
        $user = User::fromUser($user);

        if ($user->isSuper()) {
            return true;
        }
    }

    public function view($user, ?Redirect $redirect = null)
    {
        return User::fromUser($user)->hasPermission('view redirects');
    }

    public function create($user, ?Redirect $redirect = null)
    {
        return User::fromUser($user)->hasPermission('create redirects');
    }

    public function edit($user, ?Redirect $redirect = null)
    {
        return User::fromUser($user)->hasPermission('edit redirects');
    }

    public function delete($user, ?Redirect $redirect = null)
    {
        return User::fromUser($user)->hasPermission('delete redirects');
    }

    public function publish($user, ?Redirect $redirect = null)
    {
        return User::fromUser($user)->hasPermission('edit redirects');
    }
}
