<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Tk::g(\$model->sName)=>array('admin'),
	Tk::g('Create'),
);\n";
?>

?>
<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
