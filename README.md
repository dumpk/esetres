# Esetres

[![Author](https://img.shields.io/badge/author-%40nicolasbistolfi-blue.svg)](https://twitter.com/nicolasbistolfi)
[![Build Status](https://travis-ci.org/dumpk/esetres.svg?branch=master)](https://travis-ci.org/dumpk/esetres)
[![Packagist](https://img.shields.io/packagist/l/Dumpk/Esetres.svg)](https://packagist.org/packages/Dumpk/Esetres)

Laravel AWS S3 Service Integration to upload files.

## Documentation

### Installation

Require this package with composer:

```
composer require dumpk/esetres
```

Add this variables to your .env with your Amazon credentials

```
AWS_ACCESS_KEY_ID={AMAZONACCESSKEY}
AWS_SECRET_ACCESS_KEY={SECRETAMAZONSOMETHINGLONG}
AWS_REGION={YOURREGION}
```


### Usage

Add the EsetresAWS class to your class header
```php
use Dumpk\Esetres\EsetresAWS;
```
The class is a singleton so you only need to call the static methods.

To upload a file:
```php
EsetresAWS::uploadFile($filepath, $key, $bucket);
```

To delete a file:
```php
EsetresAWS::deleteFile($key, $bucket);
```

To make a file accesible to everyone:
```php
EsetresAWS::makeFilePublic($key, $bucket);
```

To check for a file existance:
```php
EsetresAWS::fileExists($key, $bucket);
```

If you want to get all the file information:
```php
EsetresAWS::getObject($key, $bucket);
```
Optionally you can pass to the getObject method, a filepath to store the file on your server.
```php
EsetresAWS::getObject($key, $bucket, "/var/www/filepath.txt");
```

## Support

The following support channels can be used for contact.

- [Twitter](https://twitter.com/nicolasbistolfi)
- [Email](mailto:nbistolfi@gmail.com)

Bug reports, feature requests, and pull requests can be submitted following our [Contribution Guide](CONTRIBUTING.md).

## Contributing & Protocols

- [Versioning](CONTRIBUTING.md#versioning)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Pull Requests](CONTRIBUTING.md#pull-requests)

## Roadmap

Will look at future enhancements down the road.

## License

This software is released under the [MIT](LICENSE.md) License.

&copy; 2015 Nicolás Bistolfi (nbistolfi@gmail.com), All rights reserved.
