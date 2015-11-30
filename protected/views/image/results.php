<?php

Yii::app()->user->setReturnUrl(array('image/results'));

/*
 * Script which is used to make XMLHttpRequests to refresh the contents on the metadatabox.
 * Is triggered by the links defined in onmouseover at the _thumbview, as rendered on this results page
 */
$scripturl = CHtml::normalizeUrl(array('image/ajaxupdateview'));
$refreshScript = "function refreshmetadatabox(x) {
	$('#metadataboxcontainer').load('" . $scripturl . "&id=' + x);
}";
Yii::app()->clientScript->registerScript('refreshscript',$refreshScript,CClientScript::POS_END);


/*
 * sets the currentPage so that it works, even when Pagination doesn't set it on the first page
 */
if(isset($_GET['Image_page']))
{
	$currentPage =  $_GET['Image_page'] - 1;
}
else
{
	$currentPage = 0;
}
?>

<div style="overflow:auto;">
	<?php 
	$this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'ajaxUpdate'=>false,
		'itemView'=>'_thumbview',
		'itemsCssClass'=>'resultsholder',
		'viewData'=>array(
			'currentPage'=>$currentPage,
		),
		'pager'=>array(
	
		),
		'summaryText'=>'Tulokset {start} - {end}, yhteensä {count}.',
		'template'=>'{items}{summary}'	
	));
	?>


	<div id="metadataboxcontainer">
		<?php 
			// This is only processed once, when the results page is loaded for the first time
			// the actual _metadatabox mouseover renderings are processed by the _thumbview
			if(isset($dataProvider->data[0]))
			{
				$this->renderPartial('_metadatabox',array('dataProvider'=>$dataProvider->data[0]));
			}
			else
			{
				echo("Ei hakutuloksia."); // FIXME FIXME
			}
		?>
	</div>

</div>		

<?php 
$cssfile = Yii::app()->baseUrl . '/css/pager.css';
$this->widget(
	'CLinkPager',
	array(
		'pages'=>$dataProvider->pagination,
		'cssFile'=>$cssfile,
		'header'=>false,
		'prevPageLabel'=>'< edellinen',
		'nextPageLabel'=>'seuraava >',	
	)
);
?>