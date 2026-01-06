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

namespace Tobento\Service\Pdf;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Factory responsible for creating PSR-7 responses
 * for downloading generated PDF documents.
 *
 * This class delegates PDF generation to the configured
 * PdfGeneratorInterface implementation and wraps the
 * resulting PDF stream inside a PSR-7 ResponseInterface.
 */
class PdfResponseFactory implements PdfResponseFactoryInterface
{
    /**
     * Create a new PdfResponseFactory instance.
     *
     * @param PdfGeneratorInterface $generator The PDF generator used to produce the PDF stream.
     * @param StreamFactoryInterface $streamFactory The PSR-17 stream factory.
     * @param ResponseFactoryInterface $responseFactory The PSR-17 response factory.
     */
    public function __construct(
        protected PdfGeneratorInterface $generator,
        protected StreamFactoryInterface $streamFactory,
        protected ResponseFactoryInterface $responseFactory,
    ) {}

    /**
     * Creates a PSR-7 response that triggers a PDF download.
     *
     * The PDF is generated using the configured PdfGeneratorInterface
     * and returned as a PSR-7 stream. The response includes the
     * appropriate headers for forcing a file download in browsers.
     *
     * @param PdfInterface $pdf The PDF definition to generate.
     * @param string $filename The suggested filename for the download.
     * @return ResponseInterface A PSR-7 response containing the PDF stream.
     */
    public function download(PdfInterface $pdf, string $filename = 'document.pdf'): ResponseInterface
    {
        $stream = $this->generator->stream($pdf);

        $response = $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withBody($stream);

        return $response;
    }
}