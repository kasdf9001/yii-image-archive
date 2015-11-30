<?php 

/**
 * Uploader script. 
 * Note that the scripts are in the protected/ directory, and that the .css and .js files
 * must be accessible by the user.
 */
$uploaderPath = '/protected/extensions/fileuploader/';
Yii::app()->getClientScript()->registerScriptFile( Yii::app()->baseUrl . $uploaderPath . 'client/fileuploader.js');
Yii::app()->getClientScript()->registerCssFile( Yii::app()->baseUrl . $uploaderPath . 'client/fileuploader.css');

?>

<div id="basketcount" style="margin:30px 0px -27px 10px;">
	<a href="ohje.html">Ohjeet kuvien lisäämiseen</a>
</div>

<div id="file-uploader">       
	<noscript>          
		<p>Javascript puuttuu, kuvien lisääminen ei toimi.</p>
		<!-- or put a simple form for upload here -->
	</noscript>         
</div>

<?php 

if(isset($_GET['cdbox']))
{
	echo('<h1>Kuvat menevät laatikkoon ' . str_pad($_GET['cdbox'],4,'0',STR_PAD_LEFT) . '!</h1>');
}

?>

<?php 

/*
 * This bit allows the user to set the cd value where the images will be uploaded. 
 * The user does not have interface, in order to hide the function. The user must specify
 * &cdbox=??? $_GET lvalue in order to upload to a custom box.
 */
if(isset($_GET['cdbox']))
{
	$uploadurl = CHtml::normalizeUrl(array('image/upload','cdbox'=>$_GET['cdbox']));
}
else 
{
	$uploadurl = CHtml::normalizeUrl(array('image/upload','cdbox'=>'false'));
}

Yii::app()->clientScript->registerScript('uploader',"
    var uploader = new qq.FileUploader({
        // pass the dom node (ex. $(selector)[0] for jQuery users)
        element: document.getElementById('file-uploader'),
        // path to server-side upload script
        action: '" . $uploadurl . "',
        debug: true,
    }); 
");

?>