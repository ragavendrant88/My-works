<?php
//***************************************************************//
// CSV File Generation
//***************************************************************//

// CSV Encode
define('CSV_ENC',  'sjis-win');
// FILE Ditectory
define('CSV_TMP_DIR',  './app/lib/test/');

/**
 *  SimplanMakeCsvFile Class
 *
 */
class SimplanMakeCsvFile
{

    /**
     *  CSV File Creation
     *
     *  @access public
     *  @param  string  $list     Data
     *  @param  string  $filename File name to be generated
     *  @param  string  $inhead   Header
     *  @param  string  $infoot   Footer
     *  @return array   OK:true , NG:false
     */
    function makeCsvFile($list, $filename, $inhead, $infoot = '')
    {
        // set
        $success = false;
        $file = fopen($filename, "w");
        if (!$file) {
            return $success;
        }
        // Exclusive Lock
        if(flock($file, LOCK_EX)) {
            // head
            if (is_array($inhead)) {
                fputs($file, SimplanMakeCsvFile::makeCsvString($inhead) );
            }
            // data
            foreach($list as $datas) {
                fputs($file, SimplanMakeCsvFile::makeCsvString($datas) );
            }
            //
            if (is_array($infoot)) {
                fputs($file, SimplanMakeCsvFile::makeCsvString($infoot) );
            }
            // File Close
            fclose($file);
            $success = true;
        }

        return $success;
    }

    /**
     *  Output CSV as string from array
     *
     *  @access public
     *  @param  string  $datas Data
     *  @return array   string
     */
    function makeCsvString($datas) {

        $record = "";
        $i = 0;
        foreach($datas as $v) {
            $code = mb_detect_encoding($v);
            $record .= ($i == 0) ? "" : ",";
            $record .= mb_convert_encoding("\"" . str_replace('"', '""', SimplanMakeCsvFile::unhtmlspecialchars($v)) . "\"", CSV_ENC, $code);
            $i++;
        }
        $record .= "\n";

        return $record;
    }

    /**
     *  CSV File output
     *
     *  @access public
     *  @param  string  $filename CSV File name
     *  @return array   OK:true , NG:false
     */
    function outputCsvFile($filename)
    {

        // Character Code
        $kanji_code = mb_internal_encoding();
        $success = false;

        header("Content-disposition: attachment; filename=" . basename($filename));
        header("Content-type: application/octet-stream; name=". basename($filename));
        header("Cache-Control: public");
        header("Pragma: public");

        // Specified Character code
        mb_http_output(CSV_ENC);

        // Outout
        if (readfile($filename)) {
            $success = true;
        }

        // Undo the internal character code
        mb_internal_encoding($kanji_code);

        return $success;
    }

    /**
     *  Convert the encoding of the file name
     *
     *  @access public
     *  @param  string  $pre CSV File name
     *  @return array   file name
     */
    function makeCsvFileName($pre)
    {
        $enc = mb_detect_encoding($pre);
        $pre = mb_convert_encoding($pre, CSV_ENC, $enc);
        $filename = TMP_DIR . $pre . '.csv';
        return $filename;
    }

    /**
     *  Inverse transformation of special characters converted to entities.
     *
     *  @access public
     *  @param  string  $string character
     *  @return array   string
     */
    function unhtmlspecialchars($string)
    {
        $string = str_replace('&amp;', '&', $string);
        $string = str_replace('&quot;', '"', $string);
        $string = str_replace('&#039;', '\'', $string);
        $string = str_replace('&lt;', '<', $string);
        $string = str_replace('&gt;', '>', $string);

        return $string;
    }
}
?>
