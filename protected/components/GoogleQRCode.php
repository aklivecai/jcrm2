<?php
/**
 * Google QR Code make
 * @Author: Rogee<rogeecn@gmail.com>
 * Date: 12-12-17 下午1:36
 * Version 1.0.0
 */

class GoogleQRCode extends CWidget {
    public $htmlOptions;
    private $_htmlOptions = array(
        'title' => '二维码',
        'alt' => '二维码',
    );
    public $size = 150;
    public $content = 'Hello World';
    public $level = 'L';
    public $margin = '1';
    
    private $_width;
    private $_height;
    public function init() {
        $this->size = intval($this->size);
        $_content = $this->content;
        if (Tak::isUrl($_content)) {
            $_content = urlencode($_content);
        }
        $data = array(
            'cht' => 'qr',
            'chs' => $this->size . 'x' . $this->size,
            'chl' => $_content,
            'chld' => $this->level . '|' . $this->margin,
        );
        
        $src = sprintf("https://chart.googleapis.com/chart?%s", http_build_query($data));
        $src = sprintf("https://127.0.0.1/chart?%s", http_build_query($data));
        
        $data = array(
            'bg' => 'f3f3f3',
            'fg' => 'ff0000',
            'gc' => '222222',
            'el' => 'l',
            'm' => '2',
            'w' => $this->size,
            'text' => $_content,
        );
        
        $src = sprintf("http://qr.liantu.com/api.php?%s", http_build_query($data));
        
        $k = '';
        if (!Tak::isGuest()) {
            $k = Tak::getFormid();
        } else {
            isset(Yii::app()->request->cookies['fid']) && $k = Yii::app()->request->cookies['fid']->value;
            if (!$k && strpos(Yii::app()->user->returnUrl, 'fid')) {
                $parts = parse_url(urldecode(Yii::app()->user->returnUrl));
                parse_str($parts['query'], $query);
                isset($query['fid']) && $k = $query['fid'];
            }
        }
        $src = sprintf("http://i.9juren.com/_ak/code.php?fid=%s&%s", $k, md5($_content));
        $src = 'http://127.0.0.1/';
        
        $_htmlOptions = array(
            'src' => $src,
            'width' => $this->size,
            'height' => $this->size,
        );
        $this->htmlOptions = array_merge($this->_htmlOptions, (array)$this->htmlOptions, $_htmlOptions);
    }
    public function run() {
        echo CHtml::tag('img', $this->htmlOptions);
    }
}
