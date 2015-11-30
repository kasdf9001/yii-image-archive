<?php

/**
 * This is the model class for table "kohteet".
 *
 * The followings are the available columns in table 'kohteet':
 * @property string $cdno
 * @property string $idno
 * @property string $saari
 * @property string $rakennus
 */
class Building extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Building the static model class
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
		return 'kohteet';
	}
	
	/**
	 * FIXME -> retarded PRIMARY KEY TO STOP YII FROM CRYING
	 */
	public function primaryKey()
	{
		return 'fk';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cdno, idno, rakennus', 'length', 'max'=>4),
			array('saari', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('cdno, idno, saari, rakennus', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		// kuvalla on kohteita, palautuvat array:ssä, kohde[0], kohde[1]
		return array(
			// 'containerimage' => array(self::BELONGS_TO, 'Image', 'fk',),
			// The relation needs to be only in the model the relation is accessed from.
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cdno' => 'Cdno',
			'idno' => 'Idno',
			'saari' => 'Saari',
			'rakennus' => 'Rakennus',
			'fk' => 'primaryKey'
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

		$criteria->compare('cdno',$this->cdno,true);

		$criteria->compare('idno',$this->idno,true);

		$criteria->compare('saari',$this->saari,true);

		$criteria->compare('rakennus',$this->rakennus,true);

		return new CActiveDataProvider('Building', array(
			'criteria'=>$criteria,
		));
	}
}