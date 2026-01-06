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

use InvalidArgumentException;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Parameter\Compression;
use Tobento\Service\Pdf\ParameterInterface;

class CompressionTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $compression = new Compression(5);

        $this->assertInstanceOf(ParameterInterface::class, $compression);
        $this->assertInstanceOf(JsonSerializable::class, $compression);
    }

    public function testReturnsLevel()
    {
        $compression = new Compression(7);

        $this->assertSame(7, $compression->level());
    }

    public function testJsonSerialize()
    {
        $compression = new Compression(3);

        $this->assertSame(['level' => 3], $compression->jsonSerialize());
    }

    public function testThrowsExceptionIfLevelTooLow()
    {
        $this->expectException(InvalidArgumentException::class);

        new Compression(-1);
    }

    public function testThrowsExceptionIfLevelTooHigh()
    {
        $this->expectException(InvalidArgumentException::class);

        new Compression(10);
    }

    public function testAllowsBoundaryValues()
    {
        $this->assertSame(0, (new Compression(0))->level());
        $this->assertSame(9, (new Compression(9))->level());
    }
}