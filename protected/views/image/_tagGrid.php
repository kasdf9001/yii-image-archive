<?php 

$model=new Image;

/*
 * additional html options for the activeRadioButtonList 
 * method for CHtml.
 * 'separator' = "string, specifies the string that separates the generated radio buttons."
 * 'style' = a CSS style for the individual radio button labels (as in; not for the whole radio list).
 */
$htmlOptions = array(
	'separator'=>'',
	'labelOptions'=>array(
		'style'=>'font-weight:normal',
	)
);

/*
 * states which are the tag values, such as 'julkaisuvapaa'
 */
$tags=Image::model()->tags();

	/**
	 * Prints all the radio button list -> radio labels -> list label -systems
	 * Nested in a table.
	 */
	echo("<table class=\"tagGrid\" id=\"tagGrid\"><tr><td>");
	foreach($tags as $tag)
	{
		if($tag == 'aikavarma') continue;
		echo("<div class='row'>");
		if($styleContext=="search")
		{
			echo CHtml::activeLabel($model,$tag,array('class'=>'properlabel')); 	// label for the radio button list
			echo CHtml::activeRadioButtonList(
				$model,								// the data model, from CModel
				$tag,								// attribute, in this case, from the array above
				$model->getTypeOptions(),			// data for the attributes
				$htmlOptions						// htmlOptions, as specified above in the array
			); 
		}
		if($styleContext=="metadatabox")
		{
			if($dataProvider->$tag=='k')
			{
				echo('<b>');
				echo CHtml::activeLabel($model,$tag);
				echo('</b>');
			}
			else 
			{
				echo CHtml::activeLabel($model,$tag,array('style'=>'color:#888888;'));
			}
		}
		if($styleContext=="metadataboxedit")
		{
			
			if($dataProvider->$tag=='k')
			{
				echo CHtml::activeCheckBox($model,$tag,array('checked'=>'checked'));
			}
			else 
			{
				echo CHtml::activeCheckBox($model,$tag);
			}
			echo CHtml::activeLabel($model,$tag);
			
		}
		
		echo("</div>");
		
		if ($tag == 'linnoituslaitteet' || $tag == 'historiallinen')
			// sets when the page should be changed. 
			echo("</td><td style=\"vertical-align:top\">");
		
	}
	echo("</td></tr></table>");

?>	