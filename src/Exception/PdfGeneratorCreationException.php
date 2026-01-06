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
 * Exception thrown when a PDF generator cannot be created.
 *
 * This exception is used by PdfGeneratorFactoryInterface implementations
 * to signal errors that occur during the construction or initialization
 * of a PDF generator instance, such as invalid configuration, missing
 * dependencies, or failures in the underlying PDF engine setup.
 */
class PdfGeneratorCreationException extends PdfException
{
    //
}