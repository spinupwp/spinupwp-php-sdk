# Contributing

## Development

After cloning the repository locally, run:

```
composer install
```

## Releasing a New Version

On GitHub, [create a new release](https://github.com/deliciousbrains/spinupwp-php-sdk/releases/new). Set the tag and release title to the semantic version, but prepend the letter **v**. For example, if releasing version `1.1.1`, the tag and release title should be `v1.1.1`. Leave target set to main and add any release notes to the description field.

Hit 'Publish release' to finalize the release. This will automatically update [Packagist](https://packagist.org/packages/deliciousbrains/spinupwp-php-sdk) with the latest version.
