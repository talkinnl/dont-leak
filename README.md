# Don't Leak

PHPUnit keeps all Test instance in memory for the whole run.
This causes used memory to continuously increase, unless you clean up all your properties during a tearDown().

There's No Doubt you'll eventually forget this mundane task.

## Installation

```shell
composer require --dev talkinnl/dont-speak
```

## Usage

In your Test classes, add this as a very last line of your tearDown().

Maybe you'd like to always use a common parent class so you'll never forget.
```php
    protected function tearDown(): void
    {
        /** ... possibly some other cleanups ... */
        
        parent::tearDown();
        
        DontLeak::freeOwnProperties($this);
    }
```

## What does it do?

`DontLeak::freeOwnProperites($object)` unsets all properties reachable in your own scope of given $object, and all private properties of parent objects. 

Properties defined by PHPUnit WON'T be touched. 