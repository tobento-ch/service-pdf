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
use Tobento\Service\Pdf\NullPdfGenerator;
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfInterface;

class NullPdfGeneratorTest extends TestCase
{
    protected function pdf(): PdfInterface
    {
        return new Pdf();
    }

    public function testNameReturnsName()
    {
        $gen = new NullPdfGenerator(
            streamFactory: new Psr17Factory(),
            name: 'custom'
        );

        $this->assertSame('custom', $gen->name());
    }

    public function testNameDefaultsToNull()
    {
        $gen = new NullPdfGenerator(
            streamFactory: new Psr17Factory()
        );

        $this->assertSame('null', $gen->name());
    }

    public function testGenerateReturnsEmptyString()
    {
        $gen = new NullPdfGenerator(
            streamFactory: new Psr17Factory(),
            name: 'x'
        );

        $this->assertSame('', $gen->generate($this->pdf()));
    }

    public function testStreamReturnsEmptyStream()
    {
        $gen = new NullPdfGenerator(
            streamFactory: new Psr17Factory(),
            name: 'y'
        );

        $stream = $gen->stream($this->pdf());

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame('', (string)$stream);
    }
}