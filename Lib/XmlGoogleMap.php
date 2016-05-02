<?php

namespace Lib\XML;

/**
 * Realiza a interação com dados de um arquivo XML e Consulta a API do Google Maps para obter
 * a longitude e latitude a partir de um endereço
 *
 * @author Gutemberg
 */
class XmlGoogleMap {

    /** Key de acesso ao Google Maps API */
    const GOOGLE_KEY = "";
    /** Url do Google Maps API para obtenção de dados via JSON */
    const GOOGLE_URL_JSON = "https://maps.googleapis.com/maps/api/geocode/json?address=";
    /** Url do Google Maps API para obtenção de dados via XML */
    const GOOGLE_URL_XML = "https://maps.googleapis.com/maps/api/geocode/xml?address=";
    
    /** Nome do arquivo xml a ser carregado */
    private static $xmlFile = null;
    /** Arquivo XML carregado como objeto */
    private static $objXml = null;
    
    /**
     * Atruibui o nome do arquivo XML a ser carregado pelo sistema
     * @access public
     * @param String $xml
     */
    public static function setXmlFile($xml)
    {
	self::$xmlFile = $xml;
    }
    
    /**
     * Retorna o nome do arquivo XML atribuido
     * @access public
     * @return String ou null caso não exista arquivo
     */
    public static function getXmlFile()
    {
	return self::$xmlFile;
    }
    
    /**
     * Carrega um arquivo XML
     * @access public
     * @param String $file nome do arquivo xml a ser carregado (Sem a extensão)
     * @return Boolean false se não for carregado ou Objeto XML
     */
    public static function loadXml($file = "")
    {
	if ($file != "") {
	    self::$xmlFile = $file;
	}
	
	$dot = strpos(self::$xmlFile, ".");
	if ($dot > 1) {
	    self::$xmlFile = substr(self::$xmlFile, 0, strlen(self::$xmlFile) - 4);
	}
	
	$dir = XML_PATH . self::$xmlFile . ".xml";
	
	if (!file_exists($dir)) {
	    return false;
	}
	
	self::$objXml = simplexml_load_file($dir);
	
	return self::$objXml;
    }
    
    /**
     * Consulta a Latitude e Longitude a partir de um endereço informado no arquivo xml
     * @return Boolean false se não existir objeto xml carregado ou  Array com o resultado
     */
    public static function loadLatLongByAddress()
    {
	if (self::$objXml === null) {
	    return false;
	}
	
	$result = array();
	
	foreach (self::$objXml->children() as $row) {
	    
	    $code = null;
	    $addr = array();
	    
	    foreach ($row->register->column as $column) {
		
		switch ((string) $column["label"]) {
		    case "code":
			$code = (string)$column;
		    break;
		    case "adress":
			$addr["addr"] = (string) $column;
		    break;
		    case "city":
			$addr["city"] = (string) $column;
		    break;
		}
		
	    }
	    
	    $complete_addr = $addr["addr"] . "+" . $addr["city"];
	    
	    $address = self::formatAddress($complete_addr);
	    
	    $result = self::getResource($address);
	    
	    $result[] = self::formatResult((array)$result);
	    
	    self::cacheResult($result);
	}
	
	var_dump($result);
	
	die;
    }
    
    /**
     * Formata o endereço de acordo com a API do Google Maps
     * @param String $address
     * @return String
     */
    private static function formatAddress($address)
    {
	$addr_formated = "";
	
	if (empty($address) || $address == "") {
	    return $addr_formated;
	}
	
	$addr_formated = preg_replace("/[^a-zA-Z0-9]/", " ", $address);
	
	$addr_formated = str_replace(" ", "+", $addr_formated);
	
	$pos = true;
	while($pos) {
	    $pos = strpos($addr_formated, "++");
	    
	    if ($pos == 0 || $pos == false) {
		break;
	    }
	    
	    $addr_formated = substr_replace($addr_formated, "", $pos, 1);
	}
	
	return $addr_formated;
    }
    
    /**
     * Formata o resultado obtido
     * @param Array $result
     * @return Array
     */
    private static function formatResult($result)
    {
	
	
	array(
	    "code" => $code,
	    "long" => "15690529530",
	    "lat" => "9834982340"
	);
    }
    
    /**
     * Trabalha com o cache dos resultados
     * @param Array $result
     */
    private static function cacheResult($result)
    {
	
    }
    
    /**
     * Busca na biblioteca a classe que trabalha com Resources para realizar a consulta na API do Google
     */
    private static function getResource($addr)
    {
	require_once(LIB_PATH . "ResourceCurl.php");
	
	$url = self::GOOGLE_URL_JSON . $addr . "&key=" . self::GOOGLE_KEY;
	
	return \Lib\ResourceCurl::getResource($addr);
    }
    
}
