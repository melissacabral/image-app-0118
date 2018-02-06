<?php
//comment this out when you need to debug something:
error_reporting( E_ALL & ~E_NOTICE ); 

//connect to the DB
$database_name 	= 'melissa_image_app_0118';
$username 		= 'mmc_imageapp0118';
$password 		= 'pncnKUrnx3JzyVB8';
$host 			= 'localhost';

$db = new mysqli( $host, $username, $password, $database_name );

//check for errors
if( $db->connect_errno > 0 ){
	die('Error connecting to Database');
}

//Define the Absolute URL. Use for Links, like <a> and <img>
define( 'SITE_URL', 'http://localhost/melissa/php/melissa-php-0118/image-app' );

//Define the File Root. Use for include(), and file upload stuff
define(	'FILE_PATH', 'C:\xampp\htdocs\melissa\php\melissa-php-0118\image-app' );

//Security!
define('SALT', 'mchv42hjsl,nfy65hkoh547982#@bmb%&*(cbnd562');
//no close