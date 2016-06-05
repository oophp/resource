<?php

namespace OOPHP\Resource\File;

use OOPHP\Resource\AbstractResource;
use OOPHP\Resource\Exception\ResourceAlreadySetException;

class Resource extends AbstractResource implements FileInterface
{
    /**
     * @var string $filePath
     */
    protected $filePath;

    /**
     * @var resource $resource
     */
    protected $resource;

    /**
     * {@inheritdoc}
     * @param bool   $useIncludePath
     * @param null   $context
     *
     * @throws ResourceAlreadySetException
     */
    public function open($source, string $mode, bool $useIncludePath = false, $context = null)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }

        $this->filePath = $source;
        $this->resource = fopen($source, $mode, $useIncludePath, $context);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function read(int $length = null)
    {
        return fread($this->resource, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return rewind($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function seek(int $offset, int $whence = SEEK_SET)
    {
        return fseek($this->resource, $offset, $whence);
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $string, int $length = null)
    {
        return fwrite($this->resource, $string, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return fclose($this->resource);
    }
}
