<?php 
/* 
	CONFIGURACIÓN INICIAL 
	$dsn : Datos de la base de datos. 
		- SQLite: 		'sqlite://./path/to/database.sqlite';
		- MySQL: 		'mysql://[user[:pass]@]host[:port]/db/;
		- PostgreSQL: 	'pgsql://[user[:pass]@]host[:port]/db/;
	$token(opcional) :  Pueden generarlo de aquí: http://randomkeygen.com/ debe ser enviado como parámetro GET.
	$clients(opcional) : Si el cliente tiene IP fija puede agregarse aquí para mejor seguridad.
*/
$dsn = ''; 
$token = '';
$clients = array
(
);