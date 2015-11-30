<?php

	$currentlyvieweditem = $currentPage * ImageController::RESULTS_PAGE_THUMB_AMOUNT + $index;
	
	$thumbnailurl = $data->getImageFile('small', true);
	
	echo('<div class="thumbcontainer" onmouseover="refreshTimer = setTimeout(\'refreshmetadatabox(' . $data->imageid . ')\',100);" 
				onmouseout="clearTimeout(refreshTimer);">');
		echo('<div class="thumbholderresults">');
			echo CHtml::link(
				// link body
				$thumbnailurl,
				// url
				array(
					'image/browse',
					'id'=>$data->imageid,
					'browseOffset'=>$currentlyvieweditem, 
				),
				// htmlOptions
				array(
					/*
					'onMouseOver'=>"refreshTimer = setTimeout('refreshmetadatabox(" . $data->imageid . ")',100)",
					'onmouseout'=>"clearTimeout(refreshTimer)",
					*/
				)
			);
		echo('</div>');
	echo('</div>');
?>