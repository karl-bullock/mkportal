<?php
/*
+--------------------------------------------------------------------------
|   MkPortal
|   ========================================
|   by Meo aka Luponero <Amedeo de longis>
|      Don K. Colburn <visiblesoul.net>
|
|   Copyright (c) 2003-2008 mkportal.it
|   http://www.mkportal.it
|   Email: luponero@mclink.it
|
+---------------------------------------------------------------------------
|
|   > MKPortal
|   > Written By Amedeo de longis
|   > Date started: 9.2.2004
|
+--------------------------------------------------------------------------
*/
if (!defined("IN_MKP")) {
    die ("Sorry !! You cannot access this file directly.");
}


define('IN_PHPBB', true);

error_reporting(E_ALL ^ E_NOTICE);

$phpbb_root_path = $MK_PATH.$FORUM_PATH."/";
$mkportals->base_url = $phpbb_root_path."index.php";
$mkportals->forum_url = $MK_PATH.$FORUM_PATH;
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'config.'.$phpEx);
include($phpbb_root_path . 'includes/functions_user.'.$phpEx); //usergroup functions

$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum');

$to_require = $MK_PATH."mkportal/include/mk_mySQL.php";
require ($to_require);
$DB = new db_driver;
$DB->obj['dbname'] = $dbname;
$DB->obj['dbuser'] = $dbuser;
$DB->obj['dbpasswd'] = $dbpasswd;
$DB->obj['dbhost'] = $dbhost;
$DB->connect();

$DB->query("SET NAMES 'utf8'");

$mkportals->member['id'] = intval($user->data['user_id']);
$mkportals->member['name'] = $user->data['username'];
$mkportals->member['name'] = str_replace( "'", "&#39;", $mkportals->member['name'] );
$mkportals->member['ip'] = $user->ip;
$mkportals->member['email'] = $user->data['user_email'];

if($user->data['user_id'] == ANONYMOUS || $user->data['is_bot']) {
	$mkportals->member['id'] = '';
}
$mkportals->member['last_visit'] = $user->data['session_last_visit'];
$mkportals->member['session_id'] = $user->session_id;

$mkportals->member['user_new_privmsg'] = $user->data['user_new_privmsg'].'/'.$user->data['user_unread_privmsg'];
if (isset($user->data['is_registered']) && $user->data['is_registered'] && $user->data['user_last_privmsg'] > $user->data['session_last_visit'] && $user->data['user_new_privmsg'] > 0) {
	$mkportals->member['show_popup'] = 1;
}

$mkportals->member['timezone'] = ($user->data['user_id'] != ANONYMOUS) ? $user->data['user_timezone']:$config['board_timezone'];
/*
$mkportals->member['mgroup'] = intval($user->data['group_id']);

if($mkportals->member['mgroup'] == 5) {
	$mkportals->member['g_access_cp'] = 1;
}
*/

//Get user's groups
$get_groups = group_memberships('', $user->data['user_id']);
foreach ($get_groups as $key => $value) {
	$mkp_group[] = $value['group_id'];
}

$mkportals->member['mgroup'] = $mkp_group;

//Get ADMINISTRATORS Group ID. In a new install it is "5" but in practice it can be any number.
$DB->query("SELECT group_id FROM " . GROUPS_TABLE . " WHERE group_name = 'ADMINISTRATORS'");
$result = $DB->fetch_row();

//Assign Portal CP access
$mkportals->member['g_access_cp'] = (in_array($result['group_id'], $mkportals->member['mgroup'], TRUE )) ? 1 : 0 ;

$mkportals->member['theme'] = $user->theme['theme_id'];
if (empty($user->theme['theme_id'])) {
		$mkportals->member['theme'] = $config['default_style'];
}

$mkportals->member['theme_path'] = $user->theme['template_path'];

$mkportals->member['mk_lang'] = $user->lang_name;
if (empty($mkportals->member['mk_lang'])) {
	$mkportals->member['mk_lang'] = $config['default_lang'];
}

// board interfaced !! Now start mkportal query count
$DB->query_count = 0;

/* Debug
print_r ($user);
exit;
*/


?>
