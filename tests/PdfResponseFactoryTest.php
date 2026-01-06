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
use Psr\Http\Message\ResponseInterface;
use Tobento\Service\Pdf\NullPdfGenerator;
use Tobento\Service\Pdf\Pdf;
use Tobento\Service\Pdf\PdfInterface;
use Tobento\Service\Pdf\PdfResponseFactory;

class PdfResponseFactoryTest extends TestCase
{
    protected function pdf(): PdfInterface
    {
        return new Pdf();
    }

    public function testDownloadCreatesValidResponse()
    {
        $streamFactory = new Psr17Factory();
        $responseFactory = new Psr17Factory();

        // NullPdfGenerator returns an empty stream
        $generator = new NullPdfGenerator(
            streamFactory: $streamFactory,
            name: 'null'
        );

        $factory = new PdfResponseFactory(
            generator: $generator,
            streamFactory: $streamFactory,
            responseFactory: $responseFactory
        );

        $response = $factory->download($this->pdf(), 'test.pdf');

        $this->assertInstanceOf(ResponseInterface::class, $response);

        // Headers
        $this->assertSame('application/pdf', $response->getHeaderLine('Content-Type'));
        $this->assertSame('attachment; filename="test.pdf"', $response->getHeaderLine('Content-Disposition'));

        // Body is the generator's stream
        $this->assertSame('', (string)$response->getBody());
    }

    public function testDefaultFilenameIsUsed()
    {
        $streamFactory = new Psr17Factory();
        $responseFactory = new Psr17Factory();

        $generator = new NullPdfGenerator(
            streamFactory: $streamFactory,
            name: 'null'
        );

        $factory = new PdfResponseFactory(
            generator: $generator,
            streamFactory: $streamFactory,
            responseFactory: $responseFactory
        );

        $response = $factory->download($this->pdf());

        $this->assertSame(
            'attachment; filename="document.pdf"',
            $response->getHeaderLine('Content-Disposition')
        );
    }
}