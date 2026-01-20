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

interface PdfInterface
{
    /**
     * Returns the logical name of the PDF.
     *
     * This name identifies the PDF within the application domain
     * (e.g. "invoice", "report", "contract") and is intended for
     * event handling, conditional logic, routing, or analytics.
     *
     * @return string
     */
    public function getName(): string;
    
    /**
     * Returns the parameters describing the PDF behavior.
     *
     * Parameters are used to configure PDF generation (e.g. paper size,
     * orientation, watermark, password protection) and may be extended
     * by userland implementations.
     *
     * @return ParametersInterface
     */
    public function parameters(): ParametersInterface;
}