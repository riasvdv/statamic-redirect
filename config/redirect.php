<?php

return [

    /**
     * Controls whether Redirect automatically creates a redirect
     * when an entry's URI changes.
     */
    'create_entry_redirects' => true,

    /**
     * Should error logs be cleaned? Make sure your schedule is running.
     */
    'clean_errors' => true,

    /**
     * Error logs older than this will be deleted.
     * @link http://php.net/manual/en/dateinterval.createfromdatestring.php
     */
    'clean_older_than' => '1 month',

    /*
     * Error repository, you can change this to use a different
     * storage method for the errors. The class must implement
     * \Rias\StatamicRedirect\Contracts\ErrorRepository
     */
    'error_repository' => \Rias\StatamicRedirect\Stache\Errors\ErrorRepository::class,

    /*
     * Redirect repository, you can change this to use a different
     * storage method for the redirect. The class must implement
     * \Rias\StatamicRedirect\Contracts\RedirectRepository
     */
    'redirect_repository' => \Rias\StatamicRedirect\Stache\Redirects\RedirectRepository::class,
];
