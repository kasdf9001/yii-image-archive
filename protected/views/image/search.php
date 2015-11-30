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

<p style="padding:0px 30px 0px 30px;">Sisältöä on vähän, paina pelkästään Hae, niin löytyy jotain.</br>
Sivu toimii kunnolla ainoastaan Firefoxilla, joka on ainoa virastossa käytössä oleva selain.</p>
<p style="padding:0px 30px 0px 30px;">Alla esimerkki tämän sovelluksen lähdekoodista.</p>

<xmp>
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow everyone to perform actions
				'actions'=>array(
					// search page, contains the search form, also used as the homepage for the application
					'search',
					// displays search results
					'results',
					// browsing of images
					'browse',
					// renders the metadatabox in the results and batch edit views
					'ajaxupdateview',
					// constructs the database criteria object based on the search form
					'searchConstructor',
					// redirects the user to a page with a set header, displays the image
					// with a custom filename and as attachement => save as dialogue
					'saveImage',
					// calulates the results page the user should be directed to from the browse view
					'calculateResultsPage',
				),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform actions
				'actions'=>array(
					// simple page which houses the button to upload new images to the archive
					'add',
					// deletes an image based on imageid (serial)
					'delete',
					// rotates an image 90 degrees in a direction
					'rotate',
					// reads a metadataboxedit form and saves the inputted information to the object
					'completeEditImage',
					// inputs a string that contains a list of building addresses,
					// parses the list and saves the addresses to the building database
					'buildingSave',
					// handles an uploaded file
					'upload',
					// renders the batch edit view
					'fast',
					// parses and saves form data to a model from the batch edit form
					'fastSave',
					// adds an image to the basket
					'addToBasket', 
					// deletes the basket
					'basketDelete',
					// removes single image from the basket
					'removeFromBasket',
					),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Feeds the user the requested image, based on $_GET['imageid'].
	 * Renames the file to a useful filename, not img0303.jpg.
	 * The saveImage partial view has header information which directs
	 * the browser to use the rendered page as an file attachement. 
	 */
	public function actionSaveImage()
	{
		$image = Image::model()->findByPk($_GET['imageid']);
		$this->renderPartial('saveImage',array('image'=>$image));
	}
	
	/**
	 * Rotates an stored image.
	 * 
	 * Rotates an image stored in the database. The rotation first creates a rotated
	 * copy of the original full sized image. The copy is placed on a temporary directory
	 * on a temporary filename. Once the copy has been succesfully crated, all the original
	 * image files are deleted; the full sized original image, and all it's thumbnails.
	 * The rotated image is then moved from it's temporary location to the place of the 
	 * original full size image, and the thumbnails are generated from that image as in
	 * any case where thumbnails are created. The database is not altered in any way, only
	 * the image files in the filesystem. Once the process is complete, the user is directed
	 * to the returnUrl.
	 * 
	 * @param int $imageid
	 * @param string $direction, either 'clockwise' or 'counterclockwise', states direction of rotation
	 */
	public function actionRotate($imageid,$direction)
	{
		
		// the image object is created from the database to avoid user input
		$image = Image::model()->findByPk($imageid);
		// where to return after the operation
		$rotatedurl = Yii::app()->user->returnUrl;
		
		// temporary filename for the created rotated image
		$tempfilename = Image::FULLIMAGETEMPPATH . uniqid() . '.jpg';
		// the image variable $rotated is created based on the original JPG in the file system
		
		// if the file does not exist, the user is redirected away
		if (!file_exists($image->getImageFile('full',false,Image::FULLIMAGEPATH)))
		{
			$rotatedurl['rotated']='fail_file_not_found';
			$this->redirect($rotatedurl);
		}
				
		// the original full image is evaluated to determine if it's a JPG
		// should the file not be jpg, execution is terminated and user is presented
		// with an error
		$originalFullImage = $image->getImageFile('full',false,Image::FULLIMAGEPATH);
		// getimagesize returns information of image size, but also of type among other things
		$information = getimagesize($originalFullImage);
		if ($information['mime'] != 'image/jpeg')
		{
			$rotatedurl['rotated']='fail_not_jpg';
			$this->redirect($rotatedurl);
		}

		// an uncompressed image is created from the original full size image
		$rotate = imagecreatefromjpeg($originalFullImage);
		// the original full image is unset to save memory
		unset($originalFullImage);
		
		// defining the direction of the rotation
		switch($direction) 
		{
			case 'clockwise':
				$angle = -90; 	
				break;
			case 'counterclockwise':
				$angle = 90; 	
				break;
		}
		
		// creates the rotated image
		$rotated = imagerotate($rotate,$angle,0);
		unset($rotate);
		// saves the rotated image as a jpeg to the temporary file directory
		imagejpeg($rotated,$tempfilename,100);
		unset($rotated);
		
		// the existance of the rotated image is evaluated before anything is deleted
		if(file_exists($tempfilename))
		{
			// deletes all the physical image files for the image object
			$image->deleteImage(array('small', 'light', 'medium', 'large','full'));
			
			// moving the generated image to it's desired location
			rename($tempfilename,$image->getImageFile('full',false,Image::FULLIMAGEPATH));
			
			// generating thumbnails for the rotated image
			$image->generateThumbnails();
		}

		$rotatedurl['rotated']='true';
		$this->redirect($rotatedurl);
				
	}
	
	/**
	 * The page used to add images to the archive.
	 * Extensively uses the actionUpload function.
	 */
	public function actionAdd()
	{
		$this->render('add');
	}
</xmp>