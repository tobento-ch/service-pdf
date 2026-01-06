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
use Tobento\Service\Pdf\Enums\Orientation as OrientationEnum;
use Tobento\Service\Pdf\ParameterInterface;

/**
 * Defines the page orientation (portrait or landscape).
 */
class Orientation implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new instance.
     *
     * @param OrientationEnum $orientation
     */
    public function __construct(
        protected OrientationEnum $orientation
    ) {}

    /**
     * Returns the orientation.
     *
     * @return OrientationEnum
     */
    public function orientation(): OrientationEnum
    {
        return $this->orientation;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['orientation' => $this->orientation()->value];
    }

    /**
     * Reconstructs the parameter instance from a serialized array.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            OrientationEnum::from($data['orientation'])
        );
    }
}