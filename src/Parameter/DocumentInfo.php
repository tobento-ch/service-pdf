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
use Tobento\Service\Pdf\ParameterInterface;

/**
 * Defines PDF document metadata.
 */
class DocumentInfo implements ParameterInterface, JsonSerializable
{
    /**
     * Create a new DocumentInfo instance.
     *
     * @param string|null $title
     * @param string|null $author
     * @param string|null $subject
     * @param array $keywords
     */
    public function __construct(
        protected string|null $title = null,
        protected string|null $author = null,
        protected string|null $subject = null,
        protected array $keywords = [],
    ) {}

    /**
     * Returns the title.
     *
     * @return string|null
     */
    public function title(): string|null
    {
        return $this->title;
    }

    /**
     * Returns the author.
     *
     * @return string|null
     */
    public function author(): string|null
    {
        return $this->author;
    }

    /**
     * Returns the subject.
     *
     * @return string|null
     */
    public function subject(): string|null
    {
        return $this->subject;
    }

    /**
     * Returns the keywords.
     *
     * @return array
     */
    public function keywords(): array
    {
        return $this->keywords;
    }

    /**
     * Serializes the parameter to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title(),
            'author' => $this->author(),
            'subject' => $this->subject(),
            'keywords' => $this->keywords(),
        ];
    }
}