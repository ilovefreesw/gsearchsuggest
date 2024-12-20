<?php
class Suggest {
    
    var $query;
    var $locale;
    var $data = array();
    var $cache_path; 
    var $cache_file;

    function __construct($query, $locale) {
        $this->query = urlencode($query);
        $this->locale = $locale;

        $this->cache_path = dirname(__FILE__) . '/Cache/';
        $this->cache_file = $this->locale . "." . preg_replace("/[^a-z0-9.]+/i", "+", $this->query) . '.json';
        
        if (file_exists($this->cache_path.$this->cache_file)) {
            $cache = file_get_contents($this->cache_path.$this->cache_file);
            $this->data = json_decode($cache, true);
        } else {
            $this->Query();
        }
    }

    function Query() {
        $agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36";
        $host = "https://suggestqueries.google.com/complete/search?client=firefox&hl=".$this->locale."&q=".$this->query;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $this->Parse($response);
    }

    function Parse($response) {
        // Decode JSON response
        $suggestions = json_decode($response, true);
        
        if (!empty($suggestions) && isset($suggestions[1])) {
            $this->data = $suggestions[1]; // Extract suggestions array
        }
        
        $this->Cache();
    }

    function Cache() {
        $json = json_encode($this->data);
        
        if (is_writable($this->cache_path)) {
            file_put_contents($this->cache_path.$this->cache_file, $json);
        }
    }
}

// Usage Example
$query = "example search";
$locale = "en";
$suggest = new Suggest($query, $locale);
?>
