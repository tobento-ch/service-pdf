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

namespace Tobento\Service\Pdf\Test\Mpdf;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Tobento\Service\Pdf\Mpdf\PdfGeneratorFactory;
use Tobento\Service\Pdf\Mpdf\PdfGenerator;
use Tobento\Service\Pdf\RendererInterface;
use Tobento\Service\Pdf\Exception\PdfGeneratorCreationException;
use Tobento\Service\Pdf\PdfGeneratorInterface;
use Tobento\Service\Pdf\Test\Helper\FakeRenderer;

class PdfGeneratorFactoryTest extends TestCase
{
    protected function factory(): PdfGeneratorFactory
    {
        return new PdfGeneratorFactory(
            renderer: new FakeRenderer(),
            streamFactory: new Psr17Factory(),
        );
    }

    public function testCreatesPdfGeneratorInstance()
    {
        $factory = $this->factory();

        $generator = $factory->createGenerator('mpdf', ['foo' => 'bar']);

        $this->assertInstanceOf(PdfGeneratorInterface::class, $generator);
        $this->assertInstanceOf(PdfGenerator::class, $generator);
        $this->assertSame('mpdf', $generator->name());
    }
}