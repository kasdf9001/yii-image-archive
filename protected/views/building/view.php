<?php
$this->breadcrumbs=array(
	'Buildings'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Building', 'url'=>array('index')),
	array('label'=>'Create Building', 'url'=>array('create')),
	array('label'=>'Update Building', 'url'=>array('update', 'id'=>$model->idno)),
	array('label'=>'Delete Building', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idno),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Building', 'url'=>array('admin')),
);
?>

<h1>View Building #<?php echo $model->idno; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'cdno',
		'idno',
		'saari',
		'rakennus',
	),
)); ?>
