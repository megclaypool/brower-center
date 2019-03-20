<?php
require_once 'plog.php';
require_once 'util.php';

function brower_views_handle_field_node_title($fields, $field, $data)
{
  return "<a href=\"?q=node/" . $data->nid . "\">" . $data->node_title . "</a>";
}

function _phptemplate_variables($hook, $vars = array()) 
{
  if ($hook != 'page') 
  {
    return $vars;
  }

  if ($vars['is_front']) 
  {
    $vars['template_file'] = 'page-front';
  } 

  return $vars;
};
?>
