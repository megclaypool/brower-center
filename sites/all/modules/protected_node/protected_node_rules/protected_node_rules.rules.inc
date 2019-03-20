<?php
/**
 * @file
 * Rules integration for the protected_node module.
 */

/**
 * Implements of hook_rules_event_info().
 */
/*
function protected_node_rules_event_info() {
 return array(
   'protected_node_password_set' => array(
     'label' => t('The node is now protected'),
   ),
   'protected_node_password_unset' => array(
     'label' => t('The node is now unprotected'),
   ),
 );
}
*/

/**
 * Implements hook_rules_condition_info().
 */
function protected_node_rules_rules_condition_info() {
  $items = array();
  $defaults = array(
    'parameter' => array(
      'node' => array(
        'type' => 'node',
        'label' => t('Content'),
      ),
    ),
    'group' => t('Protected Node'),
  );
  $items['protected_node_rules_condition_content_is_protected'] = array(
    'label' => t('Content is protected'),
    'help' => t('Evaluates to TRUE when the node is protected by a password.'),
  ) + $defaults;
  $items['protected_node_rules_condition_content_is_locked'] = array(
    'label' => t('Content is locked from current user'),
    'help' => t('Evaluates to TRUE when the node is locked by a password from the current user.'),
  ) + $defaults;
  return $items;
}

/**
 * Condition: check whether the node is protected by password.
 */
function protected_node_rules_condition_content_is_protected(&$node, $settings) {
  return $node->protected_node_is_protected == 1;
}

/**
 * Condition: check whether the current user has access to the node.
 */
function protected_node_rules_condition_content_is_locked($node) {
  return protected_node_is_locked($node->nid) !== FALSE;
}

/**
 * Implements hook_rules_action_info().
 */
function protected_node_rules_rules_action_info() {
  $defaults = array(
    'parameter' => array(
      'node' => array(
        'type' => 'node',
        'label' => t('Content'),
      ),
    ),
    'group' => t('Protected node'),
  );
  $items['protected_node_rules_action_protect'] = array(
    'label' => t('Protect node'),
  ) + $defaults;
  $items['protected_node_rules_action_password_protect'] = array(
    'parameter' => array(
      'node' => array(
        'type' => 'node',
        'label' => t('Content'),
      ),
      'passwd' => array(
        'type' => 'string',
        'label' => t('Password'),
      ),
    ),
    'label' => t('Password protect node'),
  ) + $defaults;
  $items['protected_node_rules_action_unprotect'] = array(
    'label' => t('Unprotect the node'),
  ) + $defaults;
  $items['protected_node_rules_action_lock'] = array(
    'label' => t('Lock the node away from current user'),
  ) + $defaults;
  $items['protected_node_rules_action_unlock'] = array(
    'label' => t('Unlock the node for current user'),
  ) + $defaults;
  return $items;
}

/**
 * Activate the protection on the specified node.
 */
function protected_node_rules_action_protect($node) {
  $node->protected_node_is_protected = 1;
  return array('node' => $node);
}

/**
 * Activate the password protection on the specified node.
 */
function protected_node_rules_action_password_protect($node, $passwd) {
  $node->protected_node_is_protected = 1;
  $node->protected_node_passwd = $passwd;
  return array('node' => $node);
}

/**
 * Deactivate the protection on the specified node.
 */
function protected_node_rules_action_unprotect($node) {
  $node->protected_node_is_protected = 0;
  return array('node' => $node);
}

/**
 * Revoke any access right to the node from the current user.
 */
function protected_node_rules_action_lock($node) {
  protected_node_lock($node->nid);
  return array();
}

/**
 * Grant access rights to the node to the current user.
 */
function protected_node_rules_action_unlock($node) {
  protected_node_unlock($node->nid);
  return array();
}
