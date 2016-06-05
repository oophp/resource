<?php

namespace OOPHP\Resource\File\Compressed;

use OOPHP\Resource\Exception\ResourceAlreadySetException;
use OOPHP\Resource\File\Resource;

class Zlib extends Resource
{
    public function open($source, string $mode, bool $useIncludePath = false, $context = null)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }

        $this->filePath = $source;
        $this->resource = gzopen($source, $mode, $useIncludePath);

        return $this;
    }

    public function read(int $length)
    {
        return gzread($this->resource, $length);
    }

    public function rewind()
    {
        return gzrewind($this->resource);
    }

    public function seek(int $offset, int $whence = SEEK_SET)
    {
        return gzseek($this->resource, $offset, $whence);
    }

    public function write(string $string, int $length = null)
    {
        return gzwrite($this->resource, $string, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return gzclose($this->resource);
    }
}
