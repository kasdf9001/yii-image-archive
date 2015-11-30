<?php

class ImageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * settings for the CPagination
	 * @var array of settings, name value pairs
	 */
	const RESULTS_PAGE_THUMB_AMOUNT=42;
	public $paginationSettings = array(
		'pageSize'=>ImageController::RESULTS_PAGE_THUMB_AMOUNT,
	);

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
	
	public function actionUpload()
	{
		
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array('jpeg','jpg','bmp','png','tif','tiff');
		// max file size in bytes
		// this requires server settings as well: post_max_size, upload_max_filesize
		$sizeLimit = 20 * 1024 * 1024;
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		
		// the temporary filename is based on uniqiq() which generates unique strings based on the time in microseconds
		$temporaryFileName = uniqid();
		$ext = pathinfo($_GET['qqfile'],PATHINFO_EXTENSION);
		$originalFilename = pathinfo($_GET['qqfile'],PATHINFO_BASENAME);
		
		$result = $uploader->handleUpload(Image::FULLIMAGETEMPPATH,$temporaryFileName, $ext);
		// to pass data through iframe you will need to encode all html tags
		
		/*
		 * If the qqFileUploader in extensions is OK with the file, it will return a 'success' = true
		 * this checks the result of the qqFileUploader, which means that the file was correctly uploaded
		 * Else an error is printed.
		 */
		if(isset($result["success"]))
		{
			// if the cdbox value is defined, the highest CD will not be evaluated, but rather the user defined is used
			// allowes the user to override the CD where images are placed, should the user know how to use the _GET cdbox
			if(isset($_GET['cdbox']))
			{
				// the next cd and id values are created based on the function in the model
				// the CD and ID values are the next highest, eg id1000->id1001, on the highest cd value
				$nextCdId = Image::model()->nextCdId($_GET['cdbox']);
			}
			else 
			{
				$nextCdId = Image::model()->nextCdId(false);
			}
			
			// new Image object
			$insertImage = new Image;
			// temporary image description to make it easy to find images with missing metadata
			// removed, users did not understand what it was.
			// $insertImage->kuvateksti = 'täydentämätön lisätty ' . date('r') . ' alkuperäinen tiedostonimi ' . $originalFilename;
			$insertImage->cd = $nextCdId['cd'];			
			$insertImage->id = $nextCdId['id'];
			
			// inserts 'e' "boolean" value to all the tag fields
			$tags=Image::model()->tags();
			foreach($tags as $tag)
			{
				$insertImage->$tag='e';
			}
			// saves the file extension, based on the $_GET['qqfile']
			$insertImage->tiedostotyyppi=strtolower($ext);

			$exif = exif_read_data(Image::FULLIMAGETEMPPATH . $temporaryFileName . '.' . $ext, 'IFD0');
			// echo('{error:"Exif komponenttia ei voitu lukea:"}');		
					
			if (isset($exif['DateTimeOriginal']))
			{
				// quick parse so it fits the database
				$exifDate = $exif['DateTimeOriginal'];
        		$insertImage->pvm = substr($exifDate,0,4) . substr($exifDate,5,2) . substr($exifDate,8,2);
        		$insertImage->aikavarma = 'k';
			} 
			else
			{
				// blank date is needed so the image is not filtered out as deleted
				$insertImage->pvm = '00000000';
			}
			
			// saves the metadata to the database
			$insertImage->save();
			
			/*
			 * for some reason this doesn't work;
			 * $imageid = $insertImage->imageid; so this is a workaround.
			 * It's probably a bug it doesn't work so if there's a later version, it's worth trying if it works.
			 */
			$temp = Image::model()->findBySql("SELECT * FROM diat WHERE id = '" . $nextCdId['id'] . "' AND cd = '" . $nextCdId['cd'] . "';");
			$this->actionAddToBasket($temp->imageid);
			unset($temp);

			// if the directory doesn't exist, it's created, the first '' is the cd base directory, eg /kuvat/9006/
			$subdirs = array('', '/96x64', '/192x128', '/384x256', '/768x512');
			foreach ($subdirs as $subdir)
			{
				if(!file_exists(Image::FULLIMAGEPATH . $nextCdId['cd'] . $subdir))
				{
					mkdir(Image::FULLIMAGEPATH . $nextCdId['cd'] . $subdir);
				}
			}
			
			// Moves the image to the proper location. 
			rename(
				Image::FULLIMAGETEMPPATH . $temporaryFileName . '.' . $ext, 
				$insertImage->getImageFile('full', false, Image::FULLIMAGEPATH)
			);
			
			$insertImage->generateThumbnails();
				
			// success true JSON for the javascript upload plugin so it can se it's css correctly
			echo('{success:true}');
		}
		else 
		{
			echo('{error:"' . $result["error"] . '"}');
		}
	}
	
	/**
	 * Adds images to the basket.
	 * @param the imageid of the image
	 */
	public function actionAddToBasket($addThisToBasket='')
	{		
		if(isset($_GET['imageid']))
		{
			// The imageid of the image to be submitted to the basket
			$addThisToBasket = $_GET['imageid'];
		}		
		// The current contents of the basket are loaded
		$basket = Yii::app()->user->getState('basketContents');
		// if the basket is empty, it will be created with a blank array so the in_array can compare the two
		if(!isset($basket)) $basket = array();
		// Searches if the image is already in the basket, will be only added if its not already there 
		if( !in_array($addThisToBasket,$basket) )
		{
			$i = count($basket);
			$basket[$i] = $addThisToBasket;
			Yii::app()->user->setState('basketContents', $basket);
		}	
	}
	
	/**
	 * Fast edit of basket
	 */
	public function actionFast()
	{
		$basketIds = Yii::app()->user->getState('basketContents');
		$basketSize = count($basketIds);
		if($basketSize > 0)
		{
			$basketCriteria = new CDbCriteria;
			$basketCriteria->order = Image::TYPE_CDIDDESC;
			foreach($basketIds as $bid)
			{
				$crit = 'imageid = ' . $bid;
				$basketCriteria->addCondition($crit,'OR');
			}
			$fastEditDataProvider = new CActiveDataProvider('Image',array('criteria'=>$basketCriteria,'Pagination'=>false,));
			$this->render('fast',array('dataProvider'=>$fastEditDataProvider));
		}
		else
		{
			$this->render('fastbutempty');
		}
	}	
	
	/**
	 * Deletes the basket.
	 */
	public function actionBasketDelete()
	{
		$newBasket = array();
		Yii::app()->user->setState('basketContents',$newBasket);
		$this->redirect(array('image/fast'));
	}
	
	/**
	 * Remove item from basket.
	 */
	public function actionRemoveFromBasket()
	{
		$removeThis = array($_GET['imageid']);
		$basket = Yii::app()->user->getState('basketContents');
		$newBasket = array_diff($basket,$removeThis);
		// reduces useless keys, blank keys, from the array
		$newBasket = array_values($newBasket);
		Yii::app()->user->setState('basketContents',$newBasket);
	}
	
	/**
	 * Takes in the date from the database, in the format; "19920101" and
	 * then explodes it into 3 parts; year, month and day, and returns
	 * it in an array.
	 * @param array $date
	 */
	public function explodeDate($date)
	{
		$day = substr($date,6,2);
		$month = substr($date,4,2);
		$year = substr($date,0,4);
		return array('day'=>$day,'month'=>$month,'year'=>$year);
	}
	
	/**
	 * Calculates the page where the user should return from 
	 * the browse view.
	 */
	public function calculateResultsPage($browseOffset)
	{
		$returnpage = $browseOffset / ImageController::RESULTS_PAGE_THUMB_AMOUNT;
		// rounding off the zeros
		$returnpage = floor($returnpage) + 1;
		return $returnpage;
	}
	
	/**
	 * Formats a date for displaying the date
	 * @param date to be formatted $date
	 * @return the date which is appropriate for displaying
	 */
	public function dateDisplayFormat($date)
	{
		$day = substr($date,6,2);
		$month = substr($date,4,2);
		$year = substr($date,0,4);
		if ($year=='0000')
			return '?';
		if ($month=='00')
			return '~ ' . $year;
		if ($day=='00')
			return '~ ' . $month . '.' . $year;
		$displayDate = '' . $day . '.' . $month . '.' . $year;
		return $displayDate;
		
	}


	/**
	 * Renders the search view, the file search.php.
	 */
	public function actionSearch()
	{
		$model = new Image;
		$this->render('search', array('model'=>$model));
	}
	
	/**
	 * Renders search results, the file results.php
	 */
	public function actionResults()
	{	
		// if the imagesearchcriteria object doesn't exist, as in, the user just
		// clicked on results without actually going through the search window, he will be
		// redirected to the search.		
		$test = Yii::app()->user->getState('imagesearchcriteria');
		if(!isset($test))
		{
			unset($test);
			$this->redirect(array('search'));
		}
		
		$resultsDataProvider = new CActiveDataProvider('Image',array(
			'criteria'=>Yii::app()->user->getState('imagesearchcriteria'),
			'pagination'=>$this->paginationSettings,
		));
		
		$this->render('results',
			array(
				'dataProvider' => $resultsDataProvider,
		));
	
	}
	
	/**
	 * Takes in the $_POST form and then redirects the user to the results.
	 */
	public function actionSearchConstructor()
	{
		
		/*
		 * Constructor for the SQL query. The query is created on multiple passes on the for loop,
		 * each iteration of the loop adds more to the query. The $haystack array contains
		 * names of every column that should be available for search.
		 */
		
		// creates the database criteria object
		$searchCriteria = new CDbCriteria;
		// another object, will be later on merged with the first object to create
		// x AND x AND (x OR x OR x OR) structure, the OR part coming from the textSearchCriteria
		$textSearchCriteria = new CDbCriteria;
		
		/*
		 * Constructs the portion of the query which combines the 'kohteet' table and the 'diat' table.
		 */
		if(strlen($_POST['kohde'])>0)
		{
			$parseThis = $_POST['kohde'];
			$parseThis = str_replace(' ','',$parseThis);
			// getting rid of capital letters
			$parsedKohde = strtolower($parseThis);
			// the textField is a single field which reads building value which is stored in two fields
			// so we need to parse the input
			$saari = substr($parsedKohde,0,1);
			if(count($_POST['kohde'])==2) {
				$rakennus = substr($parsedKohde,1,1); }
			else {
				$rakennus = substr($parsedKohde,1,3); }
			$searchCriteria->join = 'JOIN kohteet ON kohteet.cdno = t.cd AND kohteet.idno = t.id';
			$searchCriteria->addCondition('kohteet.saari=:saari');
			$searchCriteria->params[':saari'] = $saari;
			// if there is no building information, only the island code eg 'a'
			if($rakennus!='')
			{
				$searchCriteria->params[':rakennus'] = $rakennus;
				$searchCriteria->addCondition('kohteet.rakennus=:rakennus');
			}
		}
		
		$searchCriteria->distinct = true;
		
		$haystack = Image::model()->searchableFields();
			// array of column names in the tables database
		
		for($i=0;$i<count($haystack);$i++) // the for loop is repeated for every field in the
			// ->searchableFields array, so every form element is included in the search
		{
			
			// if an field is left empty at the search form, nothing from it will be added to the query
			if (!empty($_POST['Image'][$haystack[$i]]))
			{
				// adds padding to the cd and id fields, searching in input for '4' pads it with zeroes so images from cd '0004' are returned
				if ($haystack[$i] == 'cd' || $haystack[$i] == 'id')
				{
					$searchCriteria->addCondition($haystack[$i] . '=:' . $haystack[$i]);
					$searchCriteria->params[':' . $haystack[$i]] = str_pad($_POST['Image'][$haystack[$i]],4,'0',STR_PAD_LEFT);
					continue;
				}

				// 'valokuvaaja' is searched as a exact string
				if ( $haystack[$i] == 'valokuvaaja')
				{
					$searchCriteria ->addSearchCondition('valokuvaaja',$_POST['Image']['valokuvaaja'],true,'AND','ILIKE');
					continue;
				}
				
				// individual fields such as kuvateksti are set in a different way in this if structure
				if( $haystack[$i] == 'kuvateksti' )
				{
					// running the user input to $textwords, while replacing commas and equivalent with spaces
					$textwords = str_replace(array(',',';',':'), ' ',$_POST['Image'][$haystack[$i]]);
					// splitting the search words to array, with individual words in it
					$textwords = explode(' ',$textwords);
					
					for($w=0;$w<count($textwords);$w++)
					{
						// getting rid of commas and dots, in case someone decides to use them
						$textwords[$w] = str_replace(array(',',';',':'), '', $textwords[$w]);
						// adds in a search condition
						$textSearchCriteria->addSearchCondition(
							'kuvateksti', // the column name (or a valid SQL expression)
							$textwords[$w], // the search keyword, affected by next parameter
							true, // whether the keyword should be escaped if it contains % or _ , when this parameter
								 // is true the special characters % or _ will be escaped and the keyword will be surrounded
								 // by % character on both ends.
							'AND', // pick AND or OR SQL operator
							'ILIKE' // case insensitive LIKE
						);
						// same as before, except in place of 'kuvateksti' is 'diateksti' , so both colums are searched based on the text search input
						// $textSearchCriteria->addSearchCondition('diateksti', $textwords[$w], true, 'OR', 'ILIKE' );
					}
					continue; // skips the addCondition and params below, so the SQL is not doubled
				}
				
				// this is done for all the 'tag' type values such as 'valokuva'
				$searchCriteria->addCondition($haystack[$i] . '=:' . $haystack[$i]);
					// adds condition such as 'valokuva=:valokuva'
				$searchCriteria->params[':' . $haystack[$i]] = $_POST['Image'][$haystack[$i]];
					// adds the parameter to the parameter array, params array(':valokuva'=>'valokuva')
						
			} // the if(!empty($_POST['Image'])) -statement ends
			
		} // the for loop ends
		
		// adds a query condition which filters out deleted images
		$searchCriteria->addCondition('deleted=false');
		
		// how the images are sorted
		// $searchCriteria->order = 'imageid ASC';
		$searchCriteria->order = $_POST['searchsort'];
		
		/*
		 * Criteria for the date values. If the 'aikavali' checkbox is checked, both fields are used
		 * and the query is made for dates between the two inserterd date. If the 'aikavali' is unchecked
		 * only the exact date will be searched. Uninserted date, month and year values are treated as wildcards '_'.
		 */
		if(isset($_POST['aikavali']))
		{	
			// if the between criteria is used, the '00' values are added instead of the wildcard '_' values				
			$dateOne = $this->parseDateInput($_POST['dayOne'], $_POST['monthOne'], $_POST['yearOne']);
			$dateTwo = $this->parseDateInput($_POST['dayTwo'], $_POST['monthTwo'], $_POST['yearTwo'],true);
			$searchCriteria->addBetweenCondition('pvm',$dateOne,$dateTwo);	
		}
		else if(isset($_POST['tarkkaaika']))
		{
			$searchDate = $this->parseDateInput($_POST['dayOne'], $_POST['monthOne'], $_POST['yearOne'],true);
			$searchCriteria->addSearchCondition('pvm',$searchDate,false);
		}
		
		// merges the two criterias
		$searchCriteria->mergeWith($textSearchCriteria);
		
		// stores the searchCriteria so that the dataprovider can be created again
		Yii::app()->user->setState('imagesearchcriteria', $searchCriteria);		
		
		$this->redirect(array('results'));
		
	}
	
	/**
	 * Takes in the values inputted to the search fields. It then replaces
	 * blank values with wildcards and returns a string which corresponds
	 * with what's saved in the database.
	 * The $wild boolean allowes and disables using SQL wildcards.
	 * @param string $day
	 * @param string $month
	 * @param string $year
	 * @param boolean $wild
	 * @return string
	 */
	public function parseDateInput($day,$month,$year,$wild=false)
	{
		if($wild)
		{
			if($day=='') $day = '__';
			if($month=='') $month = '__';
			if($year=='') $year = '____';
		}
		$day = str_pad($day,2,"0",STR_PAD_LEFT);
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$year = str_pad($year,4,"0",STR_PAD_LEFT);
		return($year . $month . $day);
	}
	
	/**
	 * Renders the browse view and caluclates required values for the
	 * roller (image roll at the page top).
	 */
	public function actionBrowse()
	{
		// the browse view requires information on the basket contents
		$testBasket = Yii::app()->user->getState('basketContents');
		if(!isset($testBasket))
		{
			Yii::app()->user->setState('basketContents',array());
		}
		
		// the user can't access ?r=image/browse without the offset value set
		if(!isset($_GET['browseOffset']))
			$this->redirect(array('search'));
			
		$currentlyviewedrow = $_GET['browseOffset'];
		
		// if someone tries to manually enter an invalid number, negative, it's set to 0
		if($currentlyviewedrow<0) $currentlyviewedrow = 0;
		
		// next and prev variables set.
		$next = $currentlyviewedrow + 1;
		$prev = $currentlyviewedrow - 1;
		
		$rollCriteria = Yii::app()->user->getState('imagesearchcriteria');		
		
		$rollCriteria->offset = $currentlyviewedrow - 4;

		if($currentlyviewedrow<5)
		{
			$rollCriteria->limit = $currentlyviewedrow + 5;
		}
		else
		{
			$rollCriteria->limit = 9;
		}
		
		$rollDataProvider = new CActiveDataProvider('Image',array(
			'criteria'=>$rollCriteria,
			'Pagination'=>false,
		));

		$totalAmount = $rollDataProvider->getTotalItemCount();
		
		$this->render('browse',
			array(
				'rollDataProvider'=>$rollDataProvider,
				'browseOffset'=>$currentlyviewedrow,
				'next'=>$next,
				'prev'=>$prev,
				'totalAmount'=>$totalAmount,
			)
		);
		
	}
	
	/**
	 * completes the edit function, model save and render the original metadatabox,
	 * which doesn't have the edit form in it
	 */
	public function actionCompleteEditImage()
	{
		$saveThis = Image::model()->findByPk($_POST['Image']['imageid']);

		$saveableThings = Image::model()->allowedSaves();
		
		$tags=Image::model()->tags();
		foreach($tags as $tag)
		{
			if ($_POST['Image'][$tag] == true)
			{
				$_POST['Image'][$tag] = 'k';
			}
			else 
			{
				$_POST['Image'][$tag] = 'e';
			}
		}
		
		// parsing the date from the 3 input fields
		$saveThis->pvm = $this->parseDateInput($_POST['day'], $_POST['month'], $_POST['year']);
		
		/*
		 * For details on this for loop structure and the contents of $saveableThings,
		 * go see the actionSearch. This is copy pasted and modified from it.
		 */
		for($i=0;$i<count($saveableThings);$i++)
		{	
			if (!is_null($_POST['Image'][$saveableThings[$i]]))
			{
				$saveThis->$saveableThings[$i] = $_POST['Image'][$saveableThings[$i]];
			}
		}
		
		$saveThis->save();
		
		$this->actionBuildingSave($_POST['kohteet'], $saveThis->imageid, $saveThis->cd, $saveThis->id);
				
		if(isset($_GET['id']))
		{
			$this->renderPartial('_metadatabox',array(
					'dataProvider'=>$saveThis,
					'hideimage'=>true,
				),
				false, 	// "whether the rendering result should be returned instead of being displayed ot end users"
				false	// "whether the rendering result should be postprocessed using processOutput"
			);
		
			$this->renderPartial('_imageControlBox',array(
				'dataProvider'=>$saveThis,
			)); 	
			
		}
	}
	
	/**
	 * Saves metadata from the fastedit form to the image.
	 */
	public function actionFastSave()
	{
		if(isset($_GET['imageid']))
		{
			$basketContents[0] = $_GET['imageid']; 				
		} 
		else 
		{
			$basketContents = Yii::app()->user->getState('basketContents');
		}
		
		$saveableThings = Image::model()->searchableFields();
		
		for($basketItem=0;$basketItem<count($basketContents);$basketItem++)
		{
			
			$saveThis = Image::model()->findByPk($basketContents[$basketItem]);
			
			$date = $this->explodeDate($saveThis->pvm);
			if($_POST['day']!='')
				$date['day'] = $_POST['day'];
			if($_POST['month']!='')
				$date['month'] = $_POST['month'];
			if($_POST['year']!='')
				$date['year'] = $_POST['year'];
			$saveThis->pvm = $this->parseDateInput($date['day'], $date['month'], $date['year']);								

			/*
			 * For details on this for loop structure and the contents of $saveableThings,
			 * go see the actionSearch. This is copy pasted and modified from it.
			 */
			for($i=0;$i<count($saveableThings);$i++)
			{	
				if (!empty($_POST['Image'][$saveableThings[$i]]))
				{
					$saveThis->$saveableThings[$i] = $_POST['Image'][$saveableThings[$i]];
				}		
			}
			
			/*
			 * Adds or replaces the 'kuvateksti' field.
			 * the first if determines if the user wants to add to the 'kuvateksti' or replace the entire thing
			 */
			if($_POST['addOrReplaceKuvateksti'])
			{
				// if it's blank, it will be ignored
				if($_POST['kuvateksti'] != '')
				{
					// if the user wants to replace and the text field is not blank, it'll be added
					$saveThis->kuvateksti = $_POST['kuvateksti'];
				}
			}
			else 
			{
				// determines if the strings have equal ends, this is to avoid user inputting the same string twice
				$incrementThis = $saveThis->kuvateksti;
				$endOfIncrementThis = substr($incrementThis, strlen($incrementThis) - strlen($_POST['kuvateksti']));
				// if the ends are not equal, the 'kuvateksti' will be incremented with the inputted string
				if($_POST['kuvateksti'] != $endOfIncrementThis)
				{
					$incrementThis = $incrementThis . ', ' . $_POST['kuvateksti'];
					if($saveThis->kuvateksti == '')
					{
						$incrementThis = $_POST['kuvateksti'];
					}
					$saveThis->kuvateksti = $incrementThis;					
				}
			}
			
			/*
			 * Adds or replaces the 'kohteet' for the image.
			 * Adds or replaces based on the radiobuttonlist.
			 */
			if($_POST['addOrReplaceRakennus'])
			{
				// if it's blank, it will be ignored
				if($_POST['kohteet'] != '')
				{
					$this->actionBuildingSave($_POST['kohteet'], $saveThis->imageid, $saveThis->cd, $saveThis->id,false);
				}
			}
			else 
			{
				// if it's blank, it will be ignored
				if($_POST['kohteet'] != '')
				{				
					$this->actionBuildingSave($_POST['kohteet'], $saveThis->imageid, $saveThis->cd, $saveThis->id,true);
				}				
			}
			
			// saves input from the buildings
			$saveThis->save();
		}
	}
	
	/**
	 * Prints out a neat list of the buildings associated with an image.
	 * Reads the imageid and
	 * @return string with the building list; "A1 , A2"
	 */
	public function actionBuildingList($kohteetobject)
	{
		$cleanprint = '';
		for($i=0;$i<count($kohteetobject);$i++)
		{
			if ($i != 0) $cleanprint = $cleanprint . ', ';
			// for some reason there's a space in the database after the building number, eg "2 ". this removes it.
			$house = str_replace(' ', '', $kohteetobject[$i]->rakennus);
			$cleanprint = $cleanprint . $kohteetobject[$i]->saari . $house;
		}
		// $cleanprint = str_replace(' ', '', $cleanprint);
		$cleanprint = strtoupper($cleanprint);
		return $cleanprint;
	}
	
	/**
	 * Saves buildings. First all will be deleted and then the new or earlier existing ones will
	 * be added again. This prevents a need for comparison.
	 */
	public function actionBuildingSave($kohteetString,$imageid,$cd,$id,$addTo=false)
	{
		// if we are not adding to the previous values, but instead are replacing
		if (!$addTo)
		{
			// clearing out all the 'kohteet' for the image
			Building::model()->deleteByPk($imageid);
		}
		// getting rid of spaces in the inputstring
		$kohteetString = str_replace(' ', '', $kohteetString);
		// splits the inputted string into an array, so each building can be handled on it's own
		$kohteetArray = explode(',',$kohteetString);
		// this repeated for the kohteetArray, so that every building is it's own row in the kohteet table
		foreach($kohteetArray as $kohde)
		{
			$saari = preg_replace('/[0-9k-z]/','',$kohde);
	    	$rakennus = preg_replace('/[^0-9k-z]/','',$kohde);
	    	// converting saari letter to uppercase
	    	$saari = strtolower($saari);
	    	if(strlen($saari)<1) { continue; }
			
			// if we are adding, checks the database if the building&image combination is already there, will not add a duplicate
			if($addTo)
			{
				$duplicateCriteria = new CDbCriteria;
				$duplicateCriteria->condition = 'cdno=:cdno';
				$duplicateCriteria->addCondition('idno=:idno');
				$duplicateCriteria->addCondition('saari=:saari');
				$duplicateCriteria->addCondition('rakennus=:rakennus');
				$duplicateCriteria->params = array(
					':cdno'=>$cd,
					':idno'=>$id,
					':saari'=>$saari,
					':rakennus'=>$rakennus,
				);
				// if a it's already found in the database, will continue; to the the next kohde
				if(Building::model()->find($duplicateCriteria))
					continue;
			}

	    	$newKohde = new Building;
	    	$newKohde->cdno = $cd;
	    	$newKohde->idno = $id;
	    	$newKohde->saari = $saari;
	    	$newKohde->rakennus = $rakennus;
	    	$newKohde->fk = $imageid;
	    	$newKohde->save();
	    	unset($newKohde);
		}
	}
	
	/**
	 * Updates the metadatabox. Two different ones for different views; the fastedit one has image hidden.
	 */
	public function actionAjaxupdateview()
	{
		if(isset($_GET['fastedit']))
		{
			$this->renderPartial('_metadatabox',array(
				'dataProvider'=>Image::model()->findByPk($_GET['id']),
				'fastedit'=>true,
			));				
		}
		else 
		{
			$this->renderPartial('_metadatabox',array('dataProvider'=>Image::model()->findByPk($_GET['id']),));
		}				
	}
	
	/**
	 * Deletes an image. The image column value 'deleted' in the database will be set as 'true',
	 * the image metadata itself in the database will be left as is.
	 * The image files on the hard drive will be moved to an alternative location where they can
	 * possible be recovered from later on. The original files from cd/img*id*.jpg and their 
	 * associated thumbnails will be moved to kuvat/deleted and it's subdirectories.
	 */
	public function actionDelete()
	{
		// setting the delete tag as false
		$deleteObject = Image::model()->findByPk($_GET['imageid']);
		$deleteObject->deleted = true;
		$deleteObject->save();
		
		// link to the physical image file
		$deleteMe = $deleteObject->getImageFile('full', false, Image::FULLIMAGEPATH);
			
		// first the existance of the file has to be evaluated in order to avoid an php error
		if( file_exists( $deleteMe ) )
			rename( 
				// source
				$deleteMe,
				// target 
				Image::DELETEDIMAGEPATH .  $deleteObject->imageid . '.' . $deleteObject->tiedostotyyppi		
		);
		
		$deleteObject->deleteImage(array('small', 'light', 'medium', 'large'));
		
		$this->redirect(Yii::app()->user->returnUrl);
		
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Image::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='image-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
