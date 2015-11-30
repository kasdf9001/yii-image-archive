<?php
/**
 * This is the model class for table "diat".
 *
 * The followings are the available columns in table 'diat':
 * @property string $cd
 * @property string $id
 * @property string $pvm
 * @property string $valokuvaaja
 * @property string $julkaisuvapaa
 * @property string $valokuva
 * @property string $maalaus
 * @property string $piirustus
 * @property string $ulkokuva
 * @property string $sisakuva
 * @property string $ilmakuva
 * @property string $historiallinen
 * @property string $tyomaa
 * @property string $esittely
 * @property string $ihmisia
 * @property string $linnoituslaitteet
 * @property string $kuvateksti
 * @property string $diateksti
 * @property string $kartta
 * @property string $tiedostotyyppi
 * @property string $aikavarma
 */
class Image extends CActiveRecord
{

	/**
	 * const for the k / e solution in the database
	 */
	const TYPE_TRUE='k';
	const TYPE_BLANK='';	
	const TYPE_FALSE='e';
	
	/**
	 * Constants related to the physical locations of images.
	 * The constant name should be self describing.
	 */
	const IMAGEPATH='/slhkimages/';
	const FULLIMAGEPATH='/var/www/public_html/slhkimages/';
	const FULLIMAGETEMPPATH='/tmp/';
	const DELETEDIMAGEPATH='/var/www/public_html/slhkimages/deleted/';
	
	/**
	 * Returns a URL or path for a certain image, based on the imageid, and the desired size of the image.
	 * @param object $image the imageobject for which the 
	 * @param string $size
	 * @param boolean $tag
	 * @param string $path
	 */
	public function getImageFile($size,$tag=false,$path=Image::IMAGEPATH)
	{
		
		switch($size) 
		{
			case 'small':
				$directory = '96x64/'; 
				$extension = '.jpg';	
				break;
			case 'light':
				$directory = '192x128/';
				$extension = '.jpg';
				break;
			case 'medium':
				$directory = '384x256/'; 
				$extension = '.jpg';	
				break;
			case 'large':
				$directory = '768x512/'; 
				$extension = '.jpg';	
				break;
			case 'full':
				$directory = '';
				$extension = '.' . $this->tiedostotyyppi;
		}
		
		// constructs the path to the image, including image filename
		$filename = 'img' . $this->id . $extension;
		$imageLocation = $path . $this->cd . '/' . $directory . $filename;
						
		// this is if we want a image tag, instead of the plain url.
		if ($tag)
		{
			// the alt text is trunctuated in order to not take too much space
			$shortAlt = mb_substr($this->kuvateksti,0,70,'UTF-8');
			// the function can also return the image file path as an URL
			// it's possible to force disable browser caching : $imageLocation . '?' . uniqid()
			$imageLocation = CHtml::image($imageLocation,$shortAlt);
		}
		
		return $imageLocation;
	}
	
	/**
	 * Generates thumbnails based on the given imageid.
	 * @param object $image, the image object for which the thumbnails should be generated 
	 */
	public function generateThumbnails()
	{

		// path to the full size image
		$fullImage = $this->getImageFile('full', false, Image::FULLIMAGEPATH);
		
		// first the 768x512 the other images are based on
		$largeImage = $this->getImageFile('large', false, Image::FULLIMAGEPATH);
		exec('convert -geometry 768x768 ' . $fullImage . ' ' . $largeImage);
		
		// the thumbnails are generated in the for loop based on the 'large' image
		$geometry = array('96x96', '192x192', '384x384');
		$resizesubdirs = array('small', 'light', 'medium');
		for($i=0;$i<3;$i++)
		{
			$destination = $this->getImageFile($resizesubdirs[$i], false, Image::FULLIMAGEPATH);
			exec('convert -geometry ' . $geometry[$i] . ' ' . $largeImage . ' ' . $destination);
		}
		
	}
	
	/**
	 * Deletes image files.
	 * @param object $image the image object whose physical images are deleted
	 * @param array $deletesizes the sizes of images which should be deleted
	 */
	public function deleteImage($deleteSizes)
	{
		for($i=0;$i<count($deleteSizes);$i++)
		{
			$file = $this->getImageFile($deleteSizes[$i],false,Image::FULLIMAGEPATH);
			if(file_exists($file)) { unlink($file); }
		}
	}
	
	/**
	 * @return types of tags, k=true, e=false
	 */
	public function getTypeOptions()
	{
		return array(
			self::TYPE_TRUE=>'kyllä',
			self::TYPE_BLANK=>'',			
			self::TYPE_FALSE=>'ei',
		);
	}
	
	/**
	 * SQL ORDER BY clause strings the user can sort the results
	 * based on.
	 */
	const TYPE_PVMASC='pvm ASC';
	const TYPE_PVMDESC='pvm DESC';
	const TYPE_CDIDASC='cd ASC, id ASC';
	const TYPE_CDIDDESC='cd DESC, id DESC';
	
	/**
	 * Sorting option labels.
	 */ 
	public function getSortOptions()
	{
		return array(
			self::TYPE_PVMASC=>'kuvauspäivä, vanhin ensin',
			self::TYPE_PVMDESC=>'kuvauspäivä, uusin ensin',
			self::TYPE_CDIDASC=>'tietokantajärjestys, vanhin ensin',
			self::TYPE_CDIDDESC=>'tietokantajärjestys, uusin ensin',
		);
	}
	
	
	/**
	 * variable for the nextCdId, contains the retunred value where
	 * is told which CD and ID values are for the next image to be inserted.
	 */
	public $max;
	
	/**
	 * nextCdId gives the cd and id values for the next uploaded image,
	 * as in, the first blank spot on the database
	 * @return array with cd and id which are the next blank row on the database
	 */
	public function nextCdId($cdbox) {
		// Constructs a criteria which selects the maximum cd column value
		$criteria = new CDbCriteria();
		$criteria->select = "MAX(cd) as max";
		$maxcd = Image::model()->find($criteria);
		
		// if the user has defined his cdbox, the defined cd will be used
		if($cdbox!='false') 
		{
			$maxcd->max = str_pad($cdbox,4,'0',STR_PAD_LEFT);
		}
		
		// Constructs a criteria which selects the maximum id column value WITH the max cd value
		$idcriteria = new CDbCriteria();
		$idcriteria->select 	= "MAX(id) as max";
		$idcriteria->condition  = 'cd=:cd';
		// the cd value must be the highest cd value
		$idcriteria->params		= array(':cd' => $maxcd->max);
		$maxid = Image::model()->find($idcriteria);		
		
		// calculates the cd and id values for the next image, always one more to id
		// and if id is 9999, spins the cd value to the next higher and id to 0001
		if($maxid->max==9999)
		{
			// this must be as a string value, if inserted as 0001 the leading zeroes will
			// disappear, leading to malfunctioning filenames
			$nextid = '0001';
			$nextcd = $maxcd->max + 1;
		}
		else
		{
			$nextcd = $maxcd->max;
			// as above, this needs padding, leading zeroes are added	
			$nextid = $maxid->max + 1;
			$nextid = str_pad($nextid,4,'0',STR_PAD_LEFT);
		}
		
		return array(
			'cd' => $nextcd,
			'id' => $nextid,	
		);
	}		
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Image the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'diat';
	}
	
	/**
	 * @return primary key
	 */
	public function primaryKey()
	{
		return 'imageid';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cd, id', 'required'),
			array('imageid', 'numerical', 'integerOnly'=>true),
			array('cd, id', 'length', 'max'=>4),
			// fixme possibly causes error (stuff below)
			// array('cd, id', 'numerical', 'integerOnly'=>true, 'message'=>'Integers only on the CD ID field!'),
			array('pvm', 'length', 'max'=>8 ),
			// The Image tags, length is max 1 as the decided format to state true / false are
			// the 'k' and 'e' characters, 'k' stating 'true' and 'e' states 'false'
			array('julkaisuvapaa, valokuva, maalaus, piirustus, ulkokuva, sisakuva, ilmakuva, historiallinen, tyomaa, esittely, ihmisia, linnoituslaitteet, kartta, aikavarma', 'length', 'max'=>1),
			array('valokuvaaja, kuvateksti, diateksti, tiedostotyyppi, kohde', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cd, id, pvm, valokuvaaja, julkaisuvapaa, valokuva, maalaus, piirustus, ulkokuva, sisakuva, ilmakuva, historiallinen, tyomaa, esittely, ihmisia, linnoituslaitteet, kuvateksti, diateksti, kartta, tiedostotyyppi, aikavarma, kohde', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'kohteet' => array(self::HAS_MANY, 'Building', 'fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cd' => 'CD',
			'id' => 'ID',
			'pvm' => 'Pvm',
			'valokuvaaja' => 'Valokuvaaja',
			'julkaisuvapaa' => 'Julkaisuvapaa',
			'valokuva' => 'Valokuva',
			'maalaus' => 'Maalaus',
			'piirustus' => 'Piirustus',
			'ulkokuva' => 'Ulkokuva',
			'sisakuva' => 'Sisäkuva',
			'ilmakuva' => 'Ilmakuva',
			'historiallinen' => 'Historiallinen',
			'tyomaa' => 'Työmaa',
			'esittely' => 'Esittely',
			'ihmisia' => 'Ihmisiä',
			'linnoituslaitteet' => 'Linnoituslaitteet',
			'kuvateksti' => 'Kuvateksti',
			'diateksti' => 'Diateksti',
			'kartta' => 'Kartta',
			'tiedostotyyppi' => 'Tiedostotyyppi',
			'aikavarma' => 'Tarkka kuvausaika',
			'imageid' => 'Imageid',
			'kohde' => 'Rakennus',
		);
	}
	/**
	 * @return array with every field that should be present in the search form
	 * The user can search based on something in this list.
	 */
	public function searchableFields()
	{
		return array(
			'cd',
			'id',
			'pvm',
			'valokuvaaja',
			'julkaisuvapaa',
			'valokuva',
			'maalaus',
			'piirustus',
			'ulkokuva',
			'sisakuva',
			'ilmakuva',
			'historiallinen',
			'tyomaa',
			'esittely',
			'ihmisia',
			'linnoituslaitteet',
			'kuvateksti',
			// 'diateksti',
			'kartta',
			// 'tiedostotyyppi',
			'aikavarma',
			'kohde',
		);
	}
	
	/**
	 * List of allowed saves. 
	 */
	public function allowedSaves()
	{
		return array(
			'valokuvaaja',
			'julkaisuvapaa',
			'valokuva',
			'maalaus',
			'piirustus',
			'ulkokuva',
			'sisakuva',
			'ilmakuva',
			'historiallinen',
			'tyomaa',
			'esittely',
			'ihmisia',
			'linnoituslaitteet',
			'kuvateksti',
			'kartta',
			'aikavarma',
		);
	}
	
	/**
	 * Defines which of the columns in the 'diat' table are tag type values.
	 * @return array of the tags 
	 */
	public function tags()
	{
		return array(
			'ulkokuva',
			'sisakuva',
			'tyomaa',
			'ihmisia',
			'linnoituslaitteet',
			'julkaisuvapaa',
			'esittely',
			'ilmakuva',
			'historiallinen',
			'valokuva',
			'maalaus',
			'piirustus',
			'kartta',
			'aikavarma',
		);	
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('cd',$this->cd,true);

		$criteria->compare('id',$this->id,true);

		$criteria->compare('pvm',$this->pvm,true);

		$criteria->compare('valokuvaaja',$this->valokuvaaja,true);

		$criteria->compare('julkaisuvapaa',$this->julkaisuvapaa,true);

		$criteria->compare('valokuva',$this->valokuva,true);

		$criteria->compare('maalaus',$this->maalaus,true);

		$criteria->compare('piirustus',$this->piirustus,true);

		$criteria->compare('ulkokuva',$this->ulkokuva,true);

		$criteria->compare('sisakuva',$this->sisakuva,true);

		$criteria->compare('ilmakuva',$this->ilmakuva,true);

		$criteria->compare('historiallinen',$this->historiallinen,true);

		$criteria->compare('tyomaa',$this->tyomaa,true);

		$criteria->compare('esittely',$this->esittely,true);

		$criteria->compare('ihmisia',$this->ihmisia,true);

		$criteria->compare('linnoituslaitteet',$this->linnoituslaitteet,true);

		$criteria->compare('kuvateksti',$this->kuvateksti,true);

		$criteria->compare('diateksti',$this->diateksti,true);

		$criteria->compare('kartta',$this->kartta,true);

		$criteria->compare('tiedostotyyppi',$this->tiedostotyyppi,true);

		$criteria->compare('aikavarma',$this->aikavarma,true);

		$criteria->compare('imageid',$this->imageid,true);

		$criteria->compare('kohde',$this->kohde,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}