<?
function loadXML($url) {
    if (ini_get('allow_url_fopen') == true) {
      return load_xml_fopen($url);
    } else if (function_exists('curl_init')) {
      return load_xml_curl($url);
    } else {
      // Enable 'allow_url_fopen' or install cURL.
      throw new Exception("Can't load data.");
    }
  }
 
function load_xml_fopen($url) {
    return simplexml_load_file($url);
  }
 
function load_xml_curl($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return simplexml_load_string($result);
  }
function loadContents($url) {
    if (ini_get('allow_url_fopen') == true) {
      return load_contents_get($url);
    } else if (function_exists('curl_init')) {
      return load_contents_curl($url);
    } else {
      // Enable 'allow_url_fopen' or install cURL.
      throw new Exception("Can't load data.");
    }
  }
 
function load_contents_get($url) {
    return file_get_contents($url);
  }
 
function load_contents_curl($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
  }
?>
