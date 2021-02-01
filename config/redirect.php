<?php

return [

    /**
     * Controls whether Redirect automatically creates a redirect
     * when an entry's URI changes.
     */
    'create_entry_redirects' => true,

    /**
     * Controls whether Redirect logs 404 errors
     */
    'log_errors' => true,

    /**
     * Should error logs be cleaned? Make sure your schedule is running.
     */
    'clean_errors' => true,

    /**
     * Should error logs be cleaned when saving a new error?
     */
    'clean_errors_on_save' => true,

    /**
     * Error logs older than this will be deleted.
     * @link http://php.net/manual/en/dateinterval.createfromdatestring.php
     */
    'clean_older_than' => '1 month',

    /*
     * The maximum number of unique errors to keep.
     * This does not include individual hits.
     */
    'keep_unique_errors' => 1000,

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
