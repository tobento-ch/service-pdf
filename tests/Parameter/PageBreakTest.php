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
use Tobento\Service\Pdf\Parameter\PageBreak;
use Tobento\Service\Pdf\ParameterInterface;

class PageBreakTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $break = new PageBreak();

        $this->assertInstanceOf(ParameterInterface::class, $break);
        $this->assertInstanceOf(JsonSerializable::class, $break);
    }

    public function testJsonSerializeReturnsEmptyArray()
    {
        $break = new PageBreak();

        $this->assertSame([], $break->jsonSerialize());
    }
}