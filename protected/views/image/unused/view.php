<?php
$this->breadcrumbs=array(
	'Images'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Image', 'url'=>array('index')),
	array('label'=>'Create Image', 'url'=>array('create')),
	array('label'=>'Update Image', 'url'=>array('update', 'id'=>$model->imageid)),
	array('label'=>'Delete Image', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->imageid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Image', 'url'=>array('admin')),
);
?>

<h1>View Image #<?php echo $model->imageid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'cd',
		'id',
		'pvm',
		'valokuvaaja',
		'julkaisuvapaa',
		'valokuva',
		'maalaus',
		'piirustus',
		'ulkokuva',
		'sisakuva',
		'ilmakuva',
		'historiallinen',
		'tyomaa',
		'esittely',
		'ihmisia',
		'linnoituslaitteet',
		'kuvateksti',
		'diateksti',
		'kartta',
		'tiedostotyyppi',
		'aikavarma',
		'imageid',
	),
)); ?>


<?php $this->widget('zii.widgets.CListView', 
	array(
		'dataProvider'=>$buildingDataProvider,
		'itemView'=>'/building/_view',
	)
);

?>
