<?php

namespace OOPHP\Resource\File\Compressed;

use OOPHP\Resource\Exception\NotImplementedException;
use OOPHP\Resource\Exception\ResourceAlreadySetException;
use OOPHP\Resource\File\Resource;

class Bzip2 extends Resource
{
    /**
     * {@inheritdoc}
     * @throws ResourceAlreadySetException
     */
    public function open($source, string $mode, bool $useIncludePath = false, $context = null)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }

        $this->filePath = $source;
        $this->resource = bzopen($source, $mode);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $length = 1024)
    {
        return bzread($this->resource, $length);
    }

    /**
     * @throws NotImplementedException
     */
    public function rewind()
    {
        throw new NotImplementedException();
    }

    /**
     * @throws NotImplementedException
     */
    public function seek(int $offset, int $whence = SEEK_SET)
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $string, int $length = null)
    {
        return bzwrite($this->resource, $string, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return bzclose($this->resource);
    }
}
