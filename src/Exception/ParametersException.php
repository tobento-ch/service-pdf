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
 * Exception thrown when parameter handling fails.
 *
 * This exception is used for errors related to creating,
 * validating, or reconstructing PDF parameters, such as
 * invalid input data, missing fields, or failed parameter
 * instantiation within the ParametersFactory.
 */
class ParametersException extends PdfException
{
    //
}