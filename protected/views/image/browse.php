<?php
// jQuery registered. 
Yii::app()->clientScript->registerCoreScript('jquery');

Yii::app()->user->setReturnUrl(array('image/browse','browseOffset'=>$_GET['browseOffset']));

?>

<div id="browseImageRoll">


<?php
/*
 * This is required because of how the data provider works.
 * The rollDataProvider needs to be centered so that it has results
 * on both sides of the currently viewed item. However, if the viewed item
 * is near the start, the rollDataProvider must be centered in a different
 * way, so that it has fewer items on it's left side. 
 */
if($browseOffset < 4)
{
	if (!isset($rollDataProvider->data[$browseOffset])) { $this->redirect(array('image/results')); }
	$data = $rollDataProvider->data[$browseOffset];
}
else
{
	if (!isset($rollDataProvider->data[4])) { $this->redirect(array('image/results')); }
	$data = $rollDataProvider->data[4];
}	

// Renders a CListView which has the _thumbviewBrowse partial view, shows the
// items in the rollDataProvider.
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$rollDataProvider,
	'ajaxUpdate'=>false,
	'itemView'=>'_thumbviewBrowse',
	'viewData'=>array(
		'browseOffset'=>$browseOffset,
		'displayedImageId'=>$data->imageid,
	),
	'template'=>'{items}',
));

?>
</div>

	<div class="browseimage">
		<?php
		
		if (isset($_GET['rotated']))
		{
			switch($_GET['rotated'])
			{
				case ('true'):
					echo ('<p>Kuva on nyt käännetty. Kuva ei välttämättä näy käännettynä selaimessa. </br>
					Käännetyn kuvan näkee päivittämällä sivun F5-näppäimellä. (macilla omena+r)</p>');
					break;
				case ('fail_not_jpg');
					echo ('<p><b>Kuva ei ole JPEG tiedosto. Kuvaa ei voida arkistossa kääntää.</b></p>');
					break;
				case ('fail_file_not_found');
					echo ('<p><b>Alkuperäistä kuvaa ei löydy tai ole olemassa.</b></p>');
					break;						
			}
		}
		
		// thumbnail url
		$imageurl = $data->getImageFile('large', true);
		// link to the full image with the thumbnail as the link
		$fullimageurl = $data->getImageFile('full', false);
		echo CHtml::link($imageurl, $fullimageurl);
		?>
	</div>

<?php 

	// these are separate and not a part of the CHtml::link as the jQuery at the bottom requires these
	$linkToNext = CHtml::normalizeUrl(array('image/browse','browseOffset'=>$next));
	$linkToPrev = CHtml::normalizeUrl(array('image/browse','browseOffset'=>$prev));
	
?>

<div id="metadataboxcontainer">
<?php  
	$this->renderPartial('_metadatabox',array(
		'dataProvider'=>$data,
		'hideimage'=>true,
	)); 
?>

<?php
if (!Yii::app()->user->isGuest)
{
	$this->renderPartial('_imageControlBox',array(
		'dataProvider'=>$data,
	)); 
}
?>
</div>
	

<br />


<script type="text/javascript">
/*<![CDATA[*/

$(document).keyup(function(event){

		/* if the metadataboxedit is visible AND the text input cursor is on an input field,
			the next and prev keyboard navigation is disabled. */
		/*
		if ($('#metadataboxedit').is(':visible')) {
			if ($(event.target).is('input, textarea')) {
				return false;
			}		
		} */

		/* If the metadataboxedit is visible, the keyboard navigation is disabled */
		if ($('#metadataboxedit').is(':visible')) {
				return false;	
		}
			
		if (event.keyCode == '37') {
			<?php  
				if($prev<0)
				{
					echo('return false;');
				}
				else
				{
					echo('window.location = "' . $linkToPrev . '"');
				}
			?>
		}
		if (event.keyCode == '39') {
			<?php
				if($next>=$totalAmount)
				{
					echo('return false;');
				}
				else
				{ 
					echo('window.location = "' . $linkToNext . '"');
				}
			?>
		}
});

 /*]]>*/          
</script>


