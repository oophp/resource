<?php

namespace OOPHP\Resource\File\Compressed;

use OOPHP\Resource\Exception\ResourceAlreadySetException;
use OOPHP\Resource\File\Resource;

class Zlib extends Resource
{
    public function open(string $path, string $mode = 'r', bool $useIncludePath = null, $context = null)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }

        $path = 'compress.zlib://'.$path;

        return parent::open($path, $mode, $useIncludePath, $context);
    }
}
