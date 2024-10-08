# Don't Leak

[![PHP Composer and unittest](https://github.com/talkinnl/dont-leak/actions/workflows/php.yml/badge.svg)](https://github.com/talkinnl/dont-leak/actions/workflows/php.yml)

----

## ARCHIVED, NO LONGER NEEDED.

This package is no longer needed, since PHPUnit now destructs the TestCase instances during the run.

- Initial PR by Sebastian :fire: , [PR 5861](https://github.com/sebastianbergmann/phpunit/pull/5861), released in PHPUnit [10.5.21 / 11.2.2](https://github.com/sebastianbergmann/phpunit/releases/tag/10.5.21).
- Final PR by me :sunglasses: , [PR 5875](https://github.com/sebastianbergmann/phpunit/pull/5875#issuecomment-2179933860), released in PHPUnit [10.5.23 / 11.2.4](https://github.com/sebastianbergmann/phpunit/releases/tag/10.5.23)

----

PHPUnit keeps all Test instance in memory for the whole run.
This causes used memory to continuously increase, unless you clean up all your properties during a tearDown().

There's No Doubt you'll eventually forget this tedious task.

## Installation

```shell
composer require --dev talkinnl/dont-leak
```

## Usage

In your Test classes, add this as a **very last line** of your tearDown().

Maybe you'd like to always use a common parent class so you'll never forget.
```php
    protected function tearDown(): void
    {
        // Start with some of your cleanups which are still needed.
        // Maybe removing files made during the test etc
        
        // Always call your parents; they might get worried. :)
        parent::tearDown();
        
        // Unset any properties, prevent memory leaks.
        DontLeak::freeOwnProperties($this);
    }
```

## What does it do?

`DontLeak::freeOwnProperites($object)` unsets all properties reachable in your own scope of given $object, and all private properties of parent objects. 

Properties defined by PHPUnit WON'T be touched.

## What's next

I'd really like it if this package would be become obsolete thanks to PHPUnit changing the behaviour.

Please read, and maybe vote --> 
https://github.com/sebastianbergmann/phpunit/issues/4705

## Credits

Many suggestions of a tearDown which uses reflection to clean up exist scattered across Stack Overflow, Reddit, Github, etc.

The implementation was heavily inspired by https://gist.github.com/malarzm/e8c6141b510708e52c8535d2a13cd613 , which unsets instead of assigning null, and also clears private properties of safe parents.
