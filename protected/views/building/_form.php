<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'building-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cdno'); ?>
		<?php echo $form->textField($model,'cdno',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'cdno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idno'); ?>
		<?php echo $form->textField($model,'idno',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'idno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'saari'); ?>
		<?php echo $form->textField($model,'saari',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'saari'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rakennus'); ?>
		<?php echo $form->textField($model,'rakennus',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'rakennus'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->