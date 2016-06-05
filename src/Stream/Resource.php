<?php

namespace OOPHP\Resource\Stream;

use OOPHP\Resource\Exception\ResourceAlreadySetException;
use OOPHP\Resource\File\Resource as FileResource;
use OOPHP\Resource\String\StringInterface;

class Resource extends FileResource implements StringInterface
{
    /**
     * @param mixed  $source
     * @param string $mode
     *
     * @return $this
     * @throws ResourceAlreadySetException
     */
    public function open($source, string $mode)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }

        if (is_string($source)) {
            return parent::open($source, $mode);
        }

        $this->resource = $source;

        return $this;
    }
}
