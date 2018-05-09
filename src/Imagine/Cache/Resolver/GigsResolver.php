<?php

namespace App\Imagine\Cache\Resolver;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Exception\Imagine\Cache\Resolver\NotResolvableException;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;

class GigsResolver implements ResolverInterface
{
    /**
     * @param string $path
     * @param string $filter
     *
     * @return bool
     */
    public function isStored($path, $filter)
    {
        /** @todo: implement */
    }

    /**
     * @param string $path
     * @param string $filter
     *
     * @return string
     */
    public function resolve($path, $filter)
    {
        /** @todo: implement */
    }

    /**
     * @param BinaryInterface $binary
     * @param string          $path
     * @param string          $filter
     */
    public function store(BinaryInterface $binary, $path, $filter)
    {
        /** @todo: implement */
    }

    /**
     * @param string[] $paths
     * @param string[] $filters
     */
    public function remove(array $paths, array $filters)
    {
        /** @todo: implement */
    }
}
