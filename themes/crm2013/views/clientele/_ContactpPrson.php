<?php
$msg  = '<div class="item">';
$msg .= $data->getHtmlLink();
$msg .= '&nbsp;&nbsp;电话：';
$msg .= $data->mobile.' / '.$data->phone;
$msg .= $data->last_time>0?'最后联系：'.Tak::timetodate($data->last_time,3):'';
$msg .= '</div>';
echo $msg;