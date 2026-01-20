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

use Tobento\Service\Pdf\Parameter;

class Pdf implements PdfInterface
{
    use Traits\HasParameters;
    use Traits\ConfiguresParameters;
    
    /**
     * Returns the logical name of the PDF.
     *
     * If no name has been explicitly set, the class name is returned
     * as a safe fallback identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        $param = $this->getLastParameter(Parameter\Name::class);

        return $param instanceof Parameter\Name ? $param->name() : static::class;
    }
    
    /**
     * Returns the first parameter matching the class.
     */
    protected function getFirstParameter(string $class): null|ParameterInterface
    {
        return $this->parameters()
            ->filter(fn(ParameterInterface $p): bool => $p instanceof $class)
            ->first();
    }

    /**
     * Returns the last parameter matching the class.
     */
    protected function getLastParameter(string $class): null|ParameterInterface
    {
        return $this->parameters()
            ->filter(fn(ParameterInterface $p): bool => $p instanceof $class)
            ->last();
    }
}