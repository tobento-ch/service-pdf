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
use Tobento\Service\Pdf\Parameter\Name;
use Tobento\Service\Pdf\ParameterInterface;

class NameTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $param = new Name('invoice');

        $this->assertInstanceOf(ParameterInterface::class, $param);
        $this->assertInstanceOf(JsonSerializable::class, $param);
    }

    public function testReturnsName()
    {
        $param = new Name('report');

        $this->assertSame('report', $param->name());
    }

    public function testJsonSerialize()
    {
        $param = new Name('contract');

        $this->assertSame(['name' => 'contract'], $param->jsonSerialize());
    }

    public function testAllowsEmptyString()
    {
        $param = new Name('');

        $this->assertSame('', $param->name());
        $this->assertSame(['name' => ''], $param->jsonSerialize());
    }
}