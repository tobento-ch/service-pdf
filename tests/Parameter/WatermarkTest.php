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
use Tobento\Service\Pdf\Parameter\Watermark;
use Tobento\Service\Pdf\ParameterInterface;

class WatermarkTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $wm = new Watermark('CONFIDENTIAL');

        $this->assertInstanceOf(ParameterInterface::class, $wm);
        $this->assertInstanceOf(JsonSerializable::class, $wm);
    }

    public function testTextIsReturned()
    {
        $wm = new Watermark('TOP SECRET');

        $this->assertSame('TOP SECRET', $wm->text());
    }

    public function testJsonSerialize()
    {
        $wm = new Watermark('DRAFT');

        $this->assertSame(
            ['text' => 'DRAFT'],
            $wm->jsonSerialize()
        );
    }
}