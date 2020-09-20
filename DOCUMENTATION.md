## Installation

1. Install Statamic Redirect from the `Tools > Addons` section of your control panel, or via composer:

    ```
    composer require rias/statamic-redirect
    ```

## Usage

Redirect allows you to redirect legacy URLs, so that you don't lose SEO value when rebuilding & restructuring a website.

![Screenshot](https://raw.githubusercontent.com/riasvdv/statamic-redirect/master/docs/img/redirect.png)

Redirect supports traditional exact and RegEx matching of URL patterns.

Redirect will also automatically create a redirect for you if you change an entry's slug or uri.

Unlike utilizing .htaccess to do redirects, Redirect does not add overhead to each request for each redirect you have in place.

Redirect has no impact on your website's performance until a 404 exception happens.

## Configuration

You can publish the config file using

```
php artisan vendor:publish --tag=statamic-redirect-config
```

### Create entry redirects

By default, Statamic Redirect will create redirects when the URI of an entry changes. You can disable this by setting the `create_entry_redirects` config value to `false`.

### Cleaning errors

The amount of errors can grow quickly, Statamic Redirect includes a scheduled command that by default cleans up errors older than 1 month.

You can disable the cleaning by setting the `clean_errors` config value to `false`. Or change the date range by changing the `clean_older_than` config value. This accepts a value that is parsed by PHP's [createfromdatestring](http://php.net/manual/en/dateinterval.createfromdatestring.php) function.

> Make sure your [Schedule](https://laravel.com/docs/8.x/scheduling#introduction) is running for error cleaning to work. 

### Different storage

If you want to use a different storage method for the Errors or Redirects, you can change them in the config file.

These should implement `\Rias\StatamicRedirect\Repositories\ErrorRepository` and `\Rias\StatamicRedirect\Repositories\RedirectRepository` respectively.

In the future this addon will come with some extra Repositories by default, without a need to code your own.
