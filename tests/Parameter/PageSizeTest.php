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
use Tobento\Service\Pdf\Parameter\PageSize;
use Tobento\Service\Pdf\ParameterInterface;

class PageSizeTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $size = new PageSize(210.0, 297.0);

        $this->assertInstanceOf(ParameterInterface::class, $size);
        $this->assertInstanceOf(JsonSerializable::class, $size);
    }

    public function testWidthAndHeightAreReturned()
    {
        $size = new PageSize(100.5, 200.75);

        $this->assertSame(100.5, $size->width());
        $this->assertSame(200.75, $size->height());
    }

    public function testJsonSerialize()
    {
        $size = new PageSize(50.0, 80.0);

        $this->assertSame(
            [
                'width' => 50.0,
                'height' => 80.0,
            ],
            $size->jsonSerialize()
        );
    }
}