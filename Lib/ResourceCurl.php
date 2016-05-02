<?php

namespace Lib;

/**
 * Trabalha com resource CURL
 * A extensão curl deve está instalada e habilitada no PHP
 */
class ResourceCurl {
    
    /** Url para conexão */
    private static $url;
    
    /**
     * Atribui a URL
     * @param String $url
     */
    public static function setUrl($url)
    {
	self::$url = $url;
    }
    
    /**
     * Retorna a URL atribuida
     * @return String
     */
    public static function getUrl()
    {
	return self::$url;
    }
    
    /**
     * Retorna uma conexão com a URL informada
     * @param String $url
     * @return Boolean false ou resource
     */
    public static function getResource($url = "")
    {
	if ($url != "") {
	    self::$url = $url;
	}
	
	$rsc = \curl_init(self::$url);
	
	\curl_setopt($rsc, CURLOPT_RETURNTRANSFER, true);
	\curl_setopt($rsc, CURLOPT_FOLLOWLOCATION, true);
	
	$result = \curl_exec($rsc);
	
	$http_code = \curl_getinfo($rsc, CURLINFO_HTTP_CODE);
	
	\curl_close($rsc);
	
	if ($http_code !== 200) {
	    return false;
	}
	
	return \mb_convert_encoding($result, 'UTF-8');
    }
    
}
