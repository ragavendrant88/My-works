<? php
    /**
     * Byte Convert
     * Convert file size into byte, kb, mb, gb
     * @param int $size
     * @return string
     */
    function byte_convert($size = 0)
    {
        // skip size zero
        if ($size == 0) {
            return;
        }
        // size smaller then 1kb
        if ($size < 1024) {
            return $size . 'Byte';
        } 
        // size smaller then 1mb
        if ($size < 1048576) {
            return sprintf("%4.2fKB", $size/1024);
        }
        // size smaller then 1gb
        if ($size < 1073741824) {
            return sprintf("%4.2fMB", $size/1048576);
        }
        // size smaller then 1tb
        if ($size < 1099511627776) {
            return sprintf("%4.2fGB", $size/1073741824);
        }
        return;
    }
