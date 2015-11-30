<div class="metadatabox" id="controlbox">

<?php 
echo CHtml::ajaxLink(
	// text, the link body
	'Muokkaa kuvan tietoja',
	// url
	array(),
	// ajaxOptions
	array(
		'success'=>'function() { $("#metadataboxedit").show(); $("#metadatabox").hide(); }',
	),
	// htmlOptions
	array(
		'class'=>'linkbutton',
	)
);

?>

<br />
<br />
<?php 
/*
 * Quite large system to show / hide the add to basket and remove from basket buttons.
 * The display setting is saved in an array, generated below. 
 */

if(in_array($dataProvider->imageid,Yii::app()->user->getState('basketContents')))
{
	$showButton = array(
		'hideAdd'=>'display:none;',
		'hideRemove'=>'display:block;'
	);
} 
else 
{
	$showButton = array(
		'hideAdd'=>'display:block;',
		'hideRemove'=>'display:none;'
	);
} 
?>

	<div id="isinbasket" style="<?php echo $showButton['hideRemove']; ?>">
	<?php 
		echo CHtml::ajaxLink(
			'Poista kuvien muokkaustilasta',
			array(
				'image/removeFromBasket',
				'imageid'=>$dataProvider->imageid,
			),
			// array AjaxOptions
			array(
				'success'=>'function() { $("#notinbasket").show(); $("#isinbasket").hide(); }'
			),
			// array htmlOptions 
			array(
				'class'=>'linkbutton'
			) 
		);
	?>
	</div>
	
	<div id="notinbasket" style="<?php echo $showButton['hideAdd']; ?>">
	<?php 
		echo CHtml::ajaxLink(
			'Lisää kuvien muokkaustilaan',
			array(
				'image/addToBasket',
				'imageid'=>$dataProvider->imageid,
			),
			// array AjaxOptions
			array(
				'success'=>'function() { $("#notinbasket").hide(); $("#isinbasket").show(); }'
			),
			// array htmlOptions 
			array(
				'class'=>'linkbutton'
			) 
		);
	?>
	</div>
<br />
<br />
	
<?php 
	echo CHtml::link(
		// text, the link body
		'Poista kuva kuva-arkistosta',
		// url
		array('image/delete','imageid'=>$dataProvider->imageid),
		// htmlOptions
		array(
			'class'=>'linkbutton',
			'confirm'=>'Kuva poistetaan arkistosta!'
		)
	);
?>

<br />
<br />

<!-- "shape_rotate_clockwise.png" icon by Mark James; http://www.famfamfam.com/lab/icons/silk/ -->
<?php
	echo CHtml::link(
		// text, the link body
		'<img src=\'' . Yii::app()->baseUrl . '/images/shape_rotate_clockwise.png\'/> Käännä myötäpäivään',
		// url
		array('image/rotate','imageid'=>$dataProvider->imageid,'direction'=>'clockwise'),
		// htmlOptions
		array(
			'class'=>'linkbutton',
			'confirm'=>'Käännetäänkö kuva? Kuvan kääntämisessä menee hetki.'
		)
	);
?>

<!-- "shape_rotate_anticlockwise.png" icon by Mark James; http://www.famfamfam.com/lab/icons/silk/ -->
<?php 
	echo CHtml::link(
		// text, the link body
		'<img src=\'' . Yii::app()->baseUrl . '/images/shape_rotate_anticlockwise.png\' /> Käännä vastapäivään',
		// url
		array('image/rotate','imageid'=>$dataProvider->imageid,'direction'=>'counterclockwise'),
		// htmlOptions
		array(
			'class'=>'linkbutton',
			'confirm'=>'Käännetäänkö kuva? Kuvan kääntämisessä menee hetki.'
		)
	);
?>

</div>