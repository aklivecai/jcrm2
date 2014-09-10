<?php
echo 
    '<div class="wBlock '.$this->htmlOptions['class'].' clearfix">
      <div class="dSpace">
        <h3>'.$this->title.'</h3>
        <span class="mChartBar" sparkType="bar" sparkBarColor="white"></span><span class="number">'.Tak::formatNumber($this->tags['allData']).'</span> </div>
      <div class="rSpace"> <span><b>今日</b> '.Tak::formatNumber($this->tags['dData']).' </span> <span><b>本月</b> '.Tak::formatNumber($this->tags['mData']).' </span> <span><b>今年</b> '.Tak::formatNumber($this->tags['yData']).' </span> </div>
    </div>
    ';