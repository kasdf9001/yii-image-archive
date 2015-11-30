<?php
$this->breadcrumbs=array(
	'Buildings'=>array('index'),
	$model->id=>array('view','id'=>$model->idno),
	'Update',
);

$this->menu=array(
	array('label'=>'List Building', 'url'=>array('index')),
	array('label'=>'Create Building', 'url'=>array('create')),
	array('label'=>'View Building', 'url'=>array('view', 'id'=>$model->idno)),
	array('label'=>'Manage Building', 'url'=>array('admin')),
);
?>

<h1>Update Building <?php echo $model->idno; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>