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

namespace Tobento\Service\Pdf\Enums;

enum Paper: string
{
    case A4 = 'A4';
    case A3 = 'A3';
    case A5 = 'A5';
    case LETTER = 'Letter';
    case LEGAL = 'Legal';
}