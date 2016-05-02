<?php
//Configurações de Path
define("MAIN_PATH", realpath(__DIR__));
define("LIB_PATH", MAIN_PATH . DIRECTORY_SEPARATOR . "Lib" . DIRECTORY_SEPARATOR);
define("CACHE_PATH", MAIN_PATH . DIRECTORY_SEPARATOR . "Cache" . DIRECTORY_SEPARATOR);
define("XML_PATH", MAIN_PATH . DIRECTORY_SEPARATOR . "Files" . DIRECTORY_SEPARATOR);

//Includes
require_once(LIB_PATH . "Cache.php");
require_once(LIB_PATH . "XmlGoogleMap.php");

echo "<pre>";

\Lib\XML\XmlGoogleMap::setXmlFile("records");

