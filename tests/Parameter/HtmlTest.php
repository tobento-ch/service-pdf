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
use Tobento\Service\Pdf\Parameter\Html;
use Tobento\Service\Pdf\ParameterInterface;

class HtmlTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $html = new Html('<p>Hello</p>');

        $this->assertInstanceOf(ParameterInterface::class, $html);
        $this->assertInstanceOf(JsonSerializable::class, $html);
    }

    public function testHtmlStringIsReturned()
    {
        $html = new Html('<p>Hello</p>');

        $this->assertSame('<p>Hello</p>', $html->html());
    }

    public function testHtmlStringableIsReturned()
    {
        $stringable = new class implements Stringable {
            public function __toString(): string
            {
                return '<b>Stringable</b>';
            }
        };

        $html = new Html($stringable);

        $this->assertSame('<b>Stringable</b>', $html->html());
    }

    public function testJsonSerialize()
    {
        $html = new Html('<span>Test</span>');

        $this->assertSame(
            ['html' => '<span>Test</span>'],
            $html->jsonSerialize()
        );
    }
}