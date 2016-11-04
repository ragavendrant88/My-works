<? php  
   /**
    * Unzip the file to the destination dir
    *
    * @param   array    $param
    * @param   file     $file
    *
    * @return  boolean     Succesful or not
    */
    function getZipFileName (&$param, $file) {
        $success = false;
        $fileInfo = array();
        global $ex_dir;
        $dir = 'temp/';
        $zip_size = 1000000000;

        // Allowed Mime types
        $zip_mime = array(
            'application/x-compress',
            'application/x-lha-compressed',
            'application/x-zip-compressed',
            'application/zip',
            'multipart/x-zip',
            'application/zip',
            'application/octet-stream',
            'application/x-stuffit'
        );
        if (in_array($file['type'], $zip_mime)) {

            if ($file['size'] <= $zip_size) {

                $rand_num = sprintf("%05d", intval(rand(0, 10000)));
                $now_sec = date('U');
                if(strpos($file['name'], '-') === true) {
                    $tmp_filename = explode($fn, "-");
                    $file['name'] = $tmp_filename[0];
                    $local_name   = $file['name'];
                    $extension    = explode('.',$local_name);
                } else {
                    $local_name = $file['name'];
                    $extension  = explode('.',$local_name);
                }
                $filename = $rand_num.'_'.$now_sec.'.'.$extension[1];
                // move uploaded file from temp to original dir
                move_uploaded_file($file['tmp_name'], $dir.$filename);
                chmod($dir.$filename, 0777);
                // Extract from ZIP
                $zip_file = $dir.$filename;
                $zip = new ZipArchive;
                if ($zip->open($zip_file) === TRUE) {
                    $count = 0;
                    $flg = false;
                    for ($j = 0; $j < $zip->numFiles; $j++) {
                        $tmp = t_explode($zip->getNameIndex($j), "/");
                        $fn  = (isset($tmp[1]) && !is_empty($tmp[1])) ? $tmp[1] : $zip->getNameIndex($j);
                        $flg = (isset($tmp[1]) && !is_empty($tmp[1])) ? true    : false;
                        if($flg === true) {
                            $ex = explode($fn, ".");
                            if (!isset($ex[0]) || !isset($ex[1]) || $ex[1] != "pdf") {
                            return $success;
                            }
                        }
                        $path = explode('/', $fn);
                        if (count($path) != 1) {
                            if (empty($path[count($path) - 1])) {
                                continue;
                            }
                            $tmp = array();
                            for ($i = 0; $i<2; $i++) {
                                if ($i == count($path) - 1) {
                                    $fn = $path[$i];
                                } else {
                                    $tmp[] = $path[$i];
                                }
                            }
                            $ex_dir = implode('/', $tmp).'/';
                        }
                        $tmp = explode('-', $fn);
                        // extract
                        $target =  "target_dir/";
                        $zip->extractTo( $target);
                        $zip->close();
                    }
                } else {
                    return $success;
                }
                // Upload completed
                return $success= true;
            } else {
                return $success;
            }
        } else {
            return $success;
        }
    }
