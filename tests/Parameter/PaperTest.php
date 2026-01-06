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
use Tobento\Service\Pdf\Enums\Paper as EnumPaper;
use Tobento\Service\Pdf\Parameter\Paper;
use Tobento\Service\Pdf\ParameterInterface;

class PaperTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $paper = new Paper(EnumPaper::A4);

        $this->assertInstanceOf(ParameterInterface::class, $paper);
        $this->assertInstanceOf(JsonSerializable::class, $paper);
    }

    public function testPaperIsReturned()
    {
        $paper = new Paper(EnumPaper::LETTER);

        $this->assertSame(EnumPaper::LETTER, $paper->paper());
    }

    public function testJsonSerialize()
    {
        $paper = new Paper(EnumPaper::A3);

        $this->assertSame(
            ['paper' => EnumPaper::A3->value],
            $paper->jsonSerialize()
        );
    }

    public function testFromArray()
    {
        $paper = Paper::fromArray(['paper' => 'A5']);

        $this->assertSame(EnumPaper::A5, $paper->paper());
    }

    public function testFromArrayUsesDefaultWhenMissing()
    {
        $paper = Paper::fromArray([]);

        $this->assertSame(EnumPaper::A4, $paper->paper());
    }

    public function testRoundTripSerialization()
    {
        $original = new Paper(EnumPaper::LEGAL);

        $serialized = $original->jsonSerialize();
        $restored = Paper::fromArray($serialized);

        $this->assertSame($original->paper(), $restored->paper());
    }
}