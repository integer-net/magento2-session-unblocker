# Integer_Net Session Unblocker

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]


This extension improves the performance of AJAX requests in Magento by reducing session locking, without having to disable locking in the cache backend (e.g. Redis)

Session locking keeps safe from race conditions from parallel requests, so disabling it completely can result in session data loss.

Instead we minimize the lock time by closing the session as soon as we have read it, if we do not need to write to it anymore.

## Installation

1. Install via composer
    ```
    composer require integer-net/magento2-session-unblocker
    ```
2. Enable module
    ```
    bin/magento setup:upgrade
    ```
## Configuration

Zero configuration needed.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

### Magento Integration Tests

0. Configure test database in `dev/tests/integration/etc/install-config-mysql.php`. [Read more in the Magento docs.](https://devdocs.magento.com/guides/v2.3/test/integration/integration_test_execution.html) 

1. Copy `Test/Integration/phpunit.xml.dist` from the package to `dev/tests/integration/phpunit.xml` in your Magento installation.

2. In that directory, run
    ``` bash
    ../../../vendor/bin/phpunit
    ```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email fs@integer-net.de instead of using the issue tracker.

## Credits

- [Willem Wigman][link-author]
- [Fabian Schmengler][link-author2]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.txt) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/integer-net/magento2-session-unblocker/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/integer-net/magento2-session-unblocker.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/integer-net/magento2-session-unblocker.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/integer-net/magento2-session-unblocker
[link-travis]: https://travis-ci.org/integer-net/magento2-session-unblocker
[link-scrutinizer]: https://scrutinizer-ci.com/g/integer-net/magento2-session-unblocker/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/integer-net/magento2-session-unblocker
[link-author]: https://github.com/wigman
[link-author2]: https://github.com/schmengler
[link-contributors]: ../../contributors