<?php 
/*
 * Script which is used to make XMLHttpRequests to refresh the contents on the metadatabox.
 * Is triggered by the links defined in onmouseover at the _thumbview, as rendered on this results page
 */
$scripturl = CHtml::normalizeUrl(array('image/ajaxupdateview'));
$scripturledit = CHtml::normalizeUrl(array('image/ajaxupdateviewedit'));
$refreshScript = "function refreshmetadatabox(x) {
	$('#metadataboxcontainer').load('" . $scripturl . "&fastedit=true&id=' + x);
}";
Yii::app()->clientScript->registerScript('refreshscript',$refreshScript,CClientScript::POS_END);
?>

<?php 
if(isset($_POST['Image']))
{
$this->actionCompleteEditImage(); 
}
?>

<?php 
echo CHtml::beginForm();  
$model = Image::model();
?>

<div class="form wide">
	<table class="searchformtable">
		<tr>
			<td class="withborder">
		
				<div class="row">
					<?php 
						echo CHtml::radioButtonList(
							// name of the radiobuttonlist, read from the post in controller
							'addOrReplaceRakennus',
							// the default checked value is the "false"
							1,
							// value and label names, 1 replaces the kuvateksti, 0 adds to it
							array(
								true=>'Korvaa rakennukset',
								false=>'Lisää rakennus'
							),
							// htmlOptions
							array(
								'separator'=>'<br />'
							)
						); 
					?>
				</div>
		
				<div class="row">
					<?php echo CHtml::textField('kohteet',''); ?>
				</div>
			</td>
				
		
			<td class="withborder">
				
				<div class="row" style="height:42px;">
					<?php echo CHtml::label('Kuvauspäivämäärä',''); ?> <br />
					<?php			
							echo CHtml::textField('day','',array('maxlength'=>'2','size'=>'2')); 
							echo CHtml::textField('month','',array('maxlength'=>'2','size'=>'2'));
							echo CHtml::textField('year','',array('maxlength'=>'4','size'=>'4'));
					?>
				</div>
				
				<div class="row" id="aikavarma" style="position:relative;">
					<?php 
					echo CHtml::activeLabel($model,'aikavarma',array('class'=>'properlabel'));
					echo CHtml::activeRadioButtonList(
						$model,							
						'aikavarma',								
						$model->getTypeOptions(),
						array(
							'separator'=>'',
							'labelOptions'=>array(
								'style'=>'font-weight:normal',
							)
						));
					?>
				</div>
				
			</td>
			
			<td class="withbordernopadding">
			
				<div>
					<?php $this->renderPartial('_tagGrid',array('styleContext'=>'search','dataProvider'=>$model)); ?>
				</div>	
			
			</td>
			
			<td rowspan="2" class="withborder">
				<p>
					<?php 
						echo CHtml::link(
							'Tyhjennä muokkaustila',
							array(
								'image/basketDelete',
							),
							// array htmlOptions 
							array(
								'class'=>'linkbutton',
								'confirm'=>'Poistetaanko kuvat kuvien muokkaustilasta?',
							) 
						);
					?>
				</p>
				<br />
				<br />
				<br />
				<br />
				<br />
				<p>
					<?php echo CHtml::ajaxLink(
							'Sijoita kaikkiin kuviin',
							array(
								'image/fastSave',
							),
							// array AjaxOptions
							array(
								'type'=>'POST',
							),
							// array htmlOptions 
							array(
								'class'=>'linkbutton',
								'confirm'=>'Sijoitetaanko syötetty metadata kaikkiin kuviin?',
							) 
					);
					?>
				</p>
			</td>
		</tr>
		
		<tr>
		
			<td class="withborder">
				<div class="row">
					<?php echo CHtml::activeLabel($model,'valokuvaaja'); ?> 
					<br />
					<?php echo CHtml::activeTextField($model,'valokuvaaja'); ?>
				</div>
			</td>
			
			<td colspan="2" class="withborder">
				<div class="row" id="addorreplace">
						<?php 
							
							echo CHtml::resetButton('Tyhjennä kentät',array('class'=>'buttonlink','style'=>'float:right;height:50px;width:150px;')); 
						
							echo CHtml::radioButtonList(
								// name of the radiobuttonlist, read from the post in controller
								'addOrReplaceKuvateksti',
								// the default checked value is the "false"
								0,
								// value and label names, 1 replaces the kuvateksti, 0 adds to it
								array(
									true=>'Vaihda kuvateksti',
									false=>'Lisää kuvatekstin perään'
								),
								// htmlOptions
								array(
									'separator'=>'<br />'
								)
							); 
						?>
						<br />
						<?php  echo CHtml::textField('kuvateksti','',array('style'=>'width:500px;')); ?>
					</div>	
			</td>
		</tr>
					

	</table>
</div>
<hr />

<?php 
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'ajaxUpdate'=>false,
	'enablePagination'=>false,
	'itemsCssClass'=>'resultsholderfast',
	'itemView'=>'_thumbviewFast',
	'viewData'=>array(),
));
echo CHtml::endForm(); 
?>

<div id="metadataboxcontainer">
<?php 
	// This is only processed once, when the results page is loaded for the first time
	// the actual _metadatabox mouseover renderings are processed by the _thumbview
	if(isset($dataProvider->data[0]))
	{
		if(isset($_GET['imageid']))
		{
			$this->renderPartial('_metadatabox',array(
				'dataProvider'=>Image::model()->findByPk($_GET['imageid']),
				'fastedit'=>true,
			));
		}
		else 
		{
			$this->renderPartial('_metadatabox',array(
				'dataProvider'=>$dataProvider->data[0],
				'fastedit'=>true,
			));
		}
	}
	else
	{
		echo("Ei hakutuloksia."); // FIXME FIXME
	}
?>
</div>

<div style="position:absolute;bottom:10px;right:10px;">
	<p>

	</p>
</div>

<?php 
/*	

	
*/
?>
<script type="text/javascript">
/*<![CDATA[*/

		$(document).keyup(function(event){

			if ($(event.target).is('input, textarea')) {
				return false;
			}	
			if (event.keyCode == '32')
			{
				$("#metadataboxedit").show(); $("#metadatabox").hide();
			}
		
		});


 /*]]>*/          
</script>


