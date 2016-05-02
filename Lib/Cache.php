<?php
/**
 * Biblioteca para Geração e Manipulação de Cache
 *
 * 1 - Informe o nome do arquivo de cache através do método setFile()
 * 2 - Tente obter os dados que estão no arquivo de cache através do método readCache()
 * 3 - Utilize o método writeCache() para gravar um novo ou já existente arquivo de cache
 * 
 *
 * @author Gutemberg
 * @copyright 2015, 5GTI
 * 
 */
class Cache 
{
    /** Diretório de Armazenamento do Cache */
    private $_folderCache = CACHE_PATH;
    /** Nome do arquivo de cache, sem extensão */
    private $_fileCache;
    /** Tempo para expirar, em segundos */
    private $_timeout;
    
    public function __construct()
    {
        //Arquivo expira em 2 minutos 'padrão'
        $this->_timeout = 60 * 2;
    }
    
    /**
    * Nome do arquivo de cache
    * @access public
    * @param String $file nome do arquivo de cache
    * @return Void
    */
    public function setFile($file)
    {
        $this->_fileCache = md5($file) . '.txt';
    }

    /**
    * Obtém uma string formatada com o caminho até o arquivo
    * @access public
    * @return String
    */
    public function getFilePath()
    {
        return sprintf('%s/%s', $this->_folderCache, $this->_fileCache);
    }

    /**
    * Altera o local do arquivo de cache
    * @access public
    * @param String $folder path para o novo local
    * @return Void
    */
    public function alterFolder($folder)
    {
        $this->_folderCache = $folder;
    }

    /**
    * Tempo para expirar 'altera o padrão'
    * @access public
    * @param Integer $minutes minutos
    * @return Void
    */
    public function alterTimeout($minutes)
    {
        $this->_timeout = 60 * (int)$minutes;
    }

    /**
    * Verifica se o arquivo já existe
    * @access public
    * @return Boolean
    */
    public function existsFileCache()
    {
        $filecache = $this->getFilePath();

        if ( !file_exists($filecache) ) { return false; }

        return true;
    }

    /**
    * Verifica se o arquivo expirou
    * @access public
    * @return Boolean true - yes | false - not
    */
    public function isTimeout()
    {
        
        if ($this->existsFileCache() === false) { return false; }
        
        $filetime = filemtime($this->getFilePath());
        
        return (time() > ($filetime + $this->_timeout)) ? true : false;
    }

    /**
    * Atribui os dados ao arquivo de cache, se não existir, cria-o
    * @access public
    * @param Array,String $value dados para cache
    * @return Boolean
    */
    public function writeCache($value)
    {
        $filename = $this->getFilePath();

        $value = serialize($value);
        
        return file_put_contents($filename, $value);
    }

    /**
    * Lê um arquivo de cache
    * @access public
    * @return String or False
    */
    public function readCache()
    {
        $filename = $this->getFilePath();
        //Arquivo não existe
        if (!$this->existsFileCache()) { 
            return false; 
        }
        //Arquivo expirado
        if ($this->isTimeout() === true) { 
            return false; 
        }
        
        $data = file_get_contents($filename);
        
        return unserialize($data);
    }
    
}
