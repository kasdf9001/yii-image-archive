<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'image-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cd'); ?>
		<?php echo $form->textField($model,'cd',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'cd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'valokuvaaja'); ?>
		<?php echo $form->textArea($model,'valokuvaaja',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'valokuvaaja'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'julkaisuvapaa'); ?>
		<?php echo $form->dropDownList($model,'julkaisuvapaa', $model->getTypeOptions()); ?>
		<?php // echo $form->textField($model,'julkaisuvapaa',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'julkaisuvapaa'); ?>
	</div> 

	<div class="row">
		<?php echo $form->labelEx($model,'valokuva'); ?>
		<?php echo $form->textField($model,'valokuva',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'valokuva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maalaus'); ?>
		<?php echo $form->textField($model,'maalaus',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'maalaus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'piirustus'); ?>
		<?php echo $form->textField($model,'piirustus',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'piirustus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ulkokuva'); ?>
		<?php echo $form->textField($model,'ulkokuva',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'ulkokuva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sisakuva'); ?>
		<?php echo $form->textField($model,'sisakuva',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'sisakuva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ilmakuva'); ?>
		<?php echo $form->textField($model,'ilmakuva',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'ilmakuva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'historiallinen'); ?>
		<?php echo $form->textField($model,'historiallinen',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'historiallinen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyomaa'); ?>
		<?php echo $form->textField($model,'tyomaa',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'tyomaa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'esittely'); ?>
		<?php echo $form->textField($model,'esittely',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'esittely'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ihmisia'); ?>
		<?php echo $form->textField($model,'ihmisia',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'ihmisia'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'linnoituslaitteet'); ?>
		<?php echo $form->textField($model,'linnoituslaitteet',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'linnoituslaitteet'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kuvateksti'); ?>
		<?php echo $form->textArea($model,'kuvateksti',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'kuvateksti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'diateksti'); ?>
		<?php echo $form->textArea($model,'diateksti',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'diateksti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kartta'); ?>
		<?php echo $form->textField($model,'kartta',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'kartta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tiedostotyyppi'); ?>
		<?php echo $form->textArea($model,'tiedostotyyppi',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tiedostotyyppi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aikavarma'); ?>
		<?php echo $form->textField($model,'aikavarma',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'aikavarma'); ?>
	</div>

	<?php /* hidden , the hidden one is the row below
	<div class="row">
		<?php echo $form->labelEx($model,'imageid'); ?>
		<?php echo $form->textField($model,'imageid'); ?>
		<?php echo $form->error($model,'imageid'); ?>
	</div> */ ?>
	
	<div class="row">
		<?php echo $form->hiddenField($model,'imageid'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->