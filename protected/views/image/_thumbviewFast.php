<?php

	$thumbnailurl = $data->getImageFile('small', true);
	
	echo('<div class="thumbcontainer" onmouseover="if ($(\'#metadataboxedit\').is(\':visible\')) { return false; } else { refreshTimer = setTimeout(\'refreshmetadatabox(' . $data->imageid . ')\',100); }" onmouseout="clearTimeout(refreshTimer);" onclick="clicktimer = setTimeout(\'refreshmetadatabox(' . $data->imageid . ')\',100)">');
	
		echo('<div class="thumbholderresults">');		
		
		echo CHtml::ajaxLink(
			// link body
			$thumbnailurl,
			// url
			array(
				'image/fastSave',
				'imageid'=>$data->imageid,
			),
			// ajaxOptions
			array(
				'type'=>'POST',
			),
			// htmlOptions
			array(
			)	
		);
		
		echo('</div>');
	echo('</div>');
?>