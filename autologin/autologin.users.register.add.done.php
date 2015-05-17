<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.done
[END_COT_EXT]
==================== */

if (($cfg['users']['regnoactivation'] || $db->countRows($db_users) == 1) && !isset($_REQUEST['gftype']) && !isset($_REQUEST['gptype']))
	{
			$row = $db->query("SELECT user_lostpass,user_token FROM $db_users WHERE user_id=?",$userid)->fetch();
			cot_redirect(cot_url('login', 'a=check&v='.$row['user_lostpass'].'&token='.$row['user_token'], '', true));    
	}