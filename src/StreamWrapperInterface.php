<?php

namespace OOPHP\Resource;

interface StreamWrapperInterface
{
    public function __construct();

    public function __destruct();

    public function stream_close();

    public function stream_eof();

    public function stream_flush();

    public function stream_lock(int $operation);

    public function stream_open(string $path, string $mode, int $options, string &$openedPath);

    public function stream_read(int $count);

    public function stream_seek(int $offset, int $whence = SEEK_SET);

    public function stream_set_option(int $option, int $arg1, int $arg2 = null);

    public function stream_stat();

    public function stream_tell();

    public function stream_truncate(int $newSize);

    public function stream_write(string $data);
}
