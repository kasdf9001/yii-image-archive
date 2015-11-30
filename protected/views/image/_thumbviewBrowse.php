<?php

	// creates blank thumbholders if we are near the 1st image of the results
	if($index==0)
	{
		$extraThumbs = 4 - $browseOffset;
		for($i=0;$i<$extraThumbs;$i++)
		{
			echo('<div class="thumbholderNoHover"></div>');
		}
	}
		
	if($displayedImageId == $data->imageid)
		echo('<div class="thumbholder" id="currentthumb">');
	else
		echo('<div class="thumbholder">');	

	$thumbnailurl = $data->getImageFile('small', true);
			
	// the rolldataprovider is centered so that the displayed image is the 4th one,
	// in some cases, when the displayed image is near the start, it can't be centered
	if ($browseOffset < 5)
	{
		$rollerIndex = $index - $browseOffset;
	}
	else
	{ 
		$rollerIndex = $index - 4;
	}
	
	echo CHtml::link(
		// link body
		$thumbnailurl,
		// url
		array(
			'image/browse',
			'browseOffset'=>$browseOffset + $rollerIndex,
		),
		// htmlOptions
		array(
		)
	);

	echo('</div>');
	
?>