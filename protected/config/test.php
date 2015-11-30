<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
				// 'connectionID' => 'db',
			),
			
			/**
			 * database connection for test database
			 */ /*
			'db'=>array(
				'connectionString' => 'pgsql:host=localhost;port=5432;dbname=kuvattest',
				// 'emulatePrepare' => true,
				'username' => 'postgres',
				'charset' => 'utf8'
			), */

		),
	)
);
