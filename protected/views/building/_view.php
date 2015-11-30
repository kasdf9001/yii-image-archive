<div class="view">


	<b><?php echo CHtml::encode($data->getAttributeLabel('idno')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idno), array('view', 'id'=>$data->idno)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cdno')); ?>:</b>
	<?php echo CHtml::encode($data->cdno); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idno')); ?>:</b>
	<?php echo CHtml::encode($data->idno); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('saari')); ?>:</b>
	<?php echo CHtml::encode($data->saari); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rakennus')); ?>:</b>
	<?php echo CHtml::encode($data->rakennus); ?>
	<br />



</div>