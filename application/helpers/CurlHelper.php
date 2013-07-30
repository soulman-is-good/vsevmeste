<?php
/**
 * CurlHelper class
 * Helps make curl commands
 */
class CurlHelper {
    
    private $_options = array();
    private $_ci = null;
    private $_url = null;
    
    /**
     * CurlHelper constructor
     * @param string $url url string starting with 'http://'
     * @param mixed $query GET query could be assoc array or a string starting with '?' sign
     * @param array $options array of CURLOPT's
     * @return CurlHelper Object instance
     */
    public function __construct($url, $query = array(), $options = array()) {
        $q = "";
        if(!empty($query)){
            $q = is_array($query)?'?' . http_build_query($query) : $query;
        }
        $this->_url = $url . $q;
        $this->_options = $options;
        $this->init();
    }
    
    public function __destruct() {
        $this->close();
    }
    
    /**
     * Initializes curl
     */
    public function init() {
        if($this->_ci == null) {
            $this->_ci = curl_init($this->_url);
            foreach($this->_options as $code=>$value){
                curl_setopt($this->_ci, $code, $value);
            }
        }
        return $this;
    }
    
    /**
     * Sets curl options
     * @param int $code CURLOPT code
     * @param mixed $value value
     */
    public function setOption($code, $value) {
        $this->_options[$code] = $value;
        if($this->_ci != null)
            curl_setopt($this->_ci, $code, $value);
    }
    
    /**
     * Creates CurlHelper instance chain method
     * @param string $url url string starting with 'http://'
     * @param mixed $query GET query could be assoc array or a string starting with '?' sign
     * @param array $options array of CURLOPT's
     * @return CurlHelper Object instance
     */
    public static function create($url,$query = array(),$options = array()){
        $inst = new self($url,$query,$options);
        return $inst;
    }
    
    /**
     * Executes curl
     * @return mixed curl_exec results
     */
    public function exec(){
        return curl_exec($this->_ci);
    }
    
    /**
     * Closes curl instance
     */
    public function close(){
        if($this->_ci != null)
            curl_close($this->_ci);
        $this->_ci = null;
    }
    
}


?>
