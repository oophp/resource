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
     * @param mixed  $source Certain resource types might accept exiting resources instead of a string
     * @param string $mode   A mode string matching regexp '/^[rwaxc]\+?[bt]?$/'
     *
     * @return $this
     */
    public function open($source, string $mode);

    /**
     * @param int $length
     *
     * @return string
     */
    public function read(int $length);

    /**
     * @return bool
     */
    public function rewind();

    /**
     * @param int $offset
     * @param int $whence
     *
     * @return int Upon success, returns 0; otherwise, returns -1.
     */
    public function seek(int $offset, int $whence = SEEK_SET);

    /**
     * @param string $string
     * @param int    $length
     *
     * @return bool|int Returns the number of bytes written, or FALSE on error.
     */
    public function write(string $string, int $length = null);

    /**
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function close();

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
}
