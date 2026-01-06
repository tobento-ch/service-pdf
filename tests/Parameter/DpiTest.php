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
use Tobento\Service\Pdf\Parameter\Dpi;
use Tobento\Service\Pdf\ParameterInterface;

class DpiTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $dpi = new Dpi(96);

        $this->assertInstanceOf(ParameterInterface::class, $dpi);
        $this->assertInstanceOf(JsonSerializable::class, $dpi);
    }

    public function testReturnsDpi()
    {
        $dpi = new Dpi(150);

        $this->assertSame(150, $dpi->dpi());
    }

    public function testJsonSerialize()
    {
        $dpi = new Dpi(300);

        $this->assertSame(['dpi' => 300], $dpi->jsonSerialize());
    }

    public function testThrowsExceptionIfDpiTooLow()
    {
        $this->expectException(InvalidArgumentException::class);

        new Dpi(50); // below 72
    }

    public function testThrowsExceptionIfDpiTooHigh()
    {
        $this->expectException(InvalidArgumentException::class);

        new Dpi(1000); // above 600
    }

    public function testAllowsBoundaryValues()
    {
        $this->assertSame(72, (new Dpi(72))->dpi());
        $this->assertSame(600, (new Dpi(600))->dpi());
    }
}