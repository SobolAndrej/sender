# Sender Layer for MessageBird API Client

Currently, SMS integration available only.

## Requirements
- PHP 7.1 or higher
- [composer](http://getcomposer.org)
- JSON extension
- Mbstring extension

## Installation

The simple following command will install `sender` into your project. It also add a new
entry in your `composer.json` and update the `composer.lock` as well.

```bash
$ composer require 'SobolAndrej/sender:dev-master'
```

or

    {
        "require": {
            "SobolAndrej/sender": "dev-master"
        }
    }

and

```bash
$ composer update 'SobolAndrej/sender:dev-master'
```

## Getting Started

```php
$sender = new \Sender\Service\Sms(API_KEY);

try {
    $message = new \Sender\Model\Sms(FROM, MESSAGE, TO);

    $result = json_encode($sender->send($message));
} catch (\Exception $e) {
    $result = json_encode(['status' => $sender::STATUS_INTERNAL_ERROR, 'message' => $e->getMessage()]);
}

echo $result;

```

See more examples in `example` folder (please, copy `config.php.dist` to `config.php` and setup your api key).


## Tests

To run unit tests enter a command `phpunit -c .` 
(be sure that you have `phpunit` global variable)

## Developer documentation

* https://developers.messagebird.com/docs
* https://github.com/messagebird/php-rest-api
