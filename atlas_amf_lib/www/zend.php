<?php

	require_once 'Zend/Amf/Server.php';
	//require_once '../config/dbconf.php';
	define("DATABASE_SERVER", "localhost");
	define("DATABASE_USERNAME", "equidna_caem");
	define("DATABASE_PASSWORD", "c@3m");
	define("DATABASE_NAME", "equidna_caem");
	mysql_pconnect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD);
	mysql_selectdb(DATABASE_NAME);
	
	require_once('../includes/aux.php');
	require_once('../atlas/controller/catalogosGeograficos.php');
	require_once('../atlas/controller/catalogosGenerales.php');
	require_once('../atlas/controller/eventos.php');
	require_once '../atlas/controller/usuarios.php';
	require_once('../atlas/controller/geo.php');
	
	$server = new Zend_Amf_Server();
	//adding our class to Zend AMF Server
	$server->setClass("catalogosGeograficos");
	$server->setClass("catalogosGenerales");
	$server->setClass("eventoController");
	$server->setClass("userController");
	$server->setClass("geoController");
	//
	//you don't have to add the package name
	$server->setClassMap("cuencaCAEM", "cuencaCAEM");
	$server->setClassMap("gerenciaCAEM", "gerenciaCAEM");
	$server->setClassMap("municipioCAEM", "municipioCAEM");
	$server->setClassMap("localidadCAEM", "localidadCAEM");
	$server->setClassMap("sitioCAEM", "sitioCAEM");
	$server->setClassMap("basicItemCAEM", "basicItemCAEM");
	$server->setClassMap("directorioItemCAEM", "directorioItemCAEM");
	$server->setClassMap("accionEventoCAEM", "accionEventoCAEM");
	$server->setClassMap("eventoCAEM", "eventoCAEM");
	$server->setClassMap("usuarioCAEM", "usuarioCAEM");	
	$server->setClassMap("geoPoly", "geoPoly");
	$server->setClassMap("geoPoint", "geoPoint");
	
	echo $server->handle();

?>