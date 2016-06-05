<?php

namespace OOPHP\Resource;

use OOPHP\Resource\Exception\BadResourceModeException;

abstract class AbstractResource implements ResourceInterface
{
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
     * @param string|resource $source
     * @param string          $mode
     */
    public function __construct($source, string $mode)
    {
        $this->open($source, $mode);
        $this->setMode($mode);
    }

    /**
     * AbstractResource destructor.
     */
    public function __destruct()
    {
        $this->close();
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
