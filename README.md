# laravel-auto-subscriber

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rojtjo/laravel-auto-subscriber.svg?style=flat-square)](https://packagist.org/packages/rojtjo/laravel-auto-subscriber)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/rojtjo/laravel-auto-subscriber/Tests?label=tests)](https://github.com/rojtjo/laravel-auto-subscriber/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/rojtjo/laravel-auto-subscriber.svg?style=flat-square)](https://packagist.org/packages/rojtjo/laravel-auto-subscriber)

Automatically subscribe to all events for which your subscriber contains a handler. Under the hood we use reflection to
detect all listeners defined on the subscriber.

## Installation

You can install the package via composer:

```bash
composer require rojtjo/laravel-auto-subscriber
```

## Usage

All you have to do is use the `Rojtjo\LaravelAutoSubscriber\AutoSubscriber` trait on your subscriber class. Also make
sure to register your subscriber in the `EventServiceProvider` like you're used to.

## Example

```php
use Rojtjo\LaravelAutoSubscriber\AutoSubscriber;

final class UserNotifier
{
    use AutoSubscriber;

    public function welcomeUser(UserCreated $event): void
    {
        // Send welcome notification..
    }
}
```

This would equivalent to a handwritten subscriber like shown below.

```php
use Illuminate\Contracts\Events\Dispatcher;

final class UserNotifier
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserCreated::class, [$this, 'welcomeUser']);
    }

    // ...
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Roj Vroemen](https://github.com/rojtjo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
