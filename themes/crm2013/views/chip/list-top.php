<?php 

$_tags = array();
$temp = array(
      'label'=>Tak::getDataView($model->getLinkName()),
           'url'=> '',
           'disabled'=>true,
           'icon' =>'ok',
      );
foreach ($tags as $k1 => $v1) {
  if ($k1=='Next') {
    $_tags[] = '----';
    $_tags[] = $temp;
    $_tags[] = '----';
    $temp = false;
  } 


  if (is_array($v1)) {   
    foreach ($v1 as $key => $value) {
      $label = '';
      if (is_string($model->linkName)) {
        $label = Tak::getDataView($value[$model->linkName]);
      }elseif(is_array($model->linkName)){
          $t = array();
          
          foreach ($model->linkName as  $v) {
             $t[]= Tak::getDataView($value[$v]);
          }
          $label = join('-',$t);
      }
      $_tags[] = array(
        'label'=>$label,
        'url'=> $model->getLink(Tak::setSId($key),$view),
        'icon' =>'chevron-right',
      );
    }
  }
}
if ($temp) {
    $_tags[] = '----';
    $_tags[] = $temp;
    $_tags[] = '----';
}
   $this->widget('bootstrap.widgets.TbMenu', array(
    'id' =>'gettop-'.$model->primaryKey,
    'items'=> $_tags,
    'htmlOptions' =>array('class'=>'dropdown-menu load-over')
    )
); 
?>