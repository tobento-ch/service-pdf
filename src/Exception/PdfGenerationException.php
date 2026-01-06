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

namespace Tobento\Service\Pdf\Exception;

/**
 * Exception thrown when PDF generation fails.
 *
 * This exception is used by PdfGeneratorInterface implementations
 * to signal errors that occur during the creation of a PDF document,
 * such as rendering failures, engine errors, or invalid input data.
 */
class PdfGenerationException extends PdfException
{
    
}