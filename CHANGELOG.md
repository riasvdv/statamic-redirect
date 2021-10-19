# Release Notes

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
