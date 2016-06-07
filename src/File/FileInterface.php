<?php

namespace OOPHP\Resource\File;

use OOPHP\Resource\ResourceInterface;

interface FileInterface extends ResourceInterface
{
    /**
     * StringInterface constructor.
     *
     * @param mixed    $path           Specifies the URL that was passed to the original function.
     *                                 The URL can be broken apart with parse_url(). Note that only URLs delimited by
     *                                 :// are supported. : and :/ while technically valid URLs, are not.
     * @param string   $mode           The mode used to open the file, as detailed for fopen().
     *                                 The mode must match the regex '/^[rwaxc]\+?[bt]?$/'.
     * @param bool     $useIncludePath The optional third use_include_path parameter can be set to '1' or TRUE if you
     *                                 want to search for the file in the include_path, too.
     * @param resource $context        Stream context created using stream_context_create().
     */
    public function __construct(string $path, string $mode = 'r', bool $useIncludePath = null, $context = null);

    /**
     * @param mixed    $path           Specifies the URL that was passed to the original function.
     *                                 The URL can be broken apart with parse_url(). Note that only URLs delimited by
     *                                 :// are supported. : and :/ while technically valid URLs, are not.
     * @param string   $mode           The mode used to open the file, as detailed for fopen().
     *                                 The mode must match the regex '/^[rwaxc]\+?[bt]?$/'.
     * @param bool     $useIncludePath The optional third use_include_path parameter can be set to '1' or TRUE if you
     *                                 want to search for the file in the include_path, too.
     * @param resource $context        Stream context created using stream_context_create().
     *
     * @return $this
     */
    public function open(string $path, string $mode = 'r', bool $useIncludePath = null, $context = null);
}
