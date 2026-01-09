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
use Tobento\Service\Pdf\Parameter\Footer;
use Tobento\Service\Pdf\ParameterInterface;
use Tobento\Service\Pdf\Template;
use Tobento\Service\Pdf\TemplateAwareInterface;
use Tobento\Service\Pdf\TemplateInterface;

class FooterTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $footer = new Footer('Footer HTML');

        $this->assertInstanceOf(ParameterInterface::class, $footer);
        $this->assertInstanceOf(JsonSerializable::class, $footer);
        $this->assertInstanceOf(TemplateAwareInterface::class, $footer);
    }

    public function testHtmlStringIsReturned()
    {
        $footer = new Footer('Footer HTML');

        $this->assertSame('Footer HTML', $footer->html());
    }

    public function testHtmlStringableIsReturned()
    {
        $stringable = new class implements Stringable {
            public function __toString(): string
            {
                return 'Stringable Footer';
            }
        };

        $footer = new Footer($stringable);

        $this->assertSame('Stringable Footer', (string)$footer->html());
    }

    public function testTemplateIsReturned()
    {
        $template = new Template('footer-template', ['foo' => 'bar']);
        $footer = new Footer($template);

        $this->assertInstanceOf(TemplateInterface::class, $footer->html());
        $this->assertSame('footer-template', $footer->html()->name());
        $this->assertSame(['foo' => 'bar'], $footer->html()->data());
    }

    public function testJsonSerializeForHtml()
    {
        $footer = new Footer('Footer HTML');

        $this->assertSame(
            ['html' => 'Footer HTML'],
            $footer->jsonSerialize()
        );
    }

    public function testJsonSerializeForTemplate()
    {
        $template = new Template('footer-template', ['a' => 1]);
        $footer = new Footer($template);

        $this->assertSame(
            [
                'name' => 'footer-template',
                'data' => ['a' => 1],
            ],
            $footer->jsonSerialize()
        );
    }

    public function testFromArrayWithHtml()
    {
        $footer = Footer::fromArray(['html' => 'Footer HTML']);

        $this->assertSame('Footer HTML', $footer->html());
    }

    public function testFromArrayWithTemplate()
    {
        $footer = Footer::fromArray([
            'name' => 'footer-template',
            'data' => ['x' => 'y'],
        ]);

        $this->assertInstanceOf(TemplateInterface::class, $footer->html());
        $this->assertSame('footer-template', $footer->html()->name());
        $this->assertSame(['x' => 'y'], $footer->html()->data());
    }
    
    public function testTemplateReturnsNullWhenHtmlIsString()
    {
        $footer = new Footer('Footer HTML');

        $this->assertNull($footer->template());
    }

    public function testTemplateReturnsNullWhenHtmlIsStringable()
    {
        $stringable = new class implements Stringable {
            public function __toString(): string
            {
                return 'Stringable Footer';
            }
        };

        $footer = new Footer($stringable);

        $this->assertNull($footer->template());
    }

    public function testTemplateReturnsTemplateWhenHtmlIsTemplate()
    {
        $template = new Template('footer-template', ['foo' => 'bar']);
        $footer = new Footer($template);

        $this->assertInstanceOf(TemplateInterface::class, $footer->template());
        $this->assertSame('footer-template', $footer->template()->name());
        $this->assertSame(['foo' => 'bar'], $footer->template()->data());
    }

    public function testWithRenderedHtmlReturnsNewInstance()
    {
        $footer = new Footer('Original');
        $new = $footer->withRenderedHtml('Rendered HTML');

        $this->assertNotSame($footer, $new);
        $this->assertSame('Rendered HTML', $new->html());
    }

    public function testWithRenderedHtmlReturnsParameterInterface()
    {
        $footer = new Footer('Original');
        $new = $footer->withRenderedHtml('Rendered HTML');

        $this->assertInstanceOf(ParameterInterface::class, $new);
    }

    public function testWithRenderedHtmlRemovesTemplate()
    {
        $template = new Template('footer-template', ['foo' => 'bar']);
        $footer = new Footer($template);

        $new = $footer->withRenderedHtml('Rendered HTML');

        // After rendering, it should NOT be a template anymore
        $this->assertNull($new->template());
        $this->assertSame('Rendered HTML', $new->html());
    }
}