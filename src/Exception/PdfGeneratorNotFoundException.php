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
 * Exception thrown when a requested PDF generator is not registered.
 *
 * This exception is used by generator registries (such as PdfGenerators or
 * LazyPdfGenerators) to indicate that no PDF generator exists under the
 * specified name. It represents a lookup failure, not an error during
 * generator construction or initialization.
 */
class PdfGeneratorNotFoundException extends PdfException
{
    //
}