<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Pdf\Test\Parameter;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Parameter\Password;
use Tobento\Service\Pdf\ParameterInterface;

class PasswordTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $password = new Password('secret');

        $this->assertInstanceOf(ParameterInterface::class, $password);
        $this->assertInstanceOf(JsonSerializable::class, $password);
    }

    public function testPasswordIsReturned()
    {
        $password = new Password('topsecret');

        $this->assertSame('topsecret', $password->password());
    }

    public function testJsonSerialize()
    {
        $password = new Password('mypassword');

        $this->assertSame(
            ['password' => 'mypassword'],
            $password->jsonSerialize()
        );
    }
}