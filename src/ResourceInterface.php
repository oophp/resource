<?php

namespace OOPHP\Resource;

interface ResourceInterface
{
    /**
     * Open for reading only; place the file pointer at the beginning of the file.
     */
    const MODE_READ = 1;

    /**
     * Open for reading and writing; place the file pointer at the beginning of the file.
     */
    const MODE_READ_PLUS = 2;

    /**
     * Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length.
     * If the file does not exist, attempt to create it.
     */
    const MODE_WRITE = 4;

    /**
     * Open for reading and writing; place the file pointer at the beginning of the file and truncate the file to zero
     * length. If the file does not exist, attempt to create it.
     */
    const MODE_WRITE_PLUS = 8;

    /**
     * Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to
     * create it. In this mode, fseek() has no effect, writes are always appended.
     */
    const MODE_APPEND = 16;

    /**
     * Open for reading and writing; place the file pointer at the end of the file. If the file does not exist, attempt
     * to create it. In this mode, fseek() only affects the reading position, writes are always appended.
     */
    const MODE_APPEND_PLUS = 32;

    /**
     * Create and open for writing only; place the file pointer at the beginning of the file. If the file already
     * exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING. If the file
     * does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying
     * open(2) system call.
     */
    const MODE_EXCLUSIVE = 64;

    /**
     * Create and open for reading and writing; otherwise it has the same behavior as 'x'.
     */
    const MODE_EXCLUSIVE_PLUS = 128;

    /**
     * Open the file for writing only. If the file does not exist, it is created. If it exists, it is neither truncated
     * (as opposed to 'w'), nor the call to this function fails (as is the case with 'x'). The file pointer is
     * positioned on the beginning of the file. This may be useful if it's desired to get an advisory lock (see
     * flock()) before attempting to modify the file,  as using 'w' could truncate the file before the lock was
     * obtained (if truncation is desired, ftruncate() can be used after the lock is requested).
     */
    const MODE_CAUTIOUS = 256;

    /**
     * Open the file for reading and writing; otherwise it has the same behavior as 'c'.
     */
    const MODE_CAUTIOUS_PLUS = 512;

    /**
     * Windows offers a text-mode translation flag ('t') which will transparently translate \n to \r\n when working
     * with the file.
     */
    const MODE_WIN_TEXT = 1024;

    /**
     * For portability, it is strongly recommended that you always use the 'b' flag when opening files with fopen().
     */
    const MODE_WIN_BIN = 2048;

    /**
     * ResourceInterface constructor.
     *
     * @param mixed $path Specifies the URL that was passed to the original function. The URL can be broken apart with
     *                    parse_url(). Note that only URLs delimited by :// are supported. : and :/ while technically
     *                    valid URLs, are not.
     */
    public function __construct(string $path);

    public function __destruct();

    public function __toString();

    /**
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function close();

    /**
     * @return bool Returns TRUE if the file pointer is at EOF or an error occurs (including socket timeout); otherwise
     *              returns FALSE.
     */
    public function eof();

    /**
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function flush();

    /**
     * @param int $operation  One of the following:
     *                        - LOCK_SH to acquire a shared lock (reader).
     *                        - LOCK_EX to acquire an exclusive lock (writer).
     *                        - LOCK_UN to release a lock (shared or exclusive).
     *                        It is also possible to add LOCK_NB as a bitmask to one of the above operations if you
     *                        don't want flock() to block while locking.
     *
     * @return mixed
     */
    public function lock(int $operation);

    /**
     * @param mixed  $path       Specifies the URL that was passed to the original function.
     *                           The URL can be broken apart with parse_url(). Note that only URLs delimited by :// are
     *                           supported. : and :/ while technically valid URLs, are not.
     *
     * @return $this
     */
    public function open(string $path);

    /**
     * @param int $count How many bytes of data from the current position should be returned.
     *
     * @return string Returns the read string or FALSE on failure.
     */
    public function read(int $count);

    /**
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function rewind();

    /**
     * @param int $offset The stream offset to seek to.
     * @param int $whence Possible values:
     *                    - SEEK_SET - Set position equal to offset bytes.
     *                    - SEEK_CUR - Set position to current location plus offset.
     *                    - SEEK_END - Set position to end-of-file plus offset.
     *
     * @return bool Return TRUE if the position was updated, FALSE otherwise.
     */
    public function seek(int $offset, int $whence = SEEK_SET);

    /**
     * @param int      $option One of:
     *                         - STREAM_OPTION_BLOCKING (The method was called in response to stream_set_blocking())
     *                         - STREAM_OPTION_READ_TIMEOUT (The method was called in response to stream_set_timeout())
     *                         - STREAM_OPTION_WRITE_BUFFER (The method was called in response to
     *                         stream_set_write_buffer())
     * @param int      $arg1   If option is
     *                         - STREAM_OPTION_BLOCKING: requested blocking mode (1 meaning block 0 not blocking).
     *                         - STREAM_OPTION_READ_TIMEOUT: the timeout in seconds.
     *                         - STREAM_OPTION_WRITE_BUFFER: buffer mode (STREAM_BUFFER_NONE or STREAM_BUFFER_FULL).
     * @param int|null $arg2   If option is
     *                         - STREAM_OPTION_BLOCKING: This option is not set.
     *                         - STREAM_OPTION_READ_TIMEOUT: the timeout in microseconds.
     *                         - STREAM_OPTION_WRITE_BUFFER: the requested buffer size.
     *
     * @return bool Returns TRUE on success or FALSE on failure. If option is not implemented, FALSE should be returned.
     */
    public function setOption(int $option, int $arg1, int $arg2 = null);

    /**
     * @return array Returns an array with the statistics of the file; the format of the array is described in detail
     *               on the stat() manual page.
     */
    public function stat();

    /**
     * @return int Should return the current position of the stream.
     */
    public function tell();

    /**
     * @param int $newSize The size to truncate to.
     *                     If size is larger than the file then the file is extended with null bytes.
     *                     If size is smaller than the file then the file is truncated to that size.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function truncate(int $newSize);

    /**
     * @param string $string
     * @param int    $length
     *
     * @return bool|int Returns the number of bytes written, or FALSE on error.
     */
    public function write(string $string, int $length = null);

    /**
     * @return bool
     */
    public function isReadable();

    /**
     * @return bool
     */
    public function isWritable();

    /**
     * @return bool
     */
    public function isAppendOnly();

    /**
     * @return string
     */
    public function getMode();

    /**
     * @return resource
     */
    public function getStreamResource();

    /**
     * @param $context
     *
     * @return $this
     */
    public function setContext($context);

    /**
     * @return resource
     */
    public function getContext();

    /**
     * @return array
     */
    public function getContextOptions();

    /**
     * @return array
     */
    public function getContextParams();
}
