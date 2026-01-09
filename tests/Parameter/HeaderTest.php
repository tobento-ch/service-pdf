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
use Tobento\Service\Pdf\Parameter\Header;
use Tobento\Service\Pdf\ParameterInterface;
use Tobento\Service\Pdf\Template;
use Tobento\Service\Pdf\TemplateAwareInterface;
use Tobento\Service\Pdf\TemplateInterface;

class HeaderTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $header = new Header('Header HTML');

        $this->assertInstanceOf(ParameterInterface::class, $header);
        $this->assertInstanceOf(JsonSerializable::class, $header);
        $this->assertInstanceOf(TemplateAwareInterface::class, $header);
    }

    public function testHtmlStringIsReturned()
    {
        $header = new Header('Header HTML');

        $this->assertSame('Header HTML', $header->html());
    }

    public function testHtmlStringableIsReturned()
    {
        $stringable = new class implements Stringable {
            public function __toString(): string
            {
                return 'Stringable Header';
            }
        };

        $header = new Header($stringable);

        $this->assertSame('Stringable Header', (string)$header->html());
    }

    public function testTemplateIsReturned()
    {
        $template = new Template('header-template', ['foo' => 'bar']);
        $header = new Header($template);

        $this->assertInstanceOf(TemplateInterface::class, $header->html());
        $this->assertSame('header-template', $header->html()->name());
        $this->assertSame(['foo' => 'bar'], $header->html()->data());
    }

    public function testJsonSerializeForHtml()
    {
        $header = new Header('Header HTML');

        $this->assertSame(
            ['html' => 'Header HTML'],
            $header->jsonSerialize()
        );
    }

    public function testJsonSerializeForTemplate()
    {
        $template = new Template('header-template', ['a' => 1]);
        $header = new Header($template);

        $this->assertSame(
            [
                'name' => 'header-template',
                'data' => ['a' => 1],
            ],
            $header->jsonSerialize()
        );
    }

    public function testFromArrayWithHtml()
    {
        $header = Header::fromArray(['html' => 'Header HTML']);

        $this->assertSame('Header HTML', $header->html());
    }

    public function testFromArrayWithTemplate()
    {
        $header = Header::fromArray([
            'name' => 'header-template',
            'data' => ['x' => 'y'],
        ]);

        $this->assertInstanceOf(TemplateInterface::class, $header->html());
        $this->assertSame('header-template', $header->html()->name());
        $this->assertSame(['x' => 'y'], $header->html()->data());
    }
    
    public function testTemplateReturnsNullWhenHtmlIsString()
    {
        $header = new Header('Header HTML');

        $this->assertNull($header->template());
    }

    public function testTemplateReturnsNullWhenHtmlIsStringable()
    {
        $stringable = new class implements Stringable {
            public function __toString(): string
            {
                return 'Stringable Header';
            }
        };

        $header = new Header($stringable);

        $this->assertNull($header->template());
    }

    public function testTemplateReturnsTemplateWhenHtmlIsTemplate()
    {
        $template = new Template('header-template', ['foo' => 'bar']);
        $header = new Header($template);

        $this->assertInstanceOf(TemplateInterface::class, $header->template());
        $this->assertSame('header-template', $header->template()->name());
        $this->assertSame(['foo' => 'bar'], $header->template()->data());
    }

    public function testWithRenderedHtmlReturnsNewInstance()
    {
        $header = new Header('Original');
        $new = $header->withRenderedHtml('Rendered HTML');

        $this->assertNotSame($header, $new);
        $this->assertSame('Rendered HTML', $new->html());
    }

    public function testWithRenderedHtmlReturnsParameterInterface()
    {
        $header = new Header('Original');
        $new = $header->withRenderedHtml('Rendered HTML');

        $this->assertInstanceOf(ParameterInterface::class, $new);
    }

    public function testWithRenderedHtmlRemovesTemplate()
    {
        $template = new Template('header-template', ['foo' => 'bar']);
        $header = new Header($template);

        $new = $header->withRenderedHtml('Rendered HTML');

        $this->assertNull($new->template());
        $this->assertSame('Rendered HTML', $new->html());
    }
}