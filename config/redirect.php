<?php

return [
    /**
     * Whether Redirect should be enabled
     */
    'enable' => env('REDIRECT_ENABLED', true),

    /**
     * Controls whether Redirect automatically creates a redirect
     * when an entry's URI changes.
     */
    'create_entry_redirects' => env('REDIRECT_AUTO_CREATE', true),

    /**
     * Controls whether Redirect automatically deletes conflicting
     * redirects when an entry is saved.
     */
    'delete_conflicting_redirects' => env('REDIRECT_DELETE_CONFLICTS', true),

    /**
     * Controls whether Redirect logs 404 errors
     */
    'log_errors' => env('REDIRECT_LOG_ERRORS', true),

    /**
     * Controls whether Redirect logs individual hits
     */
    'log_hits' => env('REDIRECT_LOG_HITS', true),

    /**
     * Should error logs be cleaned? Make sure your schedule is running.
     */
    'clean_errors' => env('REDIRECT_CLEAN_ERRORS', true),

    /**
     * Should error logs be cleaned when saving a new error?
     */
    'clean_errors_on_save' => env('REDIRECT_CLEAN_ON_SAVE', true),

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
     * The database connection redirect should use to store errors. By
     * default this is the included 'redirect-sqlite' connection
     * that uses an sqlite database in storage.
     */
    'error_connection' => 'redirect-sqlite',

    /*
     * The database connection redirect should use to store redirects
     * by default this is the included 'stache' connection
     * that stores the redirects in yaml files inside
     * the folder defined by 'redirect_store'.
     */
    'redirect_connection' => 'stache',

    /*
     * Customize where on filesystem the redirects are being stored in stache.
     * Useful when using a non-conventional setup where data should
     * not be inside the usual content/redirects folder
     */
    'redirect_store' => base_path('content/redirects'),

    /*
     * Customise the redirect repository you want to use for data
     * storage. Provide null to automaticly use a repository
     * based on the 'redirect_connection'.
     */
    'redirect_repository' => null,
];
