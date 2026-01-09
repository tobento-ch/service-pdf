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
use Tobento\Service\Pdf\Parameter\Html;
use Tobento\Service\Pdf\Parameter\Template;
use Tobento\Service\Pdf\ParameterInterface;
use Tobento\Service\Pdf\Template as BaseTemplate;
use Tobento\Service\Pdf\TemplateAwareInterface;
use Tobento\Service\Pdf\TemplateInterface;

class TemplateTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $tpl = new Template(new BaseTemplate('name', []));

        $this->assertInstanceOf(ParameterInterface::class, $tpl);
        $this->assertInstanceOf(JsonSerializable::class, $tpl);
        $this->assertInstanceOf(TemplateAwareInterface::class, $tpl);
    }

    public function testTemplateIsReturned()
    {
        $inner = new BaseTemplate('invoice', ['a' => 1]);
        $tpl = new Template($inner);

        $this->assertInstanceOf(TemplateInterface::class, $tpl->template());
        $this->assertSame('invoice', $tpl->template()->name());
        $this->assertSame(['a' => 1], $tpl->template()->data());
    }

    public function testJsonSerialize()
    {
        $inner = new BaseTemplate('doc', ['x' => 'y']);
        $tpl = new Template($inner);

        $this->assertSame(
            [
                'name' => 'doc',
                'data' => ['x' => 'y'],
            ],
            $tpl->jsonSerialize()
        );
    }

    public function testFromArray()
    {
        $tpl = Template::fromArray([
            'name' => 'report',
            'data' => ['foo' => 'bar'],
        ]);

        $this->assertInstanceOf(TemplateInterface::class, $tpl->template());
        $this->assertSame('report', $tpl->template()->name());
        $this->assertSame(['foo' => 'bar'], $tpl->template()->data());
    }

    public function testRoundTripSerialization()
    {
        $original = new Template(new BaseTemplate('email', ['k' => 'v']));

        $serialized = $original->jsonSerialize();
        $restored = Template::fromArray($serialized);

        $this->assertSame($original->template()->name(), $restored->template()->name());
        $this->assertSame($original->template()->data(), $restored->template()->data());
    }
    
    public function testTemplateMethodAlwaysReturnsTemplate()
    {
        $inner = new BaseTemplate('tpl', ['x' => 1]);
        $tpl = new Template($inner);

        $this->assertSame($inner, $tpl->template());
    }

    public function testWithRenderedHtmlReturnsHtmlParameter()
    {
        $tpl = new Template(new BaseTemplate('tpl', []));

        $new = $tpl->withRenderedHtml('Rendered HTML');

        $this->assertInstanceOf(ParameterInterface::class, $new);
        $this->assertInstanceOf(Html::class, $new);
        $this->assertSame('Rendered HTML', $new->html());
    }

    public function testWithRenderedHtmlReturnsNewInstance()
    {
        $tpl = new Template(new BaseTemplate('tpl', []));
        $new = $tpl->withRenderedHtml('Rendered HTML');

        $this->assertNotSame($tpl, $new);
    }

    public function testWithRenderedHtmlRemovesTemplate()
    {
        $tpl = new Template(new BaseTemplate('tpl', []));

        $new = $tpl->withRenderedHtml('Rendered HTML');

        $this->assertInstanceOf(Html::class, $new);
        $this->assertSame('Rendered HTML', $new->html());
    }
}