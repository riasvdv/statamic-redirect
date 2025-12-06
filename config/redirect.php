<?php

return [
    /**
     * Whether Redirect should be enabled.
     */
    'enable' => env('REDIRECT_ENABLED', true),

    /**
     * The model to use for redirects
     */
    'model' => \Rias\StatamicRedirect\Eloquent\Redirects\RedirectModel::class,

    /**
     * Whether Redirect should automatically run database migrations or not
     */
    'run_migrations' => env('REDIRECT_RUN_MIGRATIONS', true),

    /**
     * Whether Redirect should preserve query strings.
     */
    'preserve_query_strings' => env('REDIRECT_PRESERVE_QUERY_STRINGS', false),

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
     * Controls whether Redirect logs 404 errors.
     */
    'log_errors' => env('REDIRECT_LOG_ERRORS', true),

    /**
     * Controls whether Redirect logs individual hits.
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

    /**
     * The maximum number of unique errors to keep.
     * This does not include individual hits.
     */
    'keep_unique_errors' => 1000,

    /**
     * The database connection used to store errors. By default,
     * this is the included 'redirect-sqlite'. Use
     * 'default' to use the Laravel default.
     */
    'error_connection' => env('REDIRECT_ERROR_CONNECTION', 'redirect-sqlite'),

    /**
     * The database connection used to store redirects. By
     * default, this is the included 'stache' connection.
     * Use 'default' to use the Laravel default.
     */
    'redirect_connection' => env('REDIRECT_REDIRECT_CONNECTION', 'stache'),

    /**
     * Customize where on filesystem the redirects are being stored in stache.
     * Useful when using a non-conventional setup where data should
     * not be inside the usual content/redirects folder.
     */
    'redirect_store' => base_path('content/redirects'),

    /**
     * Customize the redirect repository you want to use for data
     * storage. Provide null to automaticly use a repository
     * based on the 'redirect_connection'.
     */
    'redirect_repository' => null,

    /**
     * Set the default redirect type for new redirects.
     * Can be one of: 301, 302, 410.
     */
    'default_redirect_type' => env('REDIRECT_DEFAULT_REDIRECT_TYPE', 302),
];
