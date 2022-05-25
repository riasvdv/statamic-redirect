# Release Notes

## 2.3.4 (2022-05-25)

## What's Changed
* Update dependency prettier to v2.6.2 by @renovate in https://github.com/riasvdv/statamic-redirect/pull/70
* FIX The redirect is automatically deleted for Drafts by @OleksiiBrylin in https://github.com/riasvdv/statamic-redirect/pull/74

## New Contributors
* @OleksiiBrylin made their first contribution in https://github.com/riasvdv/statamic-redirect/pull/74

**Full Changelog**: https://github.com/riasvdv/statamic-redirect/compare/2.3.3...2.3.4

## 2.3.3 (2022-05-23)

- Automatically add a .gitignore in the storage/redirect folder the first time we create the database

## 2.3.2 (2022-05-23)

- Fix selected site not saving correctly to redirect
- A Redirect's site can be switched by switching the site at the top of the page when updating a Redirect

## 2.3.1 (2022-05-23)

- Fix exception on entry saved

## 2.3.0 (2022-05-22)

- Add multisite support

## 2.2.0 (2022-05-09)

- Adds support for Statamic 3.2 again

## 2.0.0 (2022-01-21)
## BREAKING
- The config file has been restructured make sure to publish it again using:

```shell
php artisan vendor:publish --tag=statamic-redirect-config --force
```

- You can no longer choose between Eloquent and Stache for storing Redirects, these are considered content so are currently always Stache.
- You can no longer choose to store errors & hits inside the Stache, there's just no way to keep this performant.

## What's new
- Errors & hits are now stored inside a local sqlite database which improves performance and allows thousands of errors & hits to be stored without any performance issues.
- We only show the warning that the schedule hasn't run on non-local environments
- The stats graphs have been improved a bit

## 1.10.3 (2022-01-16)
## What's fixed
- Fix an issue caused by Statamic 3.2.31

## 1.10.2 (2022-01-13)
## What's fixed
- Fix the update script
- Handle trailing slashes correctly

## 1.10.1 (2022-01-13)
## What's fixed
- Fixed a visual bug when having really long urls on the redirects page

## 1.10.0 (2022-01-13)

**Updating to this version will clear your errors**

## What's new
- Added a warning when the cleanup command hasn't run for more than a day (it should be running every day)
- Restructured how hits are stored, this should improve performance.
- Added a detail view for an error that displays the hits
- Added a "Clear all" button for errors
- Added a "Delete error" button on the detail view of an error

## What's fixed
- Fixed the import failing sometimes with a file not found exception

## 1.9.0 (2021-11-27)
## What's new
- Added a `enable` config value to enable/disable the addon.
- Turning off `log_hits` will still keep a log of the number of hits. Also fixed a dashboard issue when this config was turned off.

## 1.8.0 (2021-10-30)
## What's new
- Statamic Redirect now ships with 2 Eloquent providers for the Errors & Redirects

## 1.7.2 (2021-10-27)
## What's fixed
- Fixed an isssue when trying to delete redirects on a paginated listing.

## 1.7.1 (2021-10-25)
## What's fixed
- Fix wrong default paths

## 1.7.0 (2021-10-19)
## What's new
- Allows to customize the store paths (#27)

## 1.6.1 (2021-09-20)
## What's fixed
- Fix route cache issue with duplicate name

## 1.6.0 (2021-09-18)
## What's new
- Import functionality! You can now import redirects from a CSV file.
- We now require at least PHP 7.4.

## 1.5.0 (2021-09-18)
## What's new
- Errors are now stored in folders according to their id, similar to how Laravel stores its cache files. This should improve performance.
- You can now disable logging individual hits on each error.

### What's Fixed
- The listing now breaks up long urls.
- Corrupted yaml files won't break the functionality anymore. The dashboard & clean command will keep working.
- An error's hits can now be 0.
- Fix the handled destination not being shown in the error listing.

## 1.4.7 (2021-08-11)
### What's Fixed
- Fix an issue with deleting redirects

## 1.4.6 (2021-05-06)
### What's Fixed
- Fix 410 status when a redirect is cached

## 1.4.5 (2021-05-03)
### What's Fixed
- Actually fix handling of the 410 Gone status

## 1.4.4 (2021-05-03)
### What's Fixed
- Fix handling of the 410 Gone status

## 1.4.3 (2021-03-10)
### What's Fixed
- Fixed an issue where query parameters were ignored

## 1.4.2 (2021-02-17)
### What's Fixed
- Fix pagination on redirects listing

## 1.4.1 (2021-02-17)
### What's Fixed
- Account for trailing slashes in redirects

## 1.4.0 (2021-02-01)

## What's new
- You can now disable logging of errors by setting the `log_errors` config value to `false`

## 1.3.2 (2021-01-21)

## What's fixed
- Fix composer dependencies

## 1.3.1 (2020-10-20)

## What's fixed
- Fix exception when an error has no hits

## 1.3.0 (2020-10-16)

### What's new
- Added better Error cleaning, with 2 new config options: `clean_errors_on_save` and `keep_unique_errors` which will clean errors when a new one is added and only keep a set amount of unique errors, deleting the oldest ones first. If you have configured a queue the cleaning will take place on the queue.
- The `php please redirect:clean-errors` command now also takes into account the unique errors count.

## 1.2.0 (2020-09-29)

### What's new
- Add permissions for viewing, creating, editing and deleting redirects
- Add metadata (user agent, referer & ip) when hovering over the error path
- Add redirects export to JSON or CSV

## 1.1.1 (2020-09-28)

### What's fixed
- Fix incorrect typehint in RedirectController

## 1.1.0 (2020-09-21)

### What's new
- Filters and search for the Error and Redirect listings!

### What's changed
- Errors and Redirects now use the Stache, which makes loading & querying faster.

## 1.0.3 (2020-09-20)

### What's fixed
- Automatic redirects weren't working properly

## 1.0.2 (2020-09-20)

### What's fixed
- The config file had the wrong tag, it can now be published using the `statamic-redirect-config` tag.

## 1.0.1 (2020-09-20)

### What's new
- Introduced caching for redirects, consequent 404 hits will not trigger a new lookup into the Redirect storage
- Refactored the code so we can add Eloquent database storage in the future for errors & redirects

## 3.0.0 (2020-09-19)

### What's new
- This addon! 🎉
