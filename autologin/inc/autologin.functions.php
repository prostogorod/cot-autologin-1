<?php defined('COT_CODE') or die('Wrong URL');

function cot_al_autologin($userid)
{
	global $usr, $sys, $cfg,$db, $db_users, $db_online, $_SESSION;
	$row = $db->query("SELECT * FROM $db_users WHERE user_id=?",$userid)->fetch();
	$token = cot_unique(16);

	$sid = hash_hmac('sha256', $row['user_password'] . $row['user_sidtime'], $cfg['secret_key']);

	if (empty($row['user_sid']) || $row['user_sid'] != $sid
		|| $row['user_sidtime'] + $cfg['cookielifetime'] < $sys['now'])
	{
		$sid = hash_hmac('sha256', $row['user_password'] . $sys['now'], $cfg['secret_key']);
		$update_sid = ", user_sid = " . $db->quote($sid) . ", user_sidtime = " . $sys['now'];
	}else{
		$update_sid = '';
	}

	$db->query("UPDATE $db_users SET user_lastip='{$usr['ip']}', user_lastlog = {$sys['now']}, user_logcount = user_logcount + 1, user_token = '$token' $update_sid WHERE user_id={$row['user_id']}");

	$sid = hash_hmac('sha1', $sid, $cfg['secret_key']);
	$u = base64_encode($row['user_id'].':'.$sid);
	$_SESSION[$sys['site_id']] = $u;	
	
	$res = $db->query("SHOW TABLES LIKE '$db_online'");
	if (!empty($db_online) && $res->rowCount() == 1){
		$db->query("DELETE FROM $db_online WHERE online_userid='-1' AND online_ip='" . $usr['ip'] . "' LIMIT 1");
	}       
}