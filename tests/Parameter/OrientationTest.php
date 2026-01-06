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
use Tobento\Service\Pdf\Enums\Orientation as OrientationEnum;
use Tobento\Service\Pdf\Parameter\Orientation;
use Tobento\Service\Pdf\ParameterInterface;

class OrientationTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $orientation = new Orientation(OrientationEnum::PORTRAIT);

        $this->assertInstanceOf(ParameterInterface::class, $orientation);
        $this->assertInstanceOf(JsonSerializable::class, $orientation);
    }

    public function testOrientationIsReturned()
    {
        $orientation = new Orientation(OrientationEnum::LANDSCAPE);

        $this->assertSame(OrientationEnum::LANDSCAPE, $orientation->orientation());
    }

    public function testJsonSerialize()
    {
        $orientation = new Orientation(OrientationEnum::PORTRAIT);

        $this->assertSame(
            ['orientation' => OrientationEnum::PORTRAIT->value],
            $orientation->jsonSerialize()
        );
    }

    public function testFromArray()
    {
        $orientation = Orientation::fromArray(['orientation' => 'landscape']);

        $this->assertSame(OrientationEnum::LANDSCAPE, $orientation->orientation());
    }

    public function testRoundTripSerialization()
    {
        $original = new Orientation(OrientationEnum::LANDSCAPE);

        $serialized = $original->jsonSerialize();
        $restored = Orientation::fromArray($serialized);

        $this->assertSame($original->orientation(), $restored->orientation());
    }
}