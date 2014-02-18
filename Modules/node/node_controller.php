<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function node_controller()
{
  global $mysqli, $redis, $session, $route, $timestore_adminkey;
  $result = false;

  include "Modules/feed/feed_model.php";
  $feed = new Feed($mysqli,$redis, $timestore_adminkey);
  
  include "Modules/node/node_model.php";
  $node = new Node($mysqli,$redis,$feed);
  
  if ($route->format == 'html') {
    if ($route->action == "list" && $session['write']) $result = view("Modules/node/node_view.php",array());
  }
  
  if ($route->format == 'json') {
    if ($route->action == 'set' && $session['write']) $result = $node->set($session['userid'],get('nodeid'),get('data'));
    if ($route->action == 'setdecoder' && $session['write']) $result = $node->set_decoder($session['userid'],get('nodeid'),get('decoder'));
    if ($route->action == 'getall' && $session['write']) $result = $node->get_all($session['userid']);
  }

  return array('content'=>$result);
}
