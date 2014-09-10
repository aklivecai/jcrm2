<?php
class FUtils {
    /**
     * 检测文件名字是否正确
     * @param  string $file_name 文件名字
     * @return bool            true,false
     */
    public static function checkNameInvalid($file_name) {
        if ($file_name === "") {
            return true;
        }
        if ($file_name{strlen($file_name) - 1} == ".") {
            return true;
        }
        return preg_match("/[\\/" . preg_quote("|?*\\<\":>") . "]/", $file_name);
    }
    /**
     * 创建目录
     * @param string  $dir       目录路径
     * @param integer $mode      权限
     * @param boolean $recursive 是否成功
     */
    public static function MkDirs($dir, $mode = 0777, $recursive = true) {
        if (is_null($dir) || $dir == "") {
            return false;
        }
        if (is_dir($dir) || $dir == "/") {
            return true;
        }
        if (self::MkDirs(dirname($dir) , $mode, $recursive)) {
            return mkdir($dir, $mode);
        }
        return false;
    }
    /**
     * 删除目录
     * @param  string $dirPath 目录地址
     * @return void
     */
    public static function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath.= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
    
    public static function getDataHash($data) {
        $signature = sha1($data);
        return $signature;
    }
    public static function getPathBySplitStr($str) {
        $parts = str_split(substr($str, 0, 4) , 2);
        $path = implode("/", $parts);
        $path = $path . "/" . $str;
        return $path;
    }
    public static function getSuffix($extension) {
        $ext = strtolower($extension);
        $result = null;
        switch ($ext) {
            case 'txt':
                $result = 10;
            break;
            case 'html':
            case 'htm':
            case 'shtml':
                $result = 30;
            break;
            case 'doc':
            case 'docx':
            case 'rtf':
                $result = 50;
            break;
            case 'xls':
            case 'xlsx':
                $result = 60;
            break;
            case 'ppt':
            case 'pptx':
                $result = 70;
            break;
            case 'pdf':
                $result = 90;
            break;
            case 'bmp':
            case 'jpe':
            case 'jpg':
            case 'jpge':
            case 'ico':
            case 'png':
                $result = 100;
            break;
            case 'zip':
            case 'rar':
            case '7z':
            case 'gz':
            case 'tar':
            case 'bz2':
                $result = 200;
            break;
            case 'au':
            case 'snd':
            case 'mid':
            case 'rmi':
            case 'mp3':
            case 'aif':
            case 'aifc':
            case 'aiff':
            case 'm3u':
            case 'ra':
            case 'ram':
            case 'wav':
            case 'ape':
            case 'wma':
            case 'wvx':
                $result = 300;
            break;
            case 'mp4':
            case 'qt':
            case 'mov':
            case '3gp':
            case 'wmv':
            case 'avi':
            case 'mp2':
            case 'mpa':
            case 'mpe':
            case 'mpeg':
            case 'mpg':
            case 'mpv2':
            case 'mov':
            case 'qt':
            case 'lsf':
            case 'lsx':
            case 'asf':
            case 'asr':
            case 'asx':
            case 'avi':
            case 'movie':
            case 'rmvb':
            case 'rm':
            case 'viv':
            case 'vivo':
                $result = 350;
            break;
            default:
                $result = 400;
            break;
        }
        return $result;
    }
    /**
     * 获取文件格式
     * @param  string $filename 文件名字
     * @param  string $path     文件完整路径
     * @return string           文件格式
     */
    public static function mimeContentType($filename, $path = null) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            
            'bmp' => 'image/bmp',
            'cod' => 'image/cis-cod',
            'gif' => 'image/gif',
            'ief' => 'image/ief',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'jfif' => 'image/pipeg',
            'svg' => 'image/svg+xml',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'ras' => 'image/x-cmu-raster',
            'cmx' => 'image/x-cmx',
            'ico' => 'image/x-icon',
            'png' => 'image/png',
            'pnm' => 'image/x-portable-anymap',
            'pbm' => 'image/x-portable-bitmap',
            'pgm' => 'image/x-portable-graymap',
            'ppm' => 'image/x-portable-pixmap',
            'rgb' => 'image/x-rgb',
            'xbm' => 'image/x-xbitmap',
            'xpm' => 'image/x-xpixmap',
            'xwd' => 'image/x-xwindowdump',
            'svgz' => 'image/svg+xml',
            
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            
            'au' => 'audio/basic',
            'snd' => 'audio/basic',
            'mid' => 'audio/mid',
            'rmi' => 'audio/mid',
            'mp3' => 'audio/mpeg',
            'aif' => 'audio/x-aiff',
            'aifc' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff',
            'm3u' => 'audio/x-mpegurl',
            'ra' => 'audio/x-pn-realaudio',
            'ram' => 'audio/x-pn-realaudio',
            'wav' => 'audio/x-wav',
            'ape' => 'audio/x-monkeys-audio',
            'wma' => 'audio/x-ms-wma',
            'wvx' => 'audio/x-ms-wvx',
            
            'mp4' => 'video/mp4',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            '3gp' => 'video/3gpp',
            'wmv' => 'video/x-ms-wmv',
            'avi' => 'video/x-msvideo',
            'mp2' => 'video/mpeg',
            'mpa' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpv2' => 'video/mpeg',
            'mov' => 'video/quicktime',
            'qt' => 'video/quicktime',
            'lsf' => 'video/x-la-asf',
            'lsx' => 'video/x-la-asf',
            'asf' => 'video/x-ms-asf',
            'asr' => 'video/x-ms-asf',
            'asx' => 'video/x-ms-asf',
            'avi' => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            'rmvb' => 'video/vnd.rn-realvideo',
            'rm' => 'video/vnd.rn-realvideo',
            'viv' => 'video/vnd.vivo',
            'vivo' => 'video/vnd.vivo',
            
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            
            'doc' => 'application/msword',
            'docx' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/msexcel',
            'xlsx' => 'application/msexcel',
            'ppt' => 'application/mspowerpoint',
            'pptx' => 'application/mspowerpoint',
            
            'pdf' => 'application/pdf',
            
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            'apk' => 'application/vnd.android.package-archive',
        );
        $ext = explode('.', $filename);
        $ext = strtolower(array_pop($ext));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open') && !empty($path)) {
            $fileInfo = finfo_open(FILEINFO_MIME);
            $mimeType = finfo_file($fileInfo, $path);
            finfo_close($fileInfo);
            return $mimeType;
        } elseif (function_exists('mime_content_type') && ($result = mime_content_type($file)) !== false) {
            return $result;
        } else {
            return 'application/octet-stream';
        }
    }
    /**
     * 输出文件内容到浏览器
     * @param  string  $filePath      文件路径
     * @param  string  $contentType   文件类型
     * @param  string  $fileName      文件名字
     * @param  boolean $forceDownload 是否下载
     * @return void
     */
    public static function outContent($filePath, $contentType, $fileName, $forceDownload = true) {
        $options = array();
        $options['saveName'] = $fileName;
        $options['mimeType'] = $contentType;
        $options['terminate'] = false;
        $size = self::size($filePath);
        Header("Content-type: $contentType");
        Header("Cache-Control: public");
        Header("Content-length: " . $size);
        $encodedFileName = urlencode($fileName);
        $encodedFileName = str_replace("+", "%20", $encodedFileName);
        $ua = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : NULL;
        if ($forceDownload) {
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encodedFileName . '"');
            } elseif (preg_match("/Firefox\/8.0/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
            }
        }
        if (isset($_SERVER['HTTP_RANGE']) && ($_SERVER['HTTP_RANGE'] != "") && preg_match("/^bytes=([0-9]+)-/i", $_SERVER['HTTP_RANGE'], $match) && ($match[1] < $size)) {
            $range = $match[1];
            header("HTTP/1.1 206 Partial Content");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s", self::mtime($filePath)) . " GMT");
            header("Accept-Ranges: bytes");
            $rangeSize = ($size - $range) > 0 ? ($size - $range) : 0;
            header("Content-Length:" . $rangeSize);
            header("Content-Range: bytes " . $range . '-' . ($size - 1) . "/" . $size);
        } else {
            header("Content-Length: $size");
            header("Accept-Ranges: bytes");
            $range = 0;
            header("Content-Range: bytes " . $range . '-' . ($size - 1) . "/" . $size);
        }
        return self::render_contents($filePath, "", 0);
    }
    /**
     * 获取文件内容
     */
    public static function get_contents($file, $type = '', $resumepos = 0) {
        if ($resumepos == 0) {
            return @file_get_contents($file);
        }
        
        $fp = fopen($file, "rb");
        fseek($fp, $resumepos);
        $contents = "";
        while (!feof($fp)) {
            set_time_limit(0);
            $contents = $contents . fread($fp, 1024 * 8);
        }
        fclose($fp);
        return $contents;
    }
    /**
     * 输出文件内容
     * @param  string  $file           文件路径
     */
    public static function render_contents($file, $type = '', $resumePosition = 0) {
        if (file_exists($file) == FALSE) return FALSE;
        $fp = fopen($file, "rb");
        set_time_limit(0);
        $dstStream = fopen('php://output', 'wb');
        $chunkSize = 4096;
        $offset = $resumePosition;
        $file_size = self::size($file);
        while (!feof($fp) && $offset < $file_size) {
            $last_size = $file_size - $offset;
            if ($chunkSize > $last_size && $last_size > 0) {
                $chunkSize = $last_size;
            }
            $offset+= stream_copy_to_stream($fp, $dstStream, $chunkSize, $offset);
        }
        fclose($dstStream);
        fclose($fp);
        return true;
    }
    /**
     * 文件时间
     * @param  string $file 文件路径
     * @return [type]       [description]
     */
    public static function mtime($file) {
        return @filemtime($file);
    }
    /**
     * 是否存在文件
     * @param  string $file 文件路径
     * @return [type]       [description]
     */
    public static function exists($file) {
        return @file_exists($file);
    }
    
    public static function is_file($file) {
        return @is_file($file);
    }
    /**
     * 是否是目录
     * @param  [type]  $path [description]
     * @return boolean       [description]
     */
    public static function is_dir($path) {
        return @is_dir($path);
    }
    /**
     * 获取文件大小
     * @param  string $file [description]
     * @return [type]       [description]
     */
    public static function size($file) {
        if (self::exists($file) === false) {
            return false;
        }
        return @filesize($file);
    }
}
