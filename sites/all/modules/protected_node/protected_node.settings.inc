<?php
/**
 * @file
 * Configuration file for the protected_node module.
 */

/**
 * When editing a node that can be protected and the protected fieldset is
 * added, open it if the node is already protected. Leave closed otherwise.
 */
define('PROTECTED_NODE_FIELDSET_SMART', 0);

/**
 * When editing a node that can be protected, always show the fieldset as
 * opened.
 */
define('PROTECTED_NODE_FIELDSET_OPEN', 1);

/**
 * When editing a node that can be protected, always show the fieldset as
 * closed.
 */
define('PROTECTED_NODE_FIELDSET_CLOSE', 2);


/**
 * The default email subject sent to users to whom you give access to
 * a protected node.
 */
function protected_node_email_subject() {
  return t("Please visit my website...");
}

/**
 * The default email body sent to users to whom you give access to
 * a protected node.
 */
function protected_node_email_body() {
  // @todo This should go in a variable.
  return t("Dear friend,

I just created a new page on my website and wanted to invite you to
visit. The page is protected by the following password:

[node-password]

Thank you.
[site-name]"
  );
}

/**
 * Actual implementation of the protected_node_menu() function.
 *
 * Moved here to optimize the module (menu are cached and the definitions
 * are only rarely necessary.)
 */
function protected_node_menu_array() {
  $items = array();

  $items['admin/config/content/protected_node'] = array(
    'title' => 'Protected node',
    'description' => 'Edit the global Protected node settings',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('protected_node_admin_settings'),
    'access arguments' => array('administer site configuration'),
    'file' => 'protected_node.settings.inc',
  );
  $items['protected-node'] = array(
    'title' => 'Protected page - Enter Password',
    'description' => 'Here you can enter the password for protected pages',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('protected_node_enterpassword'),
    'access callback' => 'protected_node_access_callback',
    'type' => MENU_CALLBACK,
    'file' => 'protected_node.redirect.inc',
  );
  $items['protected-nodes'] = array(
    'title' => 'Protected page - Enter Password',
    'description' => 'Here you can enter the password for protected pages',
    'page callback' => 'drupal_get_form',
    'page arguments' => array('protected_node_enter_any_password'),
    'access arguments' => array('access protected content'),
    'type' => MENU_CALLBACK,
    'file' => 'protected_node.fork.inc',
  );

  return $items;
}

/**
 * Actual implementation of the protected_node_form_alter() function.
 *
 * It was moved here to optimize the module (so the .module is smaller)
 * This is really only necessary when some edits a node.
 *
 * @param[in,out] $form The form to be altered with the protected node field
 *   set.
 */
function protected_node_node_form_alter(&$form) {
  $protection = variable_get('protected_node_protection_' . $form['type']['#value'], PROTECTED_NODE_PROTECTION_PROTECTABLE);
  if ($protection == PROTECTED_NODE_PROTECTION_NEVER) {
    // Never protecting, do nothing.
    return;
  }

  // Try to avoid pre-filled password forms.
  $form['#attributes']['autocomplete'] = 'off';

  $node_type = node_type_get_type($form['type']['#value']);
  $fieldset_mode = variable_get('protected_node_fieldset_' . $node_type->type, PROTECTED_NODE_FIELDSET_SMART);
  switch ($fieldset_mode) {
    case PROTECTED_NODE_FIELDSET_OPEN:
      $collapsed = FALSE;
      break;

    case PROTECTED_NODE_FIELDSET_CLOSE:
      $collapsed = TRUE;
      break;

    /* case PROTECTED_NODE_FIELDSET_SMART: */
    default:
      if ($protection == PROTECTED_NODE_PROTECTION_ALWAYS
      || ($protection == PROTECTED_NODE_PROTECTION_PROTECTED && empty($form['nid']['#value']))) {
        $collapsed = FALSE;
      }
      else {
        $collapsed = empty($form['#node']->protected_node_is_protected);
      }
      break;
  }
  $form['protected_node'] = array(
    '#type' => 'fieldset',
    '#description' => t('By selecting the checkbox below you password protect this page. Unless you are using the global password feature, you want to enter a password in this form.'),
    '#title' => t('Password protect this @node', array('@node' => $node_type->name)),
    '#collapsible' => TRUE,
    '#collapsed' => $collapsed,
  );
  if ($protection == PROTECTED_NODE_PROTECTION_ALWAYS) {
    // Always protected, don't show anything to the user.
    $form['protected_node']['protected_node_is_protected'] = array(
      '#type' => 'value',
      '#value' => TRUE,
    );
  }
  else {
    // Define the current default value.
    if (empty($form['nid']['#value'])) {
      // A new node is being created, use the protection mode to determine the
      // default.
      // No need to test for NEVER, we're already out of this function in that
      // case.
      // No need to test for ALWAYS, we went through the previous level if() in
      // that case.
      $protected = $protection == PROTECTED_NODE_PROTECTION_PROTECTED;
    }
    else {
      $protected = !empty($form['#node']->protected_node_is_protected);
    }
    $form['protected_node']['protected_node_is_protected'] = array(
      '#type' => 'checkbox',
      '#title' => t('This @node is protected', array('@node' => $node_type->name)),
      '#description' => t('Check here if this content should be protected by a password.'),
      '#default_value' => $protected,
    );
  }

  if (isset($form['#node']->protected_node_show_title)) {
    $show_title = $form['#node']->protected_node_show_title;
  }
  else {
    $show_title = variable_get('protected_node_show_node_titles', FALSE);
  }

  $form['protected_node']['protected_node_show_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Title'),
    '#description' => t('Show the title to users when requesting the password.'),
    '#default_value' => $show_title,
  );

  // Define a password field unless only the global password should be used.
  switch (variable_get('protected_node_use_global_password', PROTECTED_NODE_PER_NODE_PASSWORD)) {
    case PROTECTED_NODE_PER_NODE_PASSWORD:
    case PROTECTED_NODE_PER_NODE_AND_GLOBAL_PASSWORD:
      $form['protected_node']['protected_node_passwd'] = array(
        '#type' => 'password_confirm',
        '#description' => t('Enter the @node password here. Remember that changing the password prevents all the users who knew the old password to continue to access the page.', array('@node' => $node_type->name)),
        '#size' => 20,
        '#after_build' => array('protected_node_autocomplete_off'),
      );

      break;

  }

  if (variable_get('protected_node_allow_hint', FALSE)) {
    if (isset($form['#node']->protected_node_hint)) {
      $hint = $form['#node']->protected_node_hint;
    }
    else {
      $hint = '';
    }
    $form['protected_node']['protected_node_hint'] = array(
      '#type' => 'textarea',
      '#title' => t('Password hint'),
      '#description' => t('Enter a password hint. This information is displayed in the password form using the [node-password-hint] token.'),
      '#default_value' => $hint,
      '#cols' => 45,
      '#rows' => 5,
    );
  }

  // Include the email box?
  $width_height = variable_get('protected_node_email_box', '');
  if ($width_height) {
    list($width, $height) = explode('x', $width_height);
    $form['protected_node']['protected_node_emails'] = array(
      '#type' => 'textarea',
      '#description' => t('Enter a list of email addresses separated by newlines and/or commas. At the time you save the page, each one of those users will be sent an email with a link back to this page and optionally the password.')
        . '<br />'
        . t('<strong>WARNING</strong>: for an email to be sent you MUST (1) protect the node; (2) re-enter the password; (3) publish the page.')
        . '<br />'
        . t('The password is necessary only if you used [node-password] in the email message. It is necessary to re-enter it because it is otherwise encrypted in the database.'),
      '#cols' => $width,
      '#rows' => $height,
    );
  }
}

/**
 * Define the settings form
 *
 * @return array
 *   $form The settings form
 */
function protected_node_admin_settings() {
  // Statistics.
  $form['protected_node_stats'] = array(
    '#type' => 'fieldset',
    '#title' => t('Protected node statistics'),
    '#collapsible' => TRUE,
  );

  $unprotected_count = db_query("SELECT COUNT(n.nid) FROM {node} n LEFT JOIN {protected_nodes} pn ON pn.nid = n.nid WHERE pn.protected_node_is_protected = :protected_node_is_protected OR pn.protected_node_is_protected IS NULL", array(':protected_node_is_protected' => 0))->fetchField();
  $protected_count = db_query("SELECT COUNT(nid) FROM {protected_nodes} WHERE protected_node_is_protected = :protected_node_is_protected", array(':protected_node_is_protected' => 1))->fetchField();
  $title_count = db_query("SELECT COUNT(nid) FROM {protected_nodes} WHERE protected_node_is_protected = :protected_node_is_protected AND protected_node_show_title = :protected_node_show_title", array(':protected_node_is_protected' => 1, ':protected_node_show_title' => 1))->fetchField();
  $global_count = db_query("SELECT COUNT(nid) FROM {protected_nodes} WHERE protected_node_is_protected = :protected_node_is_protected AND protected_node_passwd = :protected_node_passwd", array(':protected_node_is_protected' => 1, ':protected_node_passwd' => ''))->fetchField();

  // Any nodes?
  if ($protected_count + $unprotected_count > 0) {
    $stats = '<table><tr><th style="text-align: center;">Label</th><th style="text-align: center;">Count</th><th style="text-align: center;">Percent</th></tr>';
    $stats .= '<tr><td>Total nodes</td><td align="right">' . ($protected_count + $unprotected_count) . '</td><td align="right">100%</td></tr>';
    $stats .= '<tr><td>Unprotected nodes</td><td align="right">' . $unprotected_count . '</td><td align="right">' . round($unprotected_count * 100 / ($unprotected_count + $protected_count), 2) . '%</td></tr>';
    $stats .= '<tr><td>Protected nodes</td><td align="right">' . $protected_count . '</td><td align="right">' . round($protected_count * 100 / ($unprotected_count + $protected_count), 2) . '%</td></tr>';
    // Any protected nodes?
    if ($protected_count > 0) {
      $stats .= '<tr><td>&raquo; Showing title</td><td align="right">' . $title_count . '</td><td align="right">' . round($title_count * 100 / $protected_count, 2) . '%</td></tr>';
      $stats .= '<tr><td>&raquo; Hiding title</td><td align="right">' . ($protected_count - $title_count) . '</td><td align="right">' . round(($protected_count - $title_count) * 100 / $protected_count, 2) . '%</td></tr>';
      $stats .= '<tr><td>&raquo; Global passwords</td><td align="right">' . $global_count . '</td><td align="right">' . round($global_count * 100 / $protected_count, 2) . '%</td></tr>';
      $stats .= '<tr><td>&raquo; Node passwords</td><td align="right">' . ($protected_count - $global_count) . '</td><td align="right">' . round(($protected_count - $global_count) * 100 / $protected_count, 2) . '%</td></tr>';
    }
    $stats .= '</table>';

    $form['protected_node_stats']['protected_node_statistics'] = array(
      '#value' => $stats,
    );
  }

  // Security related options.
  $form['protected_node_security'] = array(
    '#type' => 'fieldset',
    '#title' => t('Protected node security'),
    '#collapsible' => TRUE,
  );

  $form['protected_node_security']['protected_node_use_global_password'] = array(
    '#type' => 'select',
    '#title' => t('Global password handling'),
    '#default_value' => variable_get('protected_node_use_global_password', PROTECTED_NODE_PER_NODE_PASSWORD),
    '#options' => array(
      PROTECTED_NODE_PER_NODE_PASSWORD => 'Per node password',
      PROTECTED_NODE_PER_NODE_AND_GLOBAL_PASSWORD => 'Per node password or Global password',
      PROTECTED_NODE_GLOBAL_PASSWORD => 'Global password only',
    ),
    '#description' => t("When checked, the global password is used if the user doesn't enter a password when creating/editing nodes.")
                    . t("<strong>WARNING:</strong> removing the global password when many pages were not otherwise assigned a password will make those nodes unaccessible except for UID=1 and the author of the node."),
  );

  $form['protected_node_security']['protected_node_global_password_field'] = array(
    '#type' => 'password_confirm',
    '#title' => t('Global password'),
    /* '#default_value' => variable_get('protected_node_global_password', ''), */
    '#description' => t('The default password for all nodes. This password is necessary if you select the previous checkbox.'),
  );
  // Yeah... we're calling a Core "private" function...
  _user_password_dynamic_validation();

  $form['protected_node_security']['protected_node_show_password_strength'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show password strength in nodes'),
    '#default_value' => variable_get('protected_node_show_password_strength', TRUE),
    '#description' => t('When checked, show the password strength on nodes being edited. Since some people will on purpose want to use this feature with very weak password, this may not always be welcome.'),
  );

  $form['protected_node_security']['protected_node_show_node_titles'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show node titles by default'),
    '#default_value' => variable_get('protected_node_show_node_titles', FALSE),
    '#description' => t('Whether a the node title should be shown (selected) or hidden (unchecked) by default. This flag is used as the default of the Show title checkbox on a create node.'),
  );

  $form['protected_node_security']['protected_node_allow_hint'] = array(
    '#type' => 'checkbox',
    '#title' => t('Allow author to enter a password hint'),
    '#default_value' => variable_get('protected_node_allow_hint', FALSE),
    '#description' => t('By default, the password is kept as secret as possible. This option allows the author to enter a hint about the password of a node. The hint is later shown using the [node-password-hint] token.'),
  );

  $form['protected_node_auto_email'] = array(
    '#type' => 'fieldset',
    '#title' => t('Protected node email support'),
    '#description' => t('The following allows the users who can protect a node to send an email about the protected node to their friends.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['protected_node_auto_email']['protected_node_email_box'] = array(
    '#type' => 'textfield',
    '#title' => t('Email node information'),
    '#size' => 12,
    '#default_value' => variable_get('protected_node_email_box', ''),
    '#description' => t('Enter the width x height of the email box text area (i.e. 60x5). Leave empty to disable this feature.'),
  );

  $form['protected_node_auto_email']['protected_node_email_from'] = array(
    '#type' => 'textfield',
    '#title' => t('From email address'),
    '#default_value' => variable_get('protected_node_email_from', ''),
    '#description' => t('Enter the email address used in the From: header. By default, [site-mail] is used (@mail)', array('@mail' => variable_get('site_mail', '<undefined>'))),
  );

  $form['protected_node_auto_email']['protected_node_email_subject'] = array(
    '#type' => 'textfield',
    '#title' => t('Email subject'),
    '#default_value' => variable_get('protected_node_email_subject', protected_node_email_subject()),
    '#description' => t('Enter the subject of the email. You may enter tokens in this field. Remember that [user-name] will be the author name.'),
  );

  $form['protected_node_auto_email']['protected_node_email_body'] = array(
    '#type' => 'textarea',
    '#title' => t('Email content'),
    '#rows' => 15,
    '#default_value' => variable_get('protected_node_email_body', protected_node_email_body()),
    '#description' => t('Enter the body of the email. You may enter tokens in this field. Remember that [user-name] will be the author name.'),
  );

  $form['protected_node_auto_email']['protected_node_random_password'] = array(
    '#type' => 'checkbox',
    '#title' => t('Generate a random password if necessary'),
    '#default_value' => variable_get('protected_node_random_password', FALSE),
    '#description' => t('When this flag is set, saving a protected node without re-entering the password will randomize a password and send it to your users. You may enter your email address to know about the password yourself. (It otherwise gets encrypted in the database.)'),
  );

  $form['protected_node_form'] = array(
    '#type' => 'fieldset',
    '#title' => t('Protected node form'),
    '#collapsible' => TRUE,
  );

  $form['protected_node_form']['protected_node_cancel'] = array(
    '#type' => 'checkbox',
    '#title' => t('Always add a cancel link'),
    '#default_value' => variable_get('protected_node_cancel', 0),
    '#description' => t('Whether a cancel link should be added to the password form. If checked and we do not have a back link, then the cancel is set to &lt;front&gt; instead.'),
  );

  $form['protected_node_form']['protected_node_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Password page title'),
    '#default_value' => variable_get('protected_node_title', 'Protected page -- Enter password'),
    '#description' => t('Enter the title of the protected node page. No HTML allowed. You can use node type tokens from the token module when installed.'),
  );

  $form['protected_node_form']['protected_node_info'] = array(
    '#type' => 'textarea',
    '#title' => t('Password page general information'),
    '#default_value' => variable_get('protected_node_info', ''),
    '#description' => t('Enter general information for the protected node page. HTML is accepted. You can use node type tokens from the token module when installed.'),
  );

  $form['protected_node_form']['protected_node_description'] = array(
    '#type' => 'textarea',
    '#title' => t('Password page description (inside the field set)'),
    '#default_value' => variable_get('protected_node_description', ''),
    '#description' => t('Enter specific description for the protected node page. This description is displayed inside the fieldset. HTML is accepted. You can use node type tokens from the token module when installed.'),
  );

  $form['protected_node_form']['protected_node_password_label'] = array(
    '#type' => 'textfield',
    '#title' => t('Password field label on password page'),
    '#default_value' => variable_get('protected_node_password_label', ''),
    '#description' => t('Enter the text for the password label appearing on the password page. The default (when empty) is the node type name followed by the word "password". You can use tokens from the token module when installed.'),
  );

  if (module_exists('token')) {
    $vars = array(
      'token_types' => array('global', 'node', 'user'),
    );
    $form['protected_node_form']['protected_node_tokens'] = array(
      '#value' => theme('token_tree', $vars),
      '#description' => t("WARNING: the user tokens should only be used if anonymous users cannot access protected nodes; otherwise the result may not be what you'd expect."),
    );
  }

  // A few actions to do some "mass work".
  $form['protected_node_actions'] = array(
    '#type' => 'fieldset',
    '#title' => t('Protected node actions'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  // Clear Sessions button.
  $form['protected_node_actions']['protected_node_clear_sessions'] = array(
    '#type' => 'fieldset',
    '#title' => t('Clear sessions'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description' => t('Click on this button to reset all the Protected Node password sessions of your website. This means all the users who entered a password before then will now have to log back in to see such and such node.'),
  );
  $form['protected_node_actions']['protected_node_clear_sessions']['protected_node_clear_sessions_button'] = array(
    '#type' => 'submit',
    '#value' => t('Clear sessions'),
    '#submit' => array('protected_node_action_clear_sessions'),
  );

  // Reset passwords button.
  $form['protected_node_actions']['protected_node_reset_passwords'] = array(
    '#type' => 'fieldset',
    '#title' => t('Reset passwords'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description' => t('WARNING: this feature sets a password on nodes that were otherwise expected to use the global password.'),
  );
  $form['protected_node_actions']['protected_node_reset_passwords']['protected_node_reset_passwords_password'] = array(
    '#type' => 'password_confirm',
    '#title' => t('New Protected Nodes password'),
    '#description' => t('Enter the new password that all existing protected nodes will be assigned.'),
  );
  $form['protected_node_actions']['protected_node_reset_passwords']['protected_node_reset_passwords_button'] = array(
    '#type' => 'submit',
    '#value' => t('Reset all existing passwords'),
    '#submit' => array('protected_node_action_reset_passwords'),
  );

  // Remove passwords button.
  $form['protected_node_actions']['protected_node_remove_passwords'] = array(
    '#type' => 'fieldset',
    '#title' => t('Use global password'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description' => '<span style="color: red;">' . t('WARNING: this function resets all your nodes password and make sure the global password is now required for all of them.') . '</span>',
  );
  $form['protected_node_actions']['protected_node_remove_passwords']['protected_node_remove_passwords_button'] = array(
    '#type' => 'submit',
    '#value' => t('Use global password on ALL nodes'),
    '#submit' => array('protected_node_action_remove_passwords'),
  );

  // Remove protection button.
  $form['protected_node_actions']['protected_node_remove_protection'] = array(
    '#type' => 'fieldset',
    '#title' => t('Remove protection'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description' => '<span style="color: red;">' . t('WARNING: this function unprotects all your currently protected nodes!!! The password is not modified.') . '</span>',
  );
  $form['protected_node_actions']['protected_node_remove_protection']['protected_node_remove_protection_button'] = array(
    '#type' => 'submit',
    '#value' => t('Remove password protection from all nodes'),
    '#submit' => array('protected_node_action_unprotected_all'),
  );

  // Protect ALL nodes buttons.
  $form['protected_node_actions']['protected_node_protect_nodes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Protect nodes'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description' => t('WARNING: this function protects ALL your nodes making them accessible only when the password is known. Nodes that do not yet have a password will make use of the global password. You may use the Reset password feature to change that password afterward.'),
  );
  $form['protected_node_actions']['protected_node_protect_nodes']['protected_node_protect_all_nodes_button'] = array(
    '#type' => 'submit',
    '#value' => t('Protect ALL nodes'),
    '#submit' => array('protected_node_action_protect_all_nodes'),
  );

  // Re-protect nodes buttons.
  $form['protected_node_actions']['protected_node_protect_password'] = array(
    '#type' => 'fieldset',
    '#title' => t('Restore protection'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#description' => t('WARNING: this function re-protects the nodes that had a password and got unprotected.'),
  );
  $form['protected_node_actions']['protected_node_protect_password']['protected_node_protect_nodes_with_password_button'] = array(
    '#type' => 'submit',
    '#value' => t('Protect nodes that already have a password'),
    '#submit' => array('protected_node_action_protect_nodes_with_password'),
  );

  $form['#validate'][] = '_protected_node_admin_settings_validate';
  $form['#submit'][] = '_protected_node_admin_settings_submit';

  return system_settings_form($form);
}

/**
 * Validate the submitted settings form from.
 *
 * This function verifies that a global password is defined if the user
 * defines that the global password should be used by default.
 */
function _protected_node_admin_settings_validate($form, &$form_state) {
  // Make sure that a password is included if a global password is required
  // and none was ever submitted before.
  switch ($form_state['values']['protected_node_use_global_password']) {
    case PROTECTED_NODE_PER_NODE_AND_GLOBAL_PASSWORD:
    case PROTECTED_NODE_GLOBAL_PASSWORD:
      if (!$form_state['values']['protected_node_global_password_field']
       && !variable_get('protected_node_global_password', '')) {
        form_error($form['protected_node_security']['protected_node_global_password_field'],
                   t('A global password is required to make use of the global password functionality.'));
      }
      break;

  }

  // If defined, the email box parameter must be a valid size.
  $email_box = trim($form_state['values']['protected_node_email_box']);
  if ($email_box) {
    if (!preg_match('/[0-9]+x[0-9]+/', $email_box)) {
      form_error($form['protected_node_security']['protected_node_email_box'],
                 t('The email box must be two numbers separated by the letter x in lowercase.'));
    }
    else {
      list($width, $height) = explode('x', $email_box);
      if ($width < 10 || $height < 2) {
        form_error($form['protected_node_security']['protected_node_email_box'],
                   t('The email box must have a width of at least 10 and a height of at least 2.'));
      }
    }
  }

  if ($form_state['values']['protected_node_email_from']) {
    if (!valid_email_address($form_state['values']['protected_node_email_from'])) {
      form_error($form['protected_node_auto_email']['protected_node_email_from'],
                 t('The From email address is not valid.'));
    }
  }
}

/**
 * Submitting the form requires a little bit of work for the password.
 * We need to encrypt it.
 */
function _protected_node_admin_settings_submit($form, &$form_state) {
  $passwd = $form_state['values']['protected_node_global_password_field'];
  if ($passwd) {
    variable_set('protected_node_global_password', sha1($passwd));
    unset($form_state['values']['protected_node_global_password_field']);
    variable_del('protected_node_global_password_field');
  }
  // Never reset the password...
}

/**
 * "Clear" all the sessions.
 *
 * This function sets the session variable date to now. Any session that was
 * created before then is then considered out of date and will be ignored.
 */
function protected_node_action_clear_sessions($form, &$form_state) {
  variable_set('protected_node_session_timelimit', REQUEST_TIME);
  drupal_set_message('All the Protected Node sessions were cleared.');
}

/**
 * "Reset" all the passwords.
 *
 * This function assigns the specified password to all the existing protected
 * nodes.
 */
function protected_node_action_reset_passwords($form, &$form_state) {
  $passwd = $form_state['values']['protected_node_reset_passwords_password'];
  if ($passwd) {
    db_update('protected_nodes')
      ->fields(array(
        'protected_node_passwd' => sha1($passwd),
      ))
      ->execute();
    variable_set('protected_node_session_timelimit', REQUEST_TIME);
    drupal_set_message('All protected nodes are now publicly visible unless protected by another module.');
  }
  else {
    form_error($form['protected_node_actions']['protected_node_reset_passwords']['protected_node_reset_passwords_password'],
               t('Enter a new password to reset all the protected node passwords.'));
  }
}

/**
 * Remove all the passwords.
 *
 * This function sets all the password to the empty string which has the effect
 * of reseting the password to use the global password on all nodes.
 */
function protected_node_action_remove_passwords($form, &$form_state) {
  db_update('protected_nodes')
    ->fields(array(
      'protected_node_passwd' =>  '',
    ))
    ->condition('protected_node_passwd', '', '<>')
    ->execute();
}

/**
 * Remove all the protections.
 *
 * This function sets all the protected_node_is_protected to 0.
 */
function protected_node_action_unprotected_all($form, &$form_state) {
  db_update('protected_nodes')
    ->fields(array(
      'protected_node_is_protected' =>  0,
    ))
    ->condition('protected_node_is_protected', 1)
    ->execute();
}

/**
 * Protect all nodes.
 *
 * This function adds to the protected nodes list all the existing nodes
 * and marks ALL nodes as protected.
 */
function protected_node_action_protect_all_nodes($form, &$form_state) {
  // First protect all nodes that already are in our table.
  db_update('protected_nodes')
    ->fields(array(
      'protected_node_is_protected' =>  1,
    ))
    ->condition('protected_node_is_protected', 0)
    ->execute();

  // Then update the table with ALL the existing nodes and as we're at it
  // we mark them as protected.
  $sql = "INSERT INTO {protected_nodes}";
  $sql .= " (SELECT n.nid, %d AS protected_node_show_title, 1 AS protected_node_is_protected, '' AS protected_node_passwd";
  $sql .= " FROM {node} n LEFT JOIN {protected_nodes} pn ON n.nid = pn.nid";
  $sql .= " WHERE pn.nid IS NULL)";
  // TODO Please convert this statement to the D7 database API syntax.
  db_query($sql, $form_state['values']['protected_node_show_node_titles']);
}

/**
 * Make sure all nodes with a password are protected.
 *
 * It is possible to unprotect a node and still have a password on it. This
 * function restores those nodes protection (i.e. sets the is_protected to 1).
 */
function protected_node_action_protect_nodes_with_password($form, &$form_state) {
  db_update('protected_nodes')
    ->fields(array(
      'protected_node_is_protected' =>  1,
    ))
    ->condition('protected_node_is_protected', 0)
    ->condition('protected_node_passwd', '', '<>')
    ->execute();
  drupal_set_message('All nodes with an existing password (excluding the global password) are now protected.');
}

/**
 * @brief Implementation of the hook_form_alter() for node types.
 *
 * This function adds a field set to each node type to let user decide whether
 * a node type is always protected, is never protected, or is protect-able.
 * If protect-able, then one can choose whether the protected node field set
 * should be always open, always closed, or smart (i.e. open if the node is
 * protected.)
 *
 * @param[in,out] $form  The node type form where our field set is added.
 */
function protected_node_node_type_form_alter(&$form) {
  $form['protected_node'] = array(
    '#type' => 'fieldset',
    '#title' => t('Protected node settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $options = array(
    PROTECTED_NODE_PROTECTION_NEVER => t('Never protected'),
    PROTECTED_NODE_PROTECTION_PROTECTABLE => t('Protectable (default is unprotected)'),
    PROTECTED_NODE_PROTECTION_PROTECTED => t('Protectable (default is protected)'),
    PROTECTED_NODE_PROTECTION_ALWAYS => t('Always protected'),
  );
  $form['protected_node']['protected_node_protection'] = array(
    '#type' => 'radios',
    '#title' => t('Protected mode for nodes of this type'),
    '#default_value' => variable_get('protected_node_protection_' . $form['#node_type']->type, PROTECTED_NODE_PROTECTION_PROTECTABLE),
    '#options' => $options,
    '#description' => t('Select the protection mode for nodes of this type:<ul><li>Never protected &mdash; the nodes cannot be protected</li><li>Protectable &mdash; lets users choose whether the node is protected, defaults to protected or unprotected.</li><li>Always protected &mdash; the node is automatically protected, the author has no choice.</li></ul>'),
  );

  $form['protected_node']['protected_node_node_type_password_field'] = array(
    '#type' => 'password_confirm',
    '#description' => t('Enter a node type password. This password is the default for all the nodes of this type.'),
    '#size' => 20,
    '#after_build' => array('protected_node_autocomplete_off'),
    '#title' => t('A default (global) password for nodes of this type'),
    // No default value.
    /* '#default_value' => variable_get('protected_node_node_password_' . $form['#node_type']->type, ''), */
  );

  $options = array(
    PROTECTED_NODE_FIELDSET_OPEN => t('Always open'),
    PROTECTED_NODE_FIELDSET_SMART => t('Smart mode (Open when protected)'),
    PROTECTED_NODE_FIELDSET_CLOSE => t('Always closed'),
  );
  $form['protected_node']['protected_node_fieldset'] = array(
    '#type' => 'radios',
    '#title' => t('How to show the protected node fieldset'),
    '#default_value' => variable_get('protected_node_fieldset_' . $form['#node_type']->type, PROTECTED_NODE_FIELDSET_SMART),
    '#options' => $options,
    '#description' => t('Select whether the "Protected node" field set should be opened or closed when editing a node.'),
  );

  $form['#validate'][] = '_protected_node_node_type_validate';
  $form['#submit'][] = '_protected_node_node_type_submit';
}

/**
 * Implements the validation of the protected node node type form.
 *
 * This function ensures that the password is encrypted.
 */
function _protected_node_node_type_validate($form, &$form_state) {
  if (!empty($form_state['values']['protected_node_node_type_password_field'])) {
    $form_state['values']['protected_node_node_type_password'] = sha1($form_state['values']['protected_node_node_type_password_field']);
  }
  else {
    $node_type = trim($form_state['values']['type']);
    $old_node_type = isset($form_state['values']['old_type']) ? $form_state['values']['old_type'] : '';
    if ($node_type != $old_node_type) {
      $passwd = variable_get('protected_node_node_type_password_' . $old_node_type, '');
      if ($passwd) {
        // Save only if available, we don't need an empty password.
        $form_state['values']['protected_node_node_type_password'] = $passwd;
      }
      variable_del('protected_node_node_type_password_' . $old_node_type);
    }
  }
  unset($form_state['values']['protected_node_node_type_password_field']);
}

/**
 * Do a little extra work on submission.
 */
function _protected_node_node_type_submit($form, &$form_state) {
  // Make doubly sure the unencrypted field doesn't get saved
  // (it should already be removed in the validate() function).
  unset($form_state['values']['protected_node_node_type_password_field']);

  // Delete the current password_field variable if it exists.
  $node_type = trim($form_state['values']['type']);
  variable_del('protected_node_node_type_password_field_' . $node_type);

  if (!empty($form_state['values']['old_type'])) {
    // Delete the old password field variable if it exists.
    $old_node_type = $form_state['values']['old_type'];
    variable_del('protected_node_node_type_password_field_' . $old_node_type);
  }
}
