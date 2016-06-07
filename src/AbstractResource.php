<?php

namespace OOPHP\Resource;

use OOPHP\Resource\Exception\BadResourceModeException;
use OOPHP\Resource\Exception\ReadOnlyException;
use OOPHP\Resource\Exception\ResourceAlreadySetException;
use OOPHP\Resource\Exception\WriteOnlyException;

abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var resource $resource
     */
    protected $resource;

    /**
     * @var resource $context
     */
    protected $context;

    /**
     * @var array $modeMap
     */
    protected $modeMap = [
        'r'  => self::MODE_READ,
        'r+' => self::MODE_READ_PLUS,
        'w'  => self::MODE_WRITE,
        'w+' => self::MODE_WRITE_PLUS,
        'a'  => self::MODE_APPEND,
        'a+' => self::MODE_APPEND_PLUS,
        'x'  => self::MODE_EXCLUSIVE,
        'x+' => self::MODE_EXCLUSIVE_PLUS,
        'c'  => self::MODE_CAUTIOUS,
        'c+' => self::MODE_CAUTIOUS_PLUS,
    ];

    /**
     * @var array $readWriteModeMap
     */
    protected $readWriteModeMap = [
        'read'  =>
            self::MODE_READ +
            self::MODE_READ_PLUS +
            self::MODE_WRITE_PLUS +
            self::MODE_APPEND_PLUS +
            self::MODE_EXCLUSIVE_PLUS +
            self::MODE_CAUTIOUS_PLUS
        ,
        'write' =>
            self::MODE_READ_PLUS +
            self::MODE_WRITE +
            self::MODE_WRITE_PLUS +
            self::MODE_APPEND +
            self::MODE_APPEND_PLUS +
            self::MODE_EXCLUSIVE +
            self::MODE_EXCLUSIVE_PLUS +
            self::MODE_CAUTIOUS +
            self::MODE_CAUTIOUS_PLUS
        ,
    ];

    /**
     * @var int $mode
     */
    protected $mode;

    /**
     * AbstractResource constructor.
     *
     * @param mixed    $path           Specifies the URL that was passed to the original function.
     *                                 The URL can be broken apart with parse_url(). Note that only URLs delimited by
     *                                 :// are supported. : and :/ while technically valid URLs, are not.
     * @param string   $mode           The mode used to open the file, as detailed for fopen().
     *                                 The mode must match the regex '/^[rwaxc]\+?[bt]?$/'.
     * @param bool     $useIncludePath The optional third use_include_path parameter can be set to '1' or TRUE if you
     *                                 want to search for the file in the include_path, too.
     * @param resource $context        Stream context created using stream_context_create().
     *
     * @throws ResourceAlreadySetException
     */
    public function __construct(string $path, string $mode = 'r', bool $useIncludePath = null, $context = null)
    {
        $this->open(...func_get_args());
    }

    /**
     * AbstractResource destructor.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return fclose($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        return feof($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        return fflush($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function lock(int $operation)
    {
        return flock($this->resource, $operation);
    }

    /**
     * @param string   $path           Specifies the URL that was passed to the original function.
     *                                 The URL can be broken apart with parse_url(). Note that only URLs delimited by
     *                                 :// are supported. : and :/ while technically valid URLs, are not.
     * @param string   $mode           The mode used to open the file, as detailed for fopen().
     *                                 The mode must match the regex '/^[rwaxc]\+?[bt]?$/'.
     * @param bool     $useIncludePath The optional third use_include_path parameter can be set to '1' or TRUE if you
     *                                 want to search for the file in the include_path, too.
     * @param resource $context        Stream context created using stream_context_create().
     *
     * @return $this
     *
     * @throws ResourceAlreadySetException
     * @throws BadResourceModeException
     * @throws \Exception
     */
    public function open(string $path, string $mode = 'r', bool $useIncludePath = null, $context = null)
    {
        if ($this->resource) {
            throw new ResourceAlreadySetException();
        }

        if (!$context) {
            $context = stream_context_create();
        }

        $this->resource = fopen($path, $mode, $useIncludePath, $context);

        $this->setMode($mode);
        if ($context) {
            $this->setContext($context);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws WriteOnlyException
     */
    public function read(int $count)
    {
        if (!$this->isReadable()) {
            throw new WriteOnlyException();
        }

        return fread($this->resource, $count);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * {@inheritdoc}
     */
    public function seek(int $offset, int $whence = SEEK_SET)
    {
        return fseek($this->resource, $offset, $whence);
    }

    public function setOption(int $option, int $arg1, int $arg2 = null)
    {
        switch ($option) {
            case STREAM_OPTION_BLOCKING:
                return stream_set_blocking($this->resource, $arg1);
            case STREAM_OPTION_READ_TIMEOUT:
                return stream_set_timeout($this->resource, $arg1, $arg2);
            case STREAM_OPTION_WRITE_BUFFER:
                return stream_set_write_buffer($this->resource, $arg1);
        }
        throw new \Exception;
    }

    /**
     * {@inheritdoc}
     */
    public function stat()
    {
        return fstat($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        return ftell($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    public function truncate(int $newSize)
    {
        return ftruncate($this->resource, $newSize);
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

        if ($this->isAppendOnly()) {
            $this->seek(0, SEEK_END);
        }

        return fwrite($this->resource, $string, $length);
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return (bool)($this->mode & $this->readWriteModeMap['read']);
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return (bool)($this->mode & $this->readWriteModeMap['write']);
    }

    /**
     * {@inheritdoc}
     */
    public function isAppendOnly()
    {
        return (bool)($this->mode & self::MODE_APPEND) || (bool)($this->mode & self::MODE_APPEND_PLUS);
    }

    /**
     * {@inheritdoc}
     */
    public function getMode()
    {
        $flippedModeMap = array_flip($this->modeMap);
        $stringMode = '';

        foreach ($flippedModeMap as $modeMapInt => $modeMapString) {
            if (($this->mode & $modeMapInt) == $modeMapInt) {
                $stringMode = $modeMapString;
                break;
            }
        }
        if (($this->mode & self::MODE_WIN_BIN) == self::MODE_WIN_BIN) {
            $stringMode .= 'b';
        } elseif (($this->mode & self::MODE_WIN_TEXT) == self::MODE_WIN_TEXT) {
            $stringMode .= 't';
        }

        return $stringMode;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreamResource()
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext($context)
    {
        if (!is_resource($context)) {
            throw new \Exception;
        }

        $this->context = $context;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextOptions()
    {
        return stream_context_get_options($this->context);
    }

    /**
     * {@inheritdoc}
     */
    public function getContextParams()
    {
        return stream_context_get_params($this->context);
    }

    /**
     * @return string
     * @throws WriteOnlyException
     */
    public function getContents()
    {
        $contents = '';
        $this->seek(0);
        while (!$this->eof()) {
            $contents .= $this->read(1024);
        }

        return $contents;
    }

    /**
     * @param string $mode
     *
     * @return $this
     * @throws BadResourceModeException
     */
    protected function setMode(string $mode)
    {
        $regExp = '/^([rwaxc]\+?)([bt])?$/';
        $matched = preg_match($regExp, $mode, $matches);
        if (!$matched) {
            throw new BadResourceModeException();
        }

        $stringMode = $matches[1];
        $winMode = $matches[2] ?? null;

        if (array_key_exists($stringMode, $this->modeMap)) {
            $this->mode = $this->modeMap[$stringMode];
        } else {
            throw new BadResourceModeException();
        }

        if ($winMode !== null) {
            $this->mode += $winMode == 'b' ? self::MODE_WIN_BIN : self::MODE_WIN_TEXT;
        }

        return $this;
    }
}
