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
use Stringable;
use Tobento\Service\Pdf\Parameter\Text;
use Tobento\Service\Pdf\ParameterInterface;

class TextTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $text = new Text('Hello');

        $this->assertInstanceOf(ParameterInterface::class, $text);
        $this->assertInstanceOf(JsonSerializable::class, $text);
    }

    public function testTextStringIsReturned()
    {
        $text = new Text('Hello World');

        $this->assertSame('Hello World', $text->text());
    }

    public function testTextStringableIsReturned()
    {
        $stringable = new class implements Stringable {
            public function __toString(): string
            {
                return 'Stringable Text';
            }
        };

        $text = new Text($stringable);

        $this->assertSame('Stringable Text', $text->text());
    }

    public function testJsonSerialize()
    {
        $text = new Text('Serialized');

        $this->assertSame(
            ['text' => 'Serialized'],
            $text->jsonSerialize()
        );
    }
}