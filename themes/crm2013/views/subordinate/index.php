<?php
$this->breadcrumbs = array(
    Tk::g('Subordinate') ,
);
$btns = array();
$btns[] = JHtml::link(Tk::g('Log') , array(
    'log',
    'SubAdminLog[manageid]' => '---',
) , array(
    'class' => 'btn btn-small'
));
if (Tak::checkAccess('clientele.*')) {
    $btns[] = JHtml::link(Tk::g('Clienteles') , array(
        'Clienteles',
        'SubClientele[manageid]' => '---',
    ) , array(
        'class' => 'btn btn-small'
    ));
}
if (Tak::checkAccess('order.*')) {
    $btns[] = JHtml::link(Tk::g(array('Order','Clienteles')) , array(
        'OrderClientele',
        'Profile[itemid]' => '---',
    ) , array(
        'class' => 'btn btn-small'
    ));
}
$strBtns = implode('  ', $btns);
?>
<div class="row-fluid">
  <div class="span12">
  <div class="head clearfix">
        <div class="isw-grid"></div>
        <h1><?php echo Tk::g('Subordinate') ?></h1>   
  </div>
  <div class="block-fluid clearfix">
  
  <div class="grid-view">
<table class="items table table-striped table-bordered table-condensed">
<thead>
<tr>
  <th width="200">员工</th>
  <th>操作</th>
</tr>
</thead>
<tbody>
<?php foreach ($this->users as $key => $value): ?>
<tr>
  <td><?php echo $value ?></td>
  <td>
    <?php echo strtr($strBtns, array(
        '---' => $key
    )) ?>
  </td>
</tr>
<?php
endforeach
?>
</tbody>
</table>  
</div>
  </div>
  </div>
</div>