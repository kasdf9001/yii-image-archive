<?php 
$data = $dataProvider;
$talolista = $this->actionBuildingList(Building::model()->findAllByPk($data->imageid)); 
?>

<div class="metadatabox" id="metadatabox">	
	
	<table id="metadatatable">
	
		<tr>
			<td><?php echo CHtml::activeLabel($data,'kohde',array('class'=>'metadatawide')); ?></td>
			<td><?php echo $talolista; ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::activeLabel($data,'valokuvaaja',array('class'=>'metadatawide')); ?></td>
			<td><?php echo CHtml::value($data,'valokuvaaja'); ?></td>
		</tr>
		<tr>
			<td><?php echo CHtml::activeLabel($data,'pvm',array('class'=>'metadatawide')); ?></td>
			<td><?php echo $this->dateDisplayFormat($data->pvm); ?></td>
		</tr>
		
		<tr>
			<td></td>
			<td>
			<?php 
				if($dataProvider->aikavarma=='k')
				{
					echo('Tarkka kuvausaika');
				}
				else 
				{
					echo('Epävarma kuvausaika');
				}
			?>
			</td>
		</tr>
	
		<tr>
			<td><?php echo CHtml::activeLabel($data,'kuvateksti',array('class'=>'metadatawide')); ?></td>
			<td><?php echo CHtml::value($data,'kuvateksti'); ?></td>
		</tr>
	
		<?php
			if($data->diateksti != '')
			{
				echo('<tr>');
					echo('<td>'); 
						echo CHtml::activeLabel($data,'diateksti',array('class'=>'metadatawide'));
					echo('</td>'); 					
					echo('<td>'); 					
						echo CHtml::value($data,'diateksti');
					echo('</td>'); 				
				echo('</tr>');
			}	
		?>
	
		<tr>
			<td><?php echo CHtml::label('CD / ID',false,array('class'=>'metadatawide')); ?></td>
			<td><?php echo CHtml::value($data,'cd'); echo(' / '); echo CHtml::value($data,'id'); ?></td>
		</tr>
	
	</table>
	
	<div class="tagGrid">
		<?php $this->renderPartial('_tagGrid',array('styleContext'=>'metadatabox','dataProvider'=>$data)); ?>
	</div>

	<div class="row">
		<?php 
		if(!isset($hideimage))
		{
			echo $data->getImageFile('medium', true);
		}
		?>
	</div>
		
	<div class="row">
		<?php 
			if(isset($hideimage))
			{	
				echo CHtml::link(
						'Lataa kuva koneelle',
						array(
							'image/saveImage',
							'imageid'=>$data->imageid,
						),
						array(
							'class'=>'linkbutton',
				));
			}
		?>
	</div>
	
</div>	

<div class="metadatabox" id="metadataboxedit" style="display:none;">

	<?php echo CHtml::beginForm(array('image/fast','imageid'=>$data->imageid)); ?>
	
	<div class="row">
		<?php echo CHtml::activeLabel($data,'kohde',array('class'=>'metadatawide')); ?>
		<?php echo CHtml::textField('kohteet',$talolista); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabel($data,'valokuvaaja',array('class'=>'metadatawide')); ?>
		<?php echo CHtml::activeTextField($data,'valokuvaaja'); ?>
	</div>
	
	<div class="row">
		<?php 
			
			echo CHtml::activeLabel($data,'pvm',array('class'=>'metadatawide'));

			// explodes the pvm date of format 19980101 to an array with the individual values
			$date = $this->explodeDate($data->pvm);
			// replaces the '00' values with just blank input fields
			$date['day'] = str_replace('00', '', $date['day']);
			$date['month'] = str_replace('00', '', $date['month']);
			$date['year'] = str_replace('0000', '', $date['year']);
			
			echo CHtml::textField('day',$date['day'],array('maxlength'=>'2','size'=>'2')); 
			echo('.');
			echo CHtml::textField('month',$date['month'],array('maxlength'=>'2','size'=>'2'));
			echo('.');
			echo CHtml::textField('year',$date['year'],array('maxlength'=>'4','size'=>'4'));

		?>
	</div>
	
	<div class="row" style="position:relative;left:100px;">
	<?php 
			if($dataProvider->aikavarma=='k')
			{
				echo CHtml::activeCheckBox($data,'aikavarma',array('checked'=>'checked'));
			}
			else 
			{
				echo CHtml::activeCheckBox($data,'aikavarma');
			}
			echo CHtml::activeLabel($data,'aikavarma');
		?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabel($data,'kuvateksti',array('class'=>'metadatawide')); ?>
	</div>	

	<div class="row">
		<?php echo CHtml::activeTextArea($data,'kuvateksti',array('rows'=>3,'cols'=>45)); ?>
	</div>
	
	<?php 
		if($data->diateksti!='')
		{
			echo('<div class="row">');
				echo CHtml::activeLabel($data,'diateksti',array('class'=>'metadatawide'));
				echo CHtml::value($data,'diateksti');
			echo('</div>');
		}
	?>
	
	<div class="row">
		<?php echo CHtml::label('CD / ID',false,array('class'=>'metadatawide')); ?>
		<?php echo CHtml::value($data,'cd'); echo(' / '); echo CHtml::value($data,'id'); ?>
	</div>

	<div class="tagGrid">
		<?php $this->renderPartial('_tagGrid',array('styleContext'=>'metadataboxedit','dataProvider'=>$data)); ?>
	</div>

	<div class="row">
		<?php 
		
		if(isset($fastedit))
		{
			echo CHtml::submitButton(
				// label
				'Tallenna',
				// htmloptions
				array(
					'class'=>'buttonlink'	
				)
			);		
		}
		else 
		{
			echo CHtml::ajaxSubmitButton(
				// label
				'Tallenna',
				// url
				array(
					'image/completeEditImage',
					'id'=>$data->imageid,
				),
				// ajaxoptions
				array(
					'update' => '#metadataboxcontainer',
					'complete'=>'function() { $("#metadataboxedit").hide(); $("#metadatabox").show(); }',
				),
				// htmloptions
				array(
					'class'=>'buttonlink',
				)
			);
		}

		?>
	</div>

	<?php 
		// hidden input field so $_POST can read the imageid 
		echo CHtml::activeHiddenField($data,'imageid'); 
	?>

	<?php echo CHtml::endForm(); ?>

</div>