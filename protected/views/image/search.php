<?php Yii::app()->user->setReturnUrl(array('image/search')); ?>

<h2 style="margin-left:10px;">Kuva-arkisto insinöörityö KK</h2>

<div class="form wide" id="searchform">
	<?php echo CHtml::beginForm(); ?>

	<table class="searchformtable">

		<tr>
		
			<td class="withborder">
			
				<?php echo CHtml::errorSummary($model); ?>
				
				<div class="row">
					<?php echo CHtml::activeLabel($model,'kohde',array('class'=>'textinputlabel')); ?>					
					<?php echo CHtml::textField('kohde','',array('maxlength'=>'4')); ?>
				</div>		
				
				<div class="row">
					<?php echo CHtml::activeLabel($model,'valokuvaaja',array('class'=>'textinputlabel')); ?>
					<?php echo CHtml::activeTextField($model,'valokuvaaja'); ?>		
				</div>
				
				<div class="row">
					<?php echo CHtml::activeLabel($model,'cd',array('class'=>'textinputlabel')); ?>
					<?php echo CHtml::activeTextField($model,'cd'); ?>	
				</div>
			
				<div class="row">
					<?php echo CHtml::activeLabel($model,'id',array('class'=>'textinputlabel')); ?>
					<?php echo CHtml::activeTextField($model,'id'); ?>
				</div>
				
			</td>
			
			<td class="withborder" width="170px" height="140px">
				
				<?php
				/**
				 *	Removed the entire option to search for images taken or not taken on a 
				 *	certain date. The feature is not particularly useful.
				 */
				/* 
				<div class="row" id="aikavarma" style="position:relative;height:28px;">
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
					
				</div>
				*/
				?>
						
				<div class="row">
					<?php echo CHtml::label('Aikahaku','',array()); ?>
				</div>
				
				<div class="row">
					<?php 
						echo CHtml::radioButton(
							// the name of this radio button does not matter, the type of search is determined based on the other radio button
							'tarkkaaika',
							false,
							array(
								'onclick'=>'
									$("#aikavali").attr("checked",false);
									$("#timeOne").show();
									$("#timeTwo").hide();
									$("#exampleOne").show();
									$("#exampleTwo").hide();									
								',
							
							)
						);
						echo CHtml::label('tarkka aika','tarkkaaika',array('style'=>'font-weight:normal;'));
						?>	
					
				</div>
				
				<div class="row">
					<?php 
						echo CHtml::radioButton(
							'aikavali',
							false,
							array(
								'onclick'=>'
									$("#tarkkaaika").attr("checked",false);
									$("#timeTwo").show();
									$("#timeOne").show();
									$("#exampleOne").show();
									$("#exampleTwo").show();									
								',
							)
						);
						echo CHtml::label('aikaväli','aikavali',array('style'=>'font-weight:normal;'));
					?>	
				</div>
				
				<div class="row" id="timeOne" style="display:none;">				
					<?php 
						echo CHtml::textField('dayOne','',array('maxlength'=>'2','size'=>'2')); 
						echo CHtml::textField('monthOne','',array('maxlength'=>'2','size'=>'2'));
						echo CHtml::textField('yearOne','',array('maxlength'=>'4','size'=>'4'));
					?>
				</div>
				<div class="row" id="exampleOne" style="font-size:smaller;display:none;">
					esim. 31 12 1900
				</div>
				
				<div class="row" id="timeTwo" style="display:none;">
					<?php 
						echo CHtml::textField('dayTwo','',array('maxlength'=>'2','size'=>'2')); 
						echo CHtml::textField('monthTwo','',array('maxlength'=>'2','size'=>'2'));
						echo CHtml::textField('yearTwo','',array('maxlength'=>'4','size'=>'4'));
					?>
				</div>
				
				<div class="row" id="exampleTwo" style="font-size:smaller;display:none;">
					esim. 01 01 2011
				</div>	
				
			</td>
			
			<td class="withborder">
				
				<!-- _tagGrid.php contains the grid style yes/no form for the image tags
						such as "julkaisuvapaa" and "historiallinen" -->
				<div class="tagGrid">
					<?php $this->renderPartial('_tagGrid',array('styleContext'=>'search')); ?>
				</div>
				
			</td>
			
		</tr>
		
		<tr>
			
			<td colspan="3" class="withborder">
			
				<div class="row">
					<?php echo CHtml::activeLabel($model,'kuvateksti',array('class'=>'textinputlabel')); ?>
					<?php echo CHtml::activeTextField($model,'kuvateksti',array('style'=>'width:300px;')); ?>
				</div>
				
			</td>
		
		</tr>
		
		<tr>
		
			<td colspan="3"  class="withborder">
					
					<?php echo CHtml::submitButton('Hae',array('submit'=>array('searchConstructor'),'class'=>'buttonlink','style'=>'float:right;width:100px;')); ?>
					<label class="textinputlabel">Järjestys</label>
					<?php echo CHtml::dropDownList('searchsort','asdf',$model->getSortOptions(),array('style'=>'background-color:#EEEEEE;border:1px solid #999999;')); ?>			
					<?php // echo CHtml::link('Tyhjennä',array('search'),array('class'=>'linkbutton','style'=>'float:right;text-align:center;')); ?>
					
			</td>
		
		</tr>
		
	</table>
	
	<?php echo CHtml::endForm(); ?>
</div>