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

namespace Tobento\Service\Pdf\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Exception\ParametersException;
use Tobento\Service\Pdf\Parameter\Html;
use Tobento\Service\Pdf\Parameter\Orientation;
use Tobento\Service\Pdf\Parameter\Paper;
use Tobento\Service\Pdf\Parameter\Parameters;
use Tobento\Service\Pdf\ParametersFactory;
use Tobento\Service\Pdf\ParametersFactoryInterface;

class ParametersFactoryTest extends TestCase
{
    protected function factory(): ParametersFactoryInterface
    {
        return new ParametersFactory();
    }

    public function testCreateFromArrayCreatesRealParameters()
    {
        $factory = $this->factory();

        $params = $factory->createFromArray([
            Html::class => ['html' => '<p>Hello</p>'],
            Paper::class => ['paper' => 'A4'],
            Orientation::class => ['orientation' => 'portrait'],
        ]);

        $this->assertInstanceOf(Parameters::class, $params);

        $this->assertInstanceOf(Html::class, $params->all()[0]);
        $this->assertSame('<p>Hello</p>', $params->all()[0]->html());

        $this->assertInstanceOf(Paper::class, $params->all()[1]);
        $this->assertSame('A4', $params->all()[1]->paper()->value);

        $this->assertInstanceOf(Orientation::class, $params->all()[2]);
        $this->assertSame('portrait', $params->all()[2]->orientation()->value);
    }

    public function testCreateFromArrayStripsNamePrefix()
    {
        $factory = $this->factory();

        $params = $factory->createFromArray([
            Html::class . ':ignored' => ['html' => '<b>Test</b>'],
        ]);

        $this->assertInstanceOf(Html::class, $params->all()[0]);
        $this->assertSame('<b>Test</b>', $params->all()[0]->html());
    }

    public function testCreateFromArrayThrowsOnInvalidValue()
    {
        $this->expectException(ParametersException::class);

        $factory = $this->factory();

        $factory->createFromArray([
            Html::class => 'not-an-array',
        ]);
    }

    public function testCreateFromArrayThrowsOnInvalidClass()
    {
        $this->expectException(ParametersException::class);

        $factory = $this->factory();

        $factory->createFromArray([
            \stdClass::class => ['foo' => 'bar'],
        ]);
    }

    public function testCreateFromJsonStringCreatesParameters()
    {
        $factory = $this->factory();

        $json = json_encode([
            Html::class => ['html' => '<i>JSON</i>'],
        ]);

        $params = $factory->createFromJsonString($json);

        $this->assertInstanceOf(Html::class, $params->all()[0]);
        $this->assertSame('<i>JSON</i>', $params->all()[0]->html());
    }

    public function testCreateFromJsonStringThrowsOnInvalidJson()
    {
        $this->expectException(ParametersException::class);

        $factory = $this->factory();

        $factory->createFromJsonString('{invalid json}');
    }

    public function testCreateFromJsonStringThrowsIfNotArray()
    {
        $this->expectException(ParametersException::class);

        $factory = $this->factory();

        $factory->createFromJsonString('"string-not-array"');
    }
}