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
     * {@inheritdoc}
     */
    public function open(string $path, string $mode = 'r', bool $useIncludePath = null, $context = null)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }

        $this->filePath = $path;

        return parent::open($path, $mode, $useIncludePath, $context);
    }
}
