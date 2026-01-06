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
use Tobento\Service\Pdf\Exception\PdfGeneratorNotFoundException;
use Tobento\Service\Pdf\NullPdfGenerator;
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfGenerators;
use Tobento\Service\Pdf\PdfInterface;

class PdfGeneratorsTest extends TestCase
{
    protected function pdf(): PdfInterface
    {
        return new Pdf();
    }

    public function testGetReturnsGenerator()
    {
        $gen = new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'alpha');

        $generators = new PdfGenerators($gen);

        $this->assertSame($gen, $generators->get('alpha'));
    }

    public function testGetThrowsIfNotFound()
    {
        $this->expectException(PdfGeneratorNotFoundException::class);

        $generators = new PdfGenerators();

        $generators->get('missing');
    }

    public function testHas()
    {
        $gen = new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'beta');

        $generators = new PdfGenerators($gen);

        $this->assertTrue($generators->has('beta'));
        $this->assertFalse($generators->has('gamma'));
    }

    public function testNames()
    {
        $g1 = new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'a');
        $g2 = new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'b');

        $generators = new PdfGenerators($g1, $g2);

        $this->assertSame(['a', 'b'], $generators->names());
    }

    public function testNameDelegatesToFirstGenerator()
    {
        $gen = new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'first');

        $generators = new PdfGenerators($gen);

        $this->assertSame('first', $generators->name());
    }

    public function testGenerateDelegatesToFirstGenerator()
    {
        $gen = new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'x');

        $generators = new PdfGenerators($gen);

        $this->assertSame('', $generators->generate($this->pdf()));
    }

    public function testStreamDelegatesToFirstGenerator()
    {
        $gen = new NullPdfGenerator(streamFactory: new Psr17Factory(), name: 'y');

        $generators = new PdfGenerators($gen);

        $stream = $generators->stream($this->pdf());

        $this->assertInstanceOf(StreamInterface::class, $stream);
    }

    public function testGetFirstGeneratorThrowsIfEmpty()
    {
        $this->expectException(PdfGeneratorNotFoundException::class);

        $generators = new PdfGenerators();

        $generators->name(); // triggers getFirstGenerator()
    }
}