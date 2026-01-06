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

namespace Tobento\Service\Pdf\Parameter;

use JsonSerializable;
use Tobento\Service\Pdf\Enums\Paper as EnumPaper;
use Tobento\Service\Pdf\ParameterInterface;

class Paper implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new instance.
     *
     * @param EnumPaper $paper
     */
    public function __construct(
        protected EnumPaper $paper
    ) {}

    /**
     * Returns the paper.
     *
     * @return EnumPaper
     */
    public function paper(): EnumPaper
    {
        return $this->paper;
    }
    
    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['paper' => $this->paper()->value];
    }
    
    /**
     * Reconstructs the parameter instance from a serialized array.
     *
     * This method is used by the ParametersFactory when rebuilding
     * parameters from JSON or array data. It converts the serialized
     * paper value back into its corresponding Paper enum instance.
     *
     * @param array $data The serialized parameter data (e.g. ['paper' => 'A4']).
     * @return static A new parameter instance created from the given data.
     */
    public static function fromArray(array $data): static
    {
        return new static(EnumPaper::from($data['paper'] ?? EnumPaper::A4->value));
    }
}