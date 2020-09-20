# Release Notes

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
