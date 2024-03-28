<?php

declare(strict_types=1);

namespace Talkinnl\DontLeak\Tests;

use PHPUnit\Framework\TestCase;

abstract class SomeParentTestClass extends TestCase
{
    public string $publicParentProp;
    protected string $protectedParentProp;
    private string $privateParentProp;

    public function setUp(): void
    {
        /**
         * Arrange: Various properties which are assigned during your Test lifecycle,
         * possibly in some setUp(), or just in a *test instance method.
         */
        $this->publicParentProp = 'Parent public property';
        $this->protectedParentProp = 'Parent protected property';
        $this->privateParentProp = 'Parent private property';

        parent::setUp();
    }

    protected function getPrivateParentProperty(): ?string
    {
        try {
            return $this->privateParentProp;
        } catch (\Error) {
            // Property already freed
            return null;
        }
    }
}
