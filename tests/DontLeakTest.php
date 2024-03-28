<?php

declare(strict_types=1);

namespace Talkinnl\DontLeak\Tests;

use Talkinnl\DontLeak\DontLeak;

final class DontLeakTest extends SomeParentTestClass
{
    public string $publicProp;
    protected string $protectedProp;
    private string $privateProp;

    public function setUp(): void
    {
        /**
         * Arrange: Various properties which are assigned during your Test lifecycle,
         * possibly in some setUp(), or just in a *test instance method.
         */
        $this->publicProp = 'My own public property';
        $this->protectedProp = 'My own protected property';
        $this->privateProp = 'My own private property';

        parent::setUp();
    }

    public function testFreeOwnProperties(): void {
        self::assertStringContainsString('property', $this->publicProp);
        self::assertStringContainsString('property', $this->protectedProp);
        self::assertStringContainsString('property', $this->privateProp);

        self::assertStringContainsString('property', $this->publicParentProp);
        self::assertStringContainsString('property', $this->protectedParentProp);
        self::assertStringContainsString('property', $this->getPrivateParentProperty());

        /**
         * Act: Clean up all properties.
         *
         * Your code MUST do this call at the very end of your tearDown() methods.
         * In this file it's only in the test to make writing my own test easier. ;)
         */
        DontLeak::freeOwnProperties($this);

        /**
         * Assert: Cleaned up.
         */
        self::assertFalse(isset($this->publicProp));
        self::assertFalse(isset($this->protectedProp));
        self::assertFalse(isset($this->privateProp));

        self::assertFalse(isset($this->publicParentProp));
        self::assertFalse(isset($this->protectedParentProp));
        self::assertNull($this->getPrivateParentProperty());

        /**
         * Assert: Kept phpunit privates.
         */
        self::assertSame(__FUNCTION__, $this->name());
    }
}
