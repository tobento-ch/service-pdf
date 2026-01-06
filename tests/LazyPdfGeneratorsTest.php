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

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Tobento\Service\Container\Container;
use Tobento\Service\Pdf\Exception\PdfGeneratorNotFoundException;
use Tobento\Service\Pdf\LazyPdfGenerators;
use Tobento\Service\Pdf\NullPdfGenerator;
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfGeneratorFactoryInterface;
use Tobento\Service\Pdf\PdfGeneratorInterface;
use Tobento\Service\Pdf\PdfInterface;

class LazyPdfGeneratorsTest extends TestCase
{
    protected function container(): Container
    {
        return new Container();
    }

    protected function pdf(): PdfInterface
    {
        return new Pdf();
    }

    public function testDirectInstance()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            ['alpha' => new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'alpha')]
        );

        $gen = $lazy->get('alpha');

        $this->assertSame('alpha', $gen->name());
    }

    public function testCallableFactory()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            [
                'beta' => function () {
                    return new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'beta');
                }
            ]
        );

        $gen = $lazy->get('beta');

        $this->assertSame('beta', $gen->name());
    }

    public function testArrayFactory()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            [
                'gamma' => [
                    'factory' => GammaFactory::class,
                    'config' => ['foo' => 'bar'],
                ]
            ]
        );

        $gen = $lazy->get('gamma');

        $this->assertSame('gamma', $gen->name());
    }

    public function testMissingGeneratorThrows()
    {
        $this->expectException(PdfGeneratorNotFoundException::class);
        
        $lazy = new LazyPdfGenerators($this->container(), []);
        
        $lazy->get('missing');
    }

    public function testMissingFactoryKeyThrows()
    {
        $this->expectException(PdfGeneratorNotFoundException::class);
        
        $lazy = new LazyPdfGenerators(
            $this->container(),
            ['delta' => []]
        );

        $lazy->get('delta');
    }

    public function testInvalidFactoryThrows()
    {
        $this->expectException(\TypeError::class);
        
        $lazy = new LazyPdfGenerators(
            $this->container(),
            [
                'epsilon' => [
                    'factory' => new \stdClass(),
                ]
            ]
        );

        $lazy->get('epsilon');
    }

    public function testHas()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            ['zeta' => new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'zeta')]
        );

        $this->assertTrue($lazy->has('zeta'));
        $this->assertFalse($lazy->has('eta'));
    }

    public function testNames()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            ['a' => 1, 'b' => 2]
        );

        $this->assertSame(['a', 'b'], $lazy->names());
    }

    public function testNameDelegatesToFirstGenerator()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            ['theta' => new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'theta')]
        );

        $this->assertSame('theta', $lazy->name());
    }

    public function testGenerateDelegates()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            ['iota' => new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'iota')]
        );

        $this->assertSame('', $lazy->generate($this->pdf()));
    }

    public function testStreamDelegates()
    {
        $lazy = new LazyPdfGenerators(
            $this->container(),
            ['kappa' => new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'kappa')]
        );

        $stream = $lazy->stream($this->pdf());

        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    public function testGetFirstGeneratorThrowsIfEmpty()
    {
        $this->expectException(PdfGeneratorNotFoundException::class);
        
        $lazy = new LazyPdfGenerators($this->container(), []);

        $lazy->name();
    }
}

class GammaFactory implements PdfGeneratorFactoryInterface
{
    public function createGenerator(string $name, array $config = []): PdfGeneratorInterface
    {
        return new NullPdfGenerator(streamFactory: new Psr17Factory(), name: $name);
    }
}