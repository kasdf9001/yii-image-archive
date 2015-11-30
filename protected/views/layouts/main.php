<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />	
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/favicon.png" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

	<div id="mainmenu">
		<?php 
			// if the browseoffset is set, we calculate which page the user should go 
			// to by using the "hakutulokset" button
			if(isset($_GET['browseOffset']))
			{
				$resultsPageNumber = $this->calculateResultsPage($_GET['browseOffset']);
				$resultsPage = array('image/results','Image_page'=>$resultsPageNumber);
			}
			else
			{
				$resultsPage = array('image/results');
			}
			
			$this->widget('zii.widgets.CMenu',array(
				'items'=>array(
					array('label'=>'Hae kuvia', 'url'=>array('image/search')),
					array('label'=>'Hakutulokset', 'url'=>$resultsPage),
					array('label'=>'Kuvien muokkaustila', 
						'url'=>array('image/fast'),
						'visible'=>!Yii::app()->user->isGuest
					),
					array('label'=>'Lisää kuvia arkistoon', 
						'url'=>array('image/add'),
						'visible'=>!Yii::app()->user->isGuest
					),
					array('label'=>'Kirjaudu sisään',
						'url'=>Yii::app()->user->loginUrl,
						'visible'=>Yii::app()->user->isGuest
					),
					// this one displays the username in the button
					// array('label'=>'Kirjaudu ulos (' . Yii::app()->user->name . ')',
					array('label'=>'Kirjaudu ulos', 
						'url'=>array('site/logout'),
						'visible'=>!Yii::app()->user->isGuest
					),
				),
				'lastItemCssClass'=>'menuright',
			)); 
		?>
	</div><!-- mainmenu -->	
	
	<div style="clear:both;"></div>
		
	<?php echo $content ?>

</body>
</html>