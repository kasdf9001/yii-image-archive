<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cd'); ?>
		<?php echo $form->textField($model,'cd',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'valokuvaaja'); ?>
		<?php echo $form->textArea($model,'valokuvaaja',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'julkaisuvapaa'); ?>
		<?php echo $form->textField($model,'julkaisuvapaa',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'valokuva'); ?>
		<?php echo $form->textField($model,'valokuva',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maalaus'); ?>
		<?php echo $form->textField($model,'maalaus',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'piirustus'); ?>
		<?php echo $form->textField($model,'piirustus',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ulkokuva'); ?>
		<?php echo $form->textField($model,'ulkokuva',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sisakuva'); ?>
		<?php echo $form->textField($model,'sisakuva',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ilmakuva'); ?>
		<?php echo $form->textField($model,'ilmakuva',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'historiallinen'); ?>
		<?php echo $form->textField($model,'historiallinen',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyomaa'); ?>
		<?php echo $form->textField($model,'tyomaa',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'esittely'); ?>
		<?php echo $form->textField($model,'esittely',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ihmisia'); ?>
		<?php echo $form->textField($model,'ihmisia',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'linnoituslaitteet'); ?>
		<?php echo $form->textField($model,'linnoituslaitteet',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kuvateksti'); ?>
		<?php echo $form->textArea($model,'kuvateksti',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'diateksti'); ?>
		<?php echo $form->textArea($model,'diateksti',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kartta'); ?>
		<?php echo $form->textField($model,'kartta',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tiedostotyyppi'); ?>
		<?php echo $form->textArea($model,'tiedostotyyppi',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aikavarma'); ?>
		<?php echo $form->textField($model,'aikavarma',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'imageid'); ?>
		<?php echo $form->textField($model,'imageid'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->