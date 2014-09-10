<?php
class JHtml extends CHtml {
    public static function tag($tag, $content = false, $htmlOptions = array() , $closeTag = true) {
        $html = parent::tag($tag, $htmlOptions, $content, $closeTag);
        return $html;
    }
    public static function phoneto($content = false, $htmlOptions = array()) {
        $url = 'tel:' . $content;
        $html = self::link($content, $url, $htmlOptions);
        return $html;
    }
}
