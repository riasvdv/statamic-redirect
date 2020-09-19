![Icon](icon.svg)

[![Latest Version](https://img.shields.io/github/release/riasvdv/statamic-redirect.svg?style=flat-square)](https://github.com/riasvdv/statamic-redirect/releases)

# Redirect plugin for Statamic

Redirect allows you to redirect legacy URLs, so that you don't lose SEO value when rebuilding & restructuring a website.

![Screenshot](docs/img/redirect.png)

## License

Redirect requires a license to be used while on a production site.  
You can purchase one at https://statamic.com/marketplace/addons/redirect.

You may use Redirect without a license while developing locally.

## Installation

Require it using Composer.

```
composer require rias/statamic-redirect
```

Publish the assets:

```
php artisan vendor:publish --provider="Rias\StatamicRedirect\RedirectServiceProvider"
```

Brought to you by [Rias](https://rias.be)
