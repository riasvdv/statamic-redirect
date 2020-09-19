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

];
