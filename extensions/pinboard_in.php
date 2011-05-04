<?php
$app = "pinboard_in";

/* Define your variables */
$remote_apps[$app] = array(
  'tag' => 'gsd', 
  'base' => "https://{$remote_creds[$app]['username']}:{$remote_creds[$app]['password']}@api.pinboard.in/v1",
  'path' => '/posts/all',
  'node' => 'post', # XML or JSON node to iterate over for URL nodes
  'sub_node' => '', # the XML or JSON nodes to use scan for URLs
  'property' => 'href', # the XML or JSON attribute to scan for a domain
  'params' => '',
  'xml_mode' => XML_MODE_ATTRIBUTE
);
$remote_apps[$app]["params"] = "tag={$remote_apps[$app]['tag']}";
$remote_apps[$app]["sync_url"] = sync_url($remote_apps[$app]);