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
        if (!empty($query)) {
            $q = is_array($query) ? '?' . http_build_query($query) : $query;
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
        if ($this->_ci == null) {
            $this->_ci = curl_init($this->_url);
            foreach ($this->_options as $code => $value) {
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
        if ($this->_ci != null)
            curl_setopt($this->_ci, $code, $value);
    }

    /**
     * Creates CurlHelper instance chain method
     * @param string $url url string starting with 'http://'
     * @param mixed $query GET query could be assoc array or a string starting with '?' sign
     * @param array $options array of CURLOPT's
     * @return CurlHelper Object instance
     */
    public static function create($url, $query = array(), $options = array()) {
        $inst = new self($url, $query, $options);
        return $inst;
    }

    /**
     * Executes curl
     * @return mixed curl_exec results
     */
    public function exec() {
        return curl_exec($this->_ci);
    }

    /**
     * Closes curl instance
     */
    public function close() {
        if ($this->_ci != null)
            curl_close($this->_ci);
        $this->_ci = null;
    }

    public static function getWebPage($url) {
        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => "", // handle all encodings
            CURLOPT_USERAGENT => "Vsevmeste.kz", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return $header;
    }

}

?>
