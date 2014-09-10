<?php
/* @var $this EventsController */
/* @var $model Events */

$this->breadcrumbs=array(
	Tk::g($model->sName)=>array('admin'),
	Tk::g('Admin'),
);
$items = Tak::getListMenu();
?>
<div class="row-fluid">
  <div class="head clearfix">
    <div class="isw-calendar"></div>
    <h1><?php echo $model->sName?></h1>
  </div>
  <div class="block-fluid">
    <div id="calendar" class="fc"></div>
  </div>
</div>
