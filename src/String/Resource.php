<?php

namespace OOPHP\Resource\String;

use OOPHP\Resource\AbstractResource;
use OOPHP\Resource\Exception\ReadOnlyException;
use OOPHP\Resource\Exception\ResourceAlreadySetException;
use OOPHP\Resource\Exception\UnknownWhenceException;
use OOPHP\Resource\Exception\WriteOnlyException;

class Resource extends AbstractResource implements StringInterface
{
    /**
     * @var string $resource
     */
    protected $resource;

    /**
     * @var int $pointer
     */
    protected $pointer = 0;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function open($source, string $mode)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }

        $this->resource = (string)$source;
        $this->pointer = 0;
        $this->eof = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws WriteOnlyException
     */
    public function read(int $length)
    {
        if (!$this->isReadable()) {
            throw new WriteOnlyException();
        }

        $string = substr($this->resource, $this->pointer, $length);
        if (($this->pointer + $length) > strlen($this->resource)) {
            $this->pointer = strlen($this->resource);
        } else {
            $this->pointer += $length;
        }

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->pointer = 0;

        return true;
    }

    /**
     * {@inheritdoc}
     * @throws UnknownWhenceException
     */
    public function seek(int $offset, int $whence = SEEK_SET)
    {
        switch ($whence) {
            case SEEK_SET:
                $this->pointer = $offset;
                break;
            case SEEK_CUR:
                $this->pointer += $offset;
                break;
            case SEEK_END:
                $this->pointer = strlen($this->resource) + $offset;
                break;
            default:
                throw new UnknownWhenceException();
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     * @throws ReadOnlyException
     */
    public function write(string $string, int $length = null)
    {
        if (!$this->isWritable()) {
            throw new ReadOnlyException();
        }

        if ($length !== null) {
            $string = substr($string, 0, $length);
        }

        if ($this->isAppendOnly()) {
            $this->resource .= $string;
        } else {
            $this->resource = substr_replace($this->resource, $string, $this->pointer, strlen($string));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    protected function setMode(string $mode)
    {
        $return = parent::setMode($mode);

        if ($this->isAppendOnly()) {
            $this->pointer = strlen($this->resource);
        }

        return $return;
    }
}
