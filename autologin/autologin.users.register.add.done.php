<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.done
[END_COT_EXT]
==================== */

if (($cfg['users']['regnoactivation'] || $db->countRows($db_users) == 1) && !isset($_REQUEST['gftype']) && !isset($_REQUEST['gptype']))
	{
			require_once cot_incfile('autologin', 'plug');
			cot_al_autologin($userid);
			cot_redirect(cot_url('users',array('m' => 'profile'),'',true));
	}