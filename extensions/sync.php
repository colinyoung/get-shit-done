<?php

// Generalized function to cURL a URL
/* Returns decoded array of sites to block */
function sync($app, $config, $method='GET') {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $config['sync_url']);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  
  print "\n==> Syncing $app...\n";  
  
  # Response
  ob_start();
  curl_exec($ch);
  $body = ob_get_clean();
  $response = NULL;

  # Content type
  $info = curl_getinfo($ch);
  if (strpos($info['content_type'], "/xml") > 0) {
    $xml = unserialize_xml($body);
    $response = array();
    foreach ($xml as $node => $value) {
      if ($node == $config['node']) {
        
        if (is_array($value)) {
          foreach ($value as $key => $sub_value)
            parse_xml_node(&$response, $sub_value, $config);
        } else {
          parse_xml_node(&$response, $value, $config);
        }
      }
    }
  }
  
  # TODO JSON
  
  curl_close($ch);
  return domains_only($response);
}

/* Compiles a URL from its component parts, just pass it a config array */
function sync_url($a) { return "{$a['base']}{$a['path']}?{$a['params']}"; }

/* Strips paths from domains for usage in $siteList in get-shit-done */
function domains_only($arr) {
  $r = array();
  foreach ($arr as $url) {
    $parsed_url = parse_url($url);
    $r[] = array_key_exists('host', $parsed_url) ? $parsed_url['host'] : $parsed_url['path'];
  }
  return $r;
}

function parse_xml_node($response, $value, $config) {
  if ($xml_mode == $config['xml_mode']) {
    # Attributes
    $response[] = $value['@attributes'][$config['property']];
  } else {
    # Nodes
    $response[] = $value[$config['property']];
  }
}

/* LIB */
function unserialize_xml($input, $callback = null, $recurse = false)
/* Function from http://www.php.net/manual/en/function.simplexml-load-string.php#91564 */
/* bool/array unserialize_xml ( string $input [ , callback $callback ] )
 * Unserializes an XML string, returning a multi-dimensional associative array, optionally runs a callback on all non-array data
 * Returns false on all failure
 * Notes:
    * Root XML tags are stripped
    * Due to its recursive nature, unserialize_xml() will also support SimpleXMLElement objects and arrays as input
    * Uses simplexml_load_string() for XML parsing, see SimpleXML documentation for more info
 */
{
    // Get input, loading an xml string with simplexml if its the top level of recursion
    $data = ((!$recurse) && is_string($input))? simplexml_load_string($input): $input;
    // Convert SimpleXMLElements to array
    if ($data instanceof SimpleXMLElement) $data = (array) $data;
    // Recurse into arrays
    if (is_array($data)) foreach ($data as &$item) $item = unserialize_xml($item, $callback, true);
    // Run callback and return
    return (!is_array($data) && is_callable($callback))? call_user_func($callback, $data): $data;
}