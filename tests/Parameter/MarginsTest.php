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
use Tobento\Service\Pdf\Parameter\Margins;
use Tobento\Service\Pdf\ParameterInterface;

class MarginsTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $margins = new Margins();

        $this->assertInstanceOf(ParameterInterface::class, $margins);
        $this->assertInstanceOf(JsonSerializable::class, $margins);
    }

    public function testValuesAreReturned()
    {
        $margins = new Margins(
            top: '10mm',
            right: 20,
            bottom: '30mm',
            left: 40
        );

        $this->assertSame('10mm', $margins->top());
        $this->assertSame(20, $margins->right());
        $this->assertSame('30mm', $margins->bottom());
        $this->assertSame(40, $margins->left());
    }

    public function testNullValuesAreReturned()
    {
        $margins = new Margins();

        $this->assertNull($margins->top());
        $this->assertNull($margins->right());
        $this->assertNull($margins->bottom());
        $this->assertNull($margins->left());
    }

    public function testJsonSerialize()
    {
        $margins = new Margins(
            top: 1,
            right: '2mm',
            bottom: 3,
            left: '4mm'
        );

        $this->assertSame(
            [
                'top' => 1,
                'right' => '2mm',
                'bottom' => 3,
                'left' => '4mm',
            ],
            $margins->jsonSerialize()
        );
    }
}