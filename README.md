# Laravel DB Schema Manager 📊🔧

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bidhan/laravel-db-manager.svg?style=flat-square)](https://packagist.org/packages/bidhan/laravel-db-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/bidhan/laravel-db-manager.svg?style=flat-square)](https://packagist.org/packages/bidhan/laravel-db-manager)
[![License](https://img.shields.io/packagist/l/bidhan/laravel-db-manager.svg?style=flat-square)](https://packagist.org/packages/bidhan/laravel-db-manager)

Advance Laravel Database Manager for managing your database schemas with ease. 🚀

## Installation 📦

You can install the package via Composer:

```bash
composer require bidhan/laravel-db-manager
```

To copy the command, click on the code block above, select the text, and copy it.

## Usage 🛠️

### Service Provider

The package will automatically register the service provider. If you need to register it manually, add the service provider to the `providers` array in `config/app.php`:

```php
'providers' => [
    // Other Service Providers

    Bidhan\Bhadhan\BidhanDBManagerServiceProvider::class,
],
```

### Configuration

You can publish the configuration file with:

```bash
php artisan vendor:publish --provider="Bidhan\Bhadhan\BidhanDBManagerServiceProvider" --tag="config"
```

### Example

Here is an example of how to use the package:

```php
GOTO bhadhan/dashboard URI To Preview Dashboard
```

### Advanced Usage

#### Get All Database Tables
```php
use Bidhan\Bhadhan\Services\BhadhanDBManagerService\BhadhanDBManagerService;

$allTables = BhadhanDBManagerService::getAllDbTables();
```

#### Get Primary Key of a Table
```php
use Bidhan\Bhadhan\Services\BhadhanDBManagerService\BhadhanDBManagerService;

$primaryKey = BhadhanDBManagerService::getPrimaryKey('tableName');
```

#### Get Foreign Keys
```php
use Bidhan\Bhadhan\Services\BhadhanDBManagerService\BhadhanDBManagerService;

$foreignKeys = BhadhanDBManagerService::getForeignKeys();
```

#### Get All Tables with Size
```php
use Bidhan\Bhadhan\Services\BhadhanDBManagerService\BhadhanDBManagerService;

$tablesWithSize = BhadhanDBManagerService::getAllTableWithSize();
```

#### Get Current Schema Size
```php
use Bidhan\Bhadhan\Services\BhadhanDBManagerService\BhadhanDBManagerService;

$schemaSize = BhadhanDBManagerService::getCurrentSchemaSize();
```

#### Get All Database Views
```php
use Bidhan\Bhadhan\Services\BhadhanDBManagerService\BhadhanDBManagerService;

$allViews = BhadhanDBManagerService::getAllDBViews();
```

## Features ✨

- Create and manage database schemas effortlessly.
- Simple and intuitive API.
- Supports all major Laravel database features.

## License 📝

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Contributors ✨

Thanks to the following people who have contributed to this project:

* [Bidhan Baniya](https://github.com/bidhan948) - Creator

## Issues and Contributions 🐛

If you encounter any issues, feel free to open an issue on GitHub. Contributions are welcome and appreciated! 🎉

## Support 🙌

If you like this package, consider giving it a star ⭐ on GitHub and sharing it with your friends!

---

Made with ❤️ by [Bidhan Baniya](https://github.com/bidhan948)