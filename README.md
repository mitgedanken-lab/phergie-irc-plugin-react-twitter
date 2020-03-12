# This project is abandoned

This repo is being kept for posterity and will be archived in a readonly state. 
If you're interested it can be forked under a new Composer namespace/GitHub organization.

# phergie/phergie-irc-plugin-react-twitter

[Phergie](http://github.com/phergie/phergie-irc-bot-react/) plugin for displaying data from tweets.

[![Build Status](https://secure.travis-ci.org/phergie/phergie-irc-plugin-react-twitter.png?branch=master)](http://travis-ci.org/phergie/phergie-irc-plugin-react-twitter)

## Install

The recommended method of installation is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "phergie/phergie-irc-plugin-react-twitter": "dev-master"
    }
}
```

See Phergie documentation for more information on
[installing and enabling plugins](https://github.com/phergie/phergie-irc-bot-react/wiki/Usage#plugins).

## Configuration

```php
return [
    'plugins' => [
        // dependencies
        new \WyriHaximus\Phergie\Plugin\Url\Plugin, // Emits url.host.twitter.com events

        // configuration
        new \Phergie\Irc\Plugin\React\Twitter\Plugin([
            // required string containing OAuth consumer key
            'consumer_key' => 'xvz1evFS4wEEPTGEFPHBog'

            // required string containing OAuth consumer secret
            'consumer_secret' => '9z6157pUbOBqtbm0A0q4r29Y2EYzIHlUwbF4Cl9c'

            // required string containing OAuth token
            'token' => '370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb'

            // required string containing OAuth token secret
            'token_secret' => '9z6157pUbOBqtbm0A0q4r29Y2EYzIHlUwbF4Cl9c'

            // optional object implementing \Phergie\Irc\Plugin\React\Twitter\FormatterInterface
            // used to format tweets prior to their syndication
            'formatter' => new \Phergie\Irc\Plugin\React\Twitter\DefaultFormatter('<@%user.screen_name%> %text% - %created_at.relative% (%url%)', \DateTime::ISO8601)
        ])
    ]
];
```

## Usage

* `twitter username` - fetches and displays the last tweet by @username
* `twitter username 3` - fetches and displays the third last tweet by @username
* `twitter 1234567` - fetches and displays tweet number 1234567
* `http://twitter.com/username/statuses/1234567` - Url plugin routes to this plugin, same output as `twitter 1234567`

## Tests

To run the unit test suite:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
./vendor/bin/phpunit
```

## License

Released under the BSD License. See `LICENSE`.
