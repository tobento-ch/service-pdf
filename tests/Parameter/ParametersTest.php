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

use ArrayIterator;
use JsonSerializable;
use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Parameter\Html;
use Tobento\Service\Pdf\Parameter\PageBreak;
use Tobento\Service\Pdf\Parameter\Parameters;
use Tobento\Service\Pdf\ParameterInterface;
use Tobento\Service\Pdf\ParametersInterface;

class ParametersTest extends TestCase
{
    public function testImplementsInterfaces()
    {
        $params = new Parameters();

        $this->assertInstanceOf(ParametersInterface::class, $params);
        $this->assertInstanceOf(\IteratorAggregate::class, $params);
    }

    public function testConstructorStoresParameters()
    {
        $p1 = new Html('A');
        $p2 = new PageBreak();

        $params = new Parameters($p1, $p2);

        $this->assertSame([$p1, $p2], $params->all());
    }

    public function testAddAppendsParameter()
    {
        $params = new Parameters();
        $p1 = new Html('A');

        $params->add($p1);

        $this->assertSame([$p1], $params->all());
    }

    public function testFilterReturnsNewInstance()
    {
        $p1 = new Html('A');
        $p2 = new PageBreak();

        $params = new Parameters($p1, $p2);

        $filtered = $params->filter(fn ($p) => $p instanceof Html);

        $this->assertNotSame($params, $filtered);
        $this->assertSame([$p1], $filtered->all());
        $this->assertSame([$p1, $p2], $params->all()); // original unchanged
    }

    public function testFirstReturnsFirstParameter()
    {
        $p1 = new Html('A');
        $p2 = new PageBreak();

        $params = new Parameters($p1, $p2);

        $this->assertSame($p1, $params->first());
    }

    public function testFirstReturnsNullWhenEmpty()
    {
        $params = new Parameters();

        $this->assertNull($params->first());
    }

    public function testIteratorReturnsArrayIterator()
    {
        $p1 = new Html('A');
        $p2 = new PageBreak();

        $params = new Parameters($p1, $p2);

        $iterator = $params->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $iterator);
        $this->assertSame([$p1, $p2], iterator_to_array($iterator));
    }

    public function testJsonSerialize()
    {
        $p1 = new Html('A');
        $p2 = new PageBreak();

        $params = new Parameters($p1, $p2);

        $json = $params->jsonSerialize();

        $this->assertSame(
            [
                Html::class . ':0' => ['html' => 'A'],
                PageBreak::class . ':1' => [],
            ],
            $json
        );
    }

    public function testToStringReturnsJsonString()
    {
        $p1 = new Html('A');
        $params = new Parameters($p1);

        $this->assertSame(
            json_encode($params->jsonSerialize()),
            (string)$params
        );
    }
}