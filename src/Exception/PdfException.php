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

use Exception;

/**
 * Base exception for all PDF-related errors.
 *
 * All exceptions thrown within the PDF service extend this class,
 * allowing consumers to catch PdfException to handle any PDF-specific
 * failure in a unified way.
 */
class PdfException extends Exception
{
    
}