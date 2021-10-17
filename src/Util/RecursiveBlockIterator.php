<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\BlockBundle\Util;

use Doctrine\Common\Collections\Collection;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
final class RecursiveBlockIterator extends \RecursiveArrayIterator
{
    /**
     * @param Collection<array-key, mixed>|array<mixed> $array
     */
    public function __construct($array)
    {
        if (\is_object($array)) {
            $array = $array->toArray();
        }

        parent::__construct($array);
    }

    /**
     * @return RecursiveBlockIterator<mixed>
     */
    public function getChildren(): self
    {
        return new self($this->current()->getChildren());
    }

    public function hasChildren(): bool
    {
        return $this->current()->hasChildren();
    }
}
