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

use Psr\Http\Message\ResponseInterface;
use Tobento\Service\Pdf\Exception\PdfGenerationException;

interface PdfResponseFactoryInterface
{
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
     * @throws PdfGenerationException If PDF generation fails.
     */
    public function download(PdfInterface $pdf, string $filename = 'document.pdf'): ResponseInterface;
}