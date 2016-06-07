<?php

namespace OOPHP\Resource\String;

use OOPHP\Resource\AbstractResource;
use OOPHP\Resource\Exception\ResourceAlreadySetException;

class Resource extends AbstractResource implements StringInterface
{
    /**
     * {@inheritdoc}
     */
    public function open(string $path, string $mode = 'r', bool $useIncludePath = null, $context = null)
    {
        if (isset($this->resource)) {
            throw new ResourceAlreadySetException();
        }
        
        $base64Source = base64_encode($path);
        $path = 'data://plain/text;base64,'.$base64Source;

        return parent::open($path, $mode, $useIncludePath, $context);
    }

    protected function setMode(string $mode)
    {
        $return = parent::setMode($mode);

        if ($this->isAppendOnly()) {
            $this->seek(0, SEEK_END);
        }

        return $return;
    }
}
