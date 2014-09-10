<?php
/* @var $this ClienteleController */
/* @var $model Clientele */

$this->breadcrumbs=array(
    Tk::g($this->modelName)=>array('admin'),
    $model->title,
);
?>
<div class="block-fluid users">              
<?php if(count($data)>0):?>
		<?php foreach ($data as $key => $value) :?>
                                <div class="item clearfix">
                                    <div class="info">
                                        <strong>「
                                        <?php echo Jurisdiction::getTypeName($value['type']);?>
                                        </strong>
                                        <?php
                                            echo $value['description'];
                                        ?>                                                            
                                    </div>                                
                                </div>
		  <?php endforeach ?>
<?php else:?>
    <div class="item clearfix">
        <?php echo '还没有分配!';?>
    </div>
<?php endif ?>
</div>