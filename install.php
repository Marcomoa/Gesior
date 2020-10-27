<?PHP
error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);
define('INITIALIZED', true);
define('ONLY_PAGE', false);
if (!file_exists('install.txt')) {
	echo ('AAC installation is disabled. To enable it make file <b>install.php</b> in main AAC directory and put there your IP.');
	exit;
}

$installIP = trim(file_get_contents('install.txt'));
if ($installIP != $_SERVER['REMOTE_ADDR']) {
	echo ('In file <b>install.txt</b> must be your IP!<br />In file is:<br /><b>' . $installIP . '</b><br />Your IP is:<br /><b>' . $_SERVER['REMOTE_ADDR'] . '</b>');
	exit;
}

$time_start = microtime(true);
session_start();

function autoLoadClass($className)
{
	if (!class_exists($className))
		if (file_exists('./classes/' . strtolower($className) . '.php'))
			include_once('./classes/' . strtolower($className) . '.php');
		else
			new Error_Critic('#E-7', 'Cannot load class <b>' . $className . '</b>, file <b>./classes/class.' . strtolower($className) . '.php</b> doesn\'t exist');
}

spl_autoload_register('autoLoadClass');

$config = array();
include('./config/config.php');

$page = '';
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
$step = 'start';

if (isset($_REQUEST['step']))
	$step = $_REQUEST['step'];

function getServerPath()
{
	$config = array();
	include('./config/config.php');
	return $config['site']['serverPath'];
}

function setServerPath($newPath)
{
	$file = fopen("./config/config.php", "r");
	$lines = array();
	while (!feof($file)) {
		$lines[] = fgets($file);
	}
	fclose($file);

	$newConfig = array();
	foreach ($lines as $i => $line) {
		if (substr($line, 0, strlen('$config[\'site\'][\'serverPath\']')) == '$config[\'site\'][\'serverPath\']')
			$newConfig[] = '$config[\'site\'][\'serverPath\'] = "' . str_replace('"', '\"', $newPath) . '";' . PHP_EOL;
		else
			$newConfig[] = $line;
	}
	Website::putFileContents("./config/config.php", implode('', $newConfig));
}
if ($page == '') {
	echo '<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
		<title>Gesior AAC - Instalation</title>
	</head>
	<frameset cols="230,*">
		<frame name="menu" src="install.php?page=menu" />
		<frame name="step" src="install.php?page=step&step=start" />
		<noframes><body>Frames don\'t work. Install Firefox :P</body></noframes>
	</frameset>
	</html>';
} elseif ($page == 'menu') {
	echo '<h2>MENU</h2><br>
	<b>IF NOT INSTALLED:</b><br>
	<a href="install.php?page=step&step=start" target="step">0. Informations</a><br>
	<a href="install.php?page=step&step=1" target="step">1. Set server path</a><br>
	<a href="install.php?page=step&step=2" target="step">2. Check DataBase connection</a><br>
	<a href="install.php?page=step&step=3" target="step">3. Add tables and columns to DB</a><br>
	<a href="install.php?page=step&step=4" target="step">4. Add samples to DB</a><br>
	<a href="install.php?page=step&step=5" target="step">5. Set Admin Account</a><br>
	<b>Author:</b><br>
	Marco Oliveira<br>
	Compatible with TFS 1.3</a>';
} elseif ($page == 'step') {
	if ($step >= 2 && $step <= 5) {
		if (Website::getWebsiteConfig()->getValue('useServerConfigCache')) {
			if (Website::fileExists('./config/server.config.php')) {
				$tmp_php_config = new ConfigPHP('./config/server.config.php');
				$config['server'] = $tmp_php_config->getConfig();
			} else {
				$tmp_lua_config = new ConfigLUA(Website::getWebsiteConfig()->getValue('serverPath') . 'config.lua');
				$config['server'] = $tmp_lua_config->getConfig();
				$tmp_php_config = new ConfigPHP();
				$tmp_php_config->setConfig($tmp_lua_config->getConfig());
				$tmp_php_config->saveToFile('./config/server.config.php');
			}
		} else {
			$tmp_lua_config = new ConfigLUA(Website::getWebsiteConfig()->getValue('serverPath') . 'config.lua');
			$config['server'] = $tmp_lua_config->getConfig();
		}

		if (Website::getServerConfig()->isSetKey('mysqlHost')) {
			define('SERVERCONFIG_SQL_HOST', 'mysqlHost');
			define('SERVERCONFIG_SQL_PORT', 'mysqlPort');
			define('SERVERCONFIG_SQL_USER', 'mysqlUser');
			define('SERVERCONFIG_SQL_PASS', 'mysqlPass');
			define('SERVERCONFIG_SQL_DATABASE', 'mysqlDatabase');
		} else
			new Error_Critic('#E-3', 'There is no key <b>mysqlHost</b> in server config', array(
				new Error('INFO', 'use server config cache: <b>' . (Website::getWebsiteConfig()->getValue('useServerConfigCache') ? 'true' : 'false') . '</b>')
			));

		Website::setDatabaseDriver(Database::DB_MYSQL);
		if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_HOST))
			Website::getDBHandle()->setDatabaseHost(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_HOST));
		else
			new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_HOST . '</b> in server config file.');

		if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PORT))
			Website::getDBHandle()->setDatabasePort(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PORT));
		else
			new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_PORT . '</b> in server config file.');

		if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_DATABASE))
			Website::getDBHandle()->setDatabaseName(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_DATABASE));
		else
			new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_DATABASE . '</b> in server config file.');

		if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_USER))
			Website::getDBHandle()->setDatabaseUsername(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_USER));
		else
			new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_USER . '</b> in server config file.');

		if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PASS))
			Website::getDBHandle()->setDatabasePassword(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PASS));
		else
			new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_PASS . '</b> in server config file.');
		Website::updatePasswordEncryption();
		$SQL = Website::getDBHandle();
	}

	if ($step == 'start') {
		echo '<h1>STEP ' . $step . '</h1>Informations<br>';
		echo 'Welcome to Gesior Account Maker installer. <b>After 5 simple steps account maker will be ready to use!</b><br />';

		$writeable = array(
			'config/config.php',
			'cache',
			'cache/flags',
			'cache/signatures',
			'cache/usercounter.txt',
			'cache/serverstatus.txt'
		);
		foreach ($writeable as $fileToWrite) {
			if (!file_exists($fileToWrite)) {
				if (!touch($fileToWrite))
					echo '<span style="color:red">CANNOT WRITE/CREATE TO FILE/DIR: <b>' . $fileToWrite . '</b> - edit file/dir access for PHP</span><br />';
				else
					echo '<span style="color:green">CAN WRITE TO FILE/DIR: <b>' . $fileToWrite . '</b></span><br />';
			} elseif (is_writable($fileToWrite))
				echo '<span style="color:green">CAN WRITE TO FILE/DIR: <b>' . $fileToWrite . '</b></span><br />';
			else
				echo '<span style="color:red">CANNOT WRITE TO FILE/DIR: <b>' . $fileToWrite . '</b> - edit file/dir access for PHP</span><br />';
		}

		$executable = array(
			'cache',
			'cache/flags',
			'cache/signatures',
			'./'
		);

		foreach ($executable as $file) {
			if (!file_exists($file)) {
				echo '<span style="color:red">DOES NOT EXIST: <b>' . $file . '</b> </span><br />';
			} elseif (is_executable($file))
				echo '<span style="color:green">CAN EXECUTE FILE/DIR: <b>' . $file . '</b></span><br />';
			else
				echo '<span style="color:red">CANNOT EXECUTE FILE/DIR: <b>' . $file . '</b> - edit file access for PHP</span><br />';
		}
	} elseif ($step == 1) {
		if (isset($_REQUEST['server_path'])) {
			echo '<h1>STEP '.$step.'</h1>Check server configuration<br>';
			$path = $_REQUEST['server_path'];
			$path = trim($path)."\\";
			$path = str_replace("\\\\", "/", $path);
			$path = str_replace("\\", "/", $path);
			$path = str_replace("//", "/", $path);
			setServerPath($path);
			$tmp_lua_config = new ConfigLUA($path . 'config.lua');
			$config['server'] = $tmp_lua_config->getConfig();
			if (isset($config['server']['mysqlHost'])) {
				echo 'File <b>config.lua</b> loaded from <font color="red"><i>' . $path . 'config.lua</i></font>. It looks like fine server config file. Now you can check database connection: <a href="install.php?page=step&step=2">STEP 2 - check database connection</a>';
			} else {
				echo 'File <b>config.lua</b> loaded from <font color="red"><i>' . $path . 'config.lua</i></font> and it\'s not valid TFS config.lua file. <a href="install.php?page=step&step=1">Go to STEP 1 - select other directory.</a> If it\'s your config.lua file from TFS contact with acc. maker author.';
			}
		} else {
			echo 'Please write you TFS directory below. Like: <i>C:\Documents and Settings\Gesior\Desktop\TFS 1.3\</i><form action="install.php">
			<input type="text" name="server_path" size="90" value="' . htmlspecialchars(getServerPath()) . '" /><input type="hidden" name="page" value="step" /><input type="hidden" name="step" value="1" /><input type="submit" value="Set server path" />
			</form>';
		}
	} elseif ($step == 2) {
		echo '<h1>STEP ' . $step . '</h1>Check database connection<br>';
		echo 'If you don\'t see any errors press <a href="install.php?page=step&step=3">link to STEP 3 - Add tables and columns to DB</a>. If you see some errors it mean server has wrong configuration. Check FAQ or ask author of acc. maker.<br />';
		$SQL->connect();
	} elseif ($step == 3) {
		echo '<h1>STEP ' . $step . '</h1>Add tables and columns to DB<br>';
		echo 'Installer try to add new tables and columns to database.<br>';
		$columns = array();
		// Accounts:
		$columns[] = array('accounts', 'authToken', 'VARCHAR', '100', '');
		$columns[] = array('accounts', 'birth_date', 'VARCHAR', '50', '');
		$columns[] = array('accounts', 'coins', 'INT', '12', '0');
		$columns[] = array('accounts', 'create_date', 'INT', '11', '0');
		$columns[] = array('accounts', 'create_ip', 'INT', '11', '0');
		$columns[] = array('accounts', 'email_code', 'VARCHAR', '255', '');
		$columns[] = array('accounts', 'email_new_time', 'INT', '11', '0');
		$columns[] = array('accounts', 'email_new', 'VARCHAR', '255', '');
		$columns[] = array('accounts', 'flag', 'VARCHAR', '80', '');
		$columns[] = array('accounts', 'gender', 'VARCHAR', '20', '0');
		$columns[] = array('accounts', 'guild_points_stats', 'INT', '11', '0');
		$columns[] = array('accounts', 'guild_points', 'INT', '11', '0');
		$columns[] = array('accounts', 'key', 'VARCHAR', '20', '0');
		$columns[] = array('accounts', 'last_post', 'INT', '11', '0');
		$columns[] = array('accounts', 'location', 'VARCHAR', '255', '');
		$columns[] = array('accounts', 'loyalty_points', 'BIGINT', '20', '0');
		$columns[] = array('accounts', 'next_email', 'INT', '11', '0');
		$columns[] = array('accounts', 'page_access', 'INT', '11', '0');
		$columns[] = array('accounts', 'rlname', 'VARCHAR', '255', '');
		$columns[] = array('accounts', 'vote', 'INT', '11', '0');

		//Guilds:
		$columns[] = array('guilds', 'create_ip', 'INT', '11', '0');
		$columns[] = array('guilds', 'description', 'TEXT', '', NULL);
		$columns[] = array('guilds', 'guild_logo', 'MEDIUMBLOB', '', NULL);

		//Guilds Invites:
		$columns[] = array('guild_invites', 'date', 'INT', '11', '');

		//Players:
		$columns[] = array('players', 'cast', 'TINYINT', '1', '0');
		$columns[] = array('players', 'comment', 'TEXT', '', NULL);
		$columns[] = array('players', 'create_date', 'INT', '11', '0');
		$columns[] = array('players', 'create_ip', 'INT', '11', '0');
		$columns[] = array('players', 'deleted', 'TINYINT', '1', '0');
		$columns[] = array('players', 'description', 'VARCHAR', '255', '');
		$columns[] = array('players', 'former', 'VARCHAR', '255', '-');
		$columns[] = array('players', 'hide_char', 'INT', '11', '0');
		$columns[] = array('players', 'hide_set', 'INT', '11', NULL);
		$columns[] = array('players', 'hide_skills', 'INT', '11', NULL);
		$columns[] = array('players', 'loyalty_ranking', 'TINYINT', '1', '0');
		$columns[] = array('players', 'marriage_spouse', 'INT', '11', NULL);
		$columns[] = array('players', 'marriage_status', 'BIGINT', '20', '0');
		$columns[] = array('players', 'signature', 'VARCHAR', '255', '');

		$tables = array();
		// mysql tables
		$tables[Database::DB_MYSQL]['announcements'] = "CREATE TABLE `announcements` (
			`id` int(10) NOT NULL ,
			`title` varchar(50) NOT NULL,
			`text` varchar(255) NOT NULL,
			`date` varchar(20) NOT NULL,
			`author` varchar(50) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['newsticker'] = "CREATE TABLE `newsticker` (
			`id` int(10) UNSIGNED NOT NULL ,
			`date` int(11) NOT NULL,
			`text` varchar(255) NOT NULL,
			`icon` varchar(50) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['pagseguro_transactions'] = "CREATE TABLE IF NOT EXISTS `pagseguro_transactions` (
			`transaction_code` varchar(36) NOT NULL,
			`name` varchar(200) DEFAULT NULL,
			`payment_method` varchar(50) NOT NULL,
			`status` varchar(50) NOT NULL,
			`item_count` int(11) NOT NULL,
			`data` datetime NOT NULL,
			`payment_amount` float DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['player_former_names'] = "CREATE TABLE IF NOT EXISTS `player_former_names` (
			`id` int(11) NOT NULL ,
			`player_id` int(11) NOT NULL,
			`former_name` varchar(35) NOT NULL,
			`date` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['tickets'] = "CREATE TABLE IF NOT EXISTS `tickets` (
			`ticket_id` int(11) NOT NULL ,
			`ticket_subject` varchar(45) NOT NULL,
			`ticket_author` varchar(255) NOT NULL,
			`ticket_author_acc_id` int(11) NOT NULL,
			`ticket_last_reply` varchar(45) NOT NULL,
			`ticket_admin_reply` int(11) NOT NULL,
			`ticket_date` datetime NOT NULL,
			`ticket_ended` varchar(45) NOT NULL,
			`ticket_status` varchar(45) NOT NULL,
			`ticket_category` varchar(45) NOT NULL,
			`ticket_description` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;";

		$tables[Database::DB_MYSQL]['tickets_reply'] = "CREATE TABLE IF NOT EXISTS `tickets_reply` (
			`ticket_replyid` int(11) NOT NULL ,
			`ticket_id` int(11) NOT NULL,
			`reply_author` varchar(255) DEFAULT NULL,
			`reply_message` text,
			`reply_date` datetime DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_forum'] = "CREATE TABLE IF NOT EXISTS `z_forum` (
			`id` int(11) NOT NULL ,
			`first_post` int(11) NOT NULL DEFAULT '0',
			`last_post` int(11) NOT NULL DEFAULT '0',
			`section` int(3) NOT NULL DEFAULT '0',
			`replies` int(20) NOT NULL DEFAULT '0',
			`views` int(20) NOT NULL DEFAULT '0',
			`author_aid` int(20) NOT NULL DEFAULT '0',
			`author_guid` int(20) NOT NULL DEFAULT '0',
			`post_text` text NOT NULL,
			`post_topic` varchar(255) NOT NULL,
			`post_smile` tinyint(1) NOT NULL DEFAULT '0',
			`post_date` int(20) NOT NULL DEFAULT '0',
			`last_edit_aid` int(20) NOT NULL DEFAULT '0',
			`edit_date` int(20) NOT NULL DEFAULT '0',
			`post_ip` varchar(15) NOT NULL DEFAULT '0.0.0.0',
			`icon_id` int(11) NOT NULL,
			`news_icon` varchar(50) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_ots_comunication'] = "CREATE TABLE IF NOT EXISTS `z_ots_comunication` (
			`id` int(11) NOT NULL ,
			`name` varchar(255) NOT NULL,
			`type` varchar(255) NOT NULL,
			`action` varchar(255) NOT NULL,
			`param1` varchar(255) NOT NULL,
			`param2` varchar(255) NOT NULL,
			`param3` varchar(255) NOT NULL,
			`param4` varchar(255) NOT NULL,
			`param5` varchar(255) NOT NULL,
			`param6` varchar(255) NOT NULL,
			`param7` varchar(255) NOT NULL,
			`delete_it` int(2) NOT NULL DEFAULT '1'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_ots_guildcomunication'] = "CREATE TABLE IF NOT EXISTS `z_ots_guildcomunication` (
			`id` int(11) NOT NULL ,
			`name` varchar(255) NOT NULL,
			`type` varchar(255) NOT NULL,
			`action` varchar(255) NOT NULL,
			`param1` varchar(255) NOT NULL,
			`param2` varchar(255) NOT NULL,
			`param3` varchar(255) NOT NULL,
			`param4` varchar(255) NOT NULL,
			`param5` varchar(255) NOT NULL,
			`param6` varchar(255) NOT NULL,
			`param7` varchar(255) NOT NULL,
			`delete_it` int(2) NOT NULL DEFAULT '1'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_polls'] = "CREATE TABLE IF NOT EXISTS `z_polls` (
			`id` int(11) NOT NULL ,
			`question` varchar(255) NOT NULL,
			`end` int(11) NOT NULL,
			`start` int(11) NOT NULL,
			`answers` int(11) NOT NULL,
			`votes_all` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_polls_answers'] = "CREATE TABLE IF NOT EXISTS `z_polls_answers` (
			`poll_id` int(11) NOT NULL ,
			`answer_id` int(11) NOT NULL,
			`answer` varchar(255) NOT NULL,
			`votes` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_shop_category'] = "CREATE TABLE IF NOT EXISTS `z_shop_category` (
			`id` int(11) NOT NULL ,
			`name` varchar(50) NOT NULL,
			`desc` varchar(255) NOT NULL,
			`button` varchar(50) NOT NULL,
			`hide` int(11) NOT NULL DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		INSERT INTO `z_shop_category` (`id`, `name`, `desc`, `button`, `hide`) VALUES
		(0, 'Extra Services', 'Buy an extra service to transfer a character to another game world, to change your character name or sex, to change your account name, or to get a new recovery key.', '_sbutton_getextraservice.gif', 0),
		(1, 'Mounts', 'Buy your characters one or more of the fabulous mounts offered here.', '_sbutton_getmount.gif', 1),
		(2, 'Outfits', 'Buy your characters one or more of the fancy outfits offered here.', '_sbutton_getoutfit.gif', 1),
		(3, 'Items', 'Buy items for your character be more stronger in the game.', '_sbutton_getextraservice.gif', 1);";

		$tables[Database::DB_MYSQL]['z_shop_donates'] = "CREATE TABLE IF NOT EXISTS `z_shop_donates` (
			`id` int(11) NOT NULL ,
			`date` int(11) NOT NULL,
			`reference` varchar(50) NOT NULL,
			`account_name` varchar(50) NOT NULL,
			`method` varchar(50) NOT NULL,
			`price` varchar(20) NOT NULL,
			`coins` int(11) NOT NULL,
			`status` varchar(20) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_shop_donate_confirm'] = "CREATE TABLE IF NOT EXISTS `z_shop_donate_confirm` (
			`id` int(11) NOT NULL ,
			`date` int(11) NOT NULL,
			`account_name` varchar(50) NOT NULL,
			`donate_id` int(11) NOT NULL,
			`msg` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_shop_history_item'] = "CREATE TABLE IF NOT EXISTS `z_shop_history_item` (
			`id` int(11) NOT NULL ,
			`to_name` varchar(255) NOT NULL DEFAULT '0',
			`to_account` int(11) NOT NULL DEFAULT '0',
			`from_nick` varchar(255) NOT NULL,
			`from_account` int(11) NOT NULL DEFAULT '0',
			`price` int(11) NOT NULL DEFAULT '0',
			`offer_id` varchar(255) NOT NULL DEFAULT '',
			`trans_state` varchar(255) NOT NULL,
			`trans_start` int(11) NOT NULL DEFAULT '0',
			`trans_real` int(11) NOT NULL DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$tables[Database::DB_MYSQL]['z_shop_offer'] = "CREATE TABLE IF NOT EXISTS `z_shop_offer` (
			`id` int(11) NOT NULL ,
			`category` int(11) NOT NULL,
			`coins` int(11) NOT NULL DEFAULT '0',
			`price` varchar(50) NOT NULL,
			`itemid` int(11) NOT NULL DEFAULT '0',
			`mount_id` varchar(100) NOT NULL,
			`addon_name` varchar(100) NOT NULL,
			`count` int(11) NOT NULL DEFAULT '0',
			`offer_type` varchar(255) DEFAULT NULL,
			`offer_description` text NOT NULL,
			`offer_name` varchar(255) NOT NULL,
			`offer_date` int(11) NOT NULL,
			`default_image` varchar(50) NOT NULL,
			`hide` int(11) NOT NULL DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		INSERT INTO `z_shop_offer` (`id`, `category`, `coins`, `price`, `itemid`, `mount_id`, `addon_name`, `count`, `offer_type`, `offer_description`, `offer_name`, `offer_date`, `default_image`, `hide`) VALUES
		(0, 2, 250, '', 0, '', '', 1, 'changename', 'Buy a character name change to rename one of your characters.', 'Character Change Name', 1416865577, 'changename.png', 0),
		(1, 2, 10, '', 0, '', '', 1, 'changesex', 'Buy a character sex change to turn your male character into a female one, or your female character into a male one.', 'Character Change Sex', 1416874417, 'changesex.png', 1),
		(2, 2, 250, '', 0, '', '', 1, 'changeaccountname', 'Buy an account name change to select a different name for your account.', 'Account Name Change', 1416874601, 'changeaccountname.png', 0),
		(3, 2, 300, '', 0, '', '', 1, 'newrk', 'If you need a new recovery key, you can order it here. Note that the letter for the new recovery key can only be sent to the address in the account registration.', 'Recovery Key', 1416874816, 'newrk.png', 0);";

		$tables[Database::DB_MYSQL]['z_shop_payment'] = "CREATE TABLE IF NOT EXISTS `z_shop_payment` (
			`id` int(11) NOT NULL ,
			`ref` varchar(10) NOT NULL,
			`account_name` varchar(50) NOT NULL,
			`service_id` int(11) NOT NULL,
			`service_category_id` int(11) NOT NULL,
			`payment_method_id` int(11) NOT NULL,
			`price` varchar(50) NOT NULL,
			`coins` int(11) UNSIGNED NOT NULL,
			`status` varchar(50) NOT NULL DEFAULT 'waiting',
			`date` int(11) NOT NULL,
			`gift` int(11) NOT NULL DEFAULT '0'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		foreach ($columns as $column) {
			if ($column[4] === NULL) {
				if ($SQL->query('ALTER TABLE ' . $SQL->tableName($column[0]) . ' ADD ' . $SQL->fieldName($column[1]) . ' ' . $column[2] . '  NULL DEFAULT NULL'))
					echo "<span style=\"color:green\">Column <b>" . $column[1] . "</b> added to table <b>" . $column[0] . "</b>.</span><br />";
				else
					echo "Could not add column <b>" . $column[1] . "</b> to table <b>" . $column[0] . "</b>. Already exist?<br />";
			} else {
				if ($SQL->query('ALTER TABLE ' . $SQL->tableName($column[0]) . ' ADD ' . $SQL->fieldName($column[1]) . ' ' . $column[2] . '' . (($column[3] == '') ? '' : '(' . $column[3] . ')') . ' NOT NULL DEFAULT \'' . $column[4] . '\'') !== false)
					echo "<span style=\"color:green\">Column <b>" . $column[1] . "</b> added to table <b>" . $column[0] . "</b>.</span><br />";
				else
					echo "Could not add column <b>" . $column[1] . "</b> to table <b>" . $column[0] . "</b>. Already exist?<br />";
			}
		}
		foreach ($tables[$SQL->getDatabaseDriver()] as $tableName => $tableQuery) {
			if ($SQL->query($tableQuery) !== false)
				echo "<span style=\"color:green\">Table <b>" . $tableName . "</b> created.</span><br />";
			else
				echo "Could not create table <b>" . $tableName . "</b>. Already exist?<br />";
		}
		echo 'Tables and columns added to database.<br>Go to <a href="install.php?page=step&step=4">STEP 4 - Add samples</a>';
	} elseif ($step == 4) {
		echo '<h1>STEP ' . $step . '</h1>Add samples to DB:<br>';
		$samplePlayers = array();
		$samplePlayers[0] = 'Rook Sample';
		$samplePlayers[1] = 'Sorcerer Sample';
		$samplePlayers[2] = 'Druid Sample';
		$samplePlayers[3] = 'Paladin Sample';
		$samplePlayers[4] = 'Knight Sample';

		$account = new Account(1, Account::LOADTYPE_NAME);
		if (!$account->isLoaded()) {
			$account = new Account();
			$account->setName(1);
			$account->setPassword(1);
			$account->setMail(rand(0, 999999) . '@gmail.com');
			$account->setPageAccess(3);
			$account->setFlag('pl');
			$account->setCreateIP(Visitor::getIP());
			$account->setCreateDate(time());
			$account->save();
		}
		$newPlayer = new Player('Account Manager', Player::LOADTYPE_NAME);
		if (!$newPlayer->isLoaded()) {
			$newPlayer = new Player();
			$newPlayer->setComment('');
			$newPlayer->setName('Account Manager');
			$newPlayer->setAccountID($account->getID());
			$newPlayer->setLevel(8);
			$newPlayer->setExperience(4200);
			$newPlayer->setGroupID(1);
			$newPlayer->setVocation(0);
			$newPlayer->setHealth(185);
			$newPlayer->setHealthMax(185);
			$newPlayer->setMana(35);
			$newPlayer->setManaMax(35);
			$newPlayer->setTown(1);
			$newPlayer->setSoul(100);
			$newPlayer->setCapacity(420);
			$newPlayer->setSave(1);
			$newPlayer->setStamina(2520);
			$newPlayer->setLookType(128);
			$newPlayer->setLookBody(44);
			$newPlayer->setLookFeet(98);
			$newPlayer->setLookHead(15);
			$newPlayer->setLookLegs(76);
			$newPlayer->setLookAddons(0);
			$newPlayer->setSkill(0, 10);
			$newPlayer->setSkill(1, 10);
			$newPlayer->setSkill(2, 10);
			$newPlayer->setSkill(3, 10);
			$newPlayer->setSkill(4, 10);
			$newPlayer->setSkill(5, 10);
			$newPlayer->setSkill(6, 10);
			$newPlayer->setSkillCount(0, 0);
			$newPlayer->setSkillCount(1, 0);
			$newPlayer->setSkillCount(2, 0);
			$newPlayer->setSkillCount(3, 0);
			$newPlayer->setSkillCount(4, 0);
			$newPlayer->setSkillCount(5, 0);
			$newPlayer->setSkillCount(6, 0);
			$newPlayer->save();
		}

		if ($newPlayer->isLoaded()) {
			foreach ($samplePlayers as $vocationID => $name) {
				$samplePlayer = new Player($name, Player::LOADTYPE_NAME);
				if (!$samplePlayer->isLoaded()) {
					$samplePlayer = new Player('Account Manager', Player::LOADTYPE_NAME);
					$samplePlayer->setID(null);
					$samplePlayer->setName($name);
					$samplePlayer->setVocation($vocationID);
					$samplePlayer->setGroupID(1);
					$samplePlayer->setLookType(128);
					$samplePlayer->save();
					echo '<span style="color:green">Added sample character: </span><span style="color:green;font-weight:bold">' . $name . '</span><br/>';
				} else
					echo 'Sample character: <span style="font-weight:bold">' . $name . '</span> already exist in database<br/>';
			}
		} else
			new Error_Critic('', 'Character <i>Account Manager</i> does not exist. Cannot install sample characters!');
	} elseif ($step == 5) {
		echo '<h1>STEP ' . $step . '</h1>Set Admin Account<br>';
		if (empty($_REQUEST['saveaccpassword'])) {
			echo 'Admin account login is: <b>1</b><br/>Set new password to this account.<br>';
			echo 'New password: <form action="install.php" method=POST><input type="text" name="newpass" size="35">(Don\'t give it password to anyone!)';
			echo '<input type="hidden" name="saveaccpassword" value="yes"><input type="hidden" name="page" value="step"><input type="hidden" name="step" value="5"><input type="submit" value="SET"></form><br>If account with login 1 doesn\'t exist installator will create it and set your password.';
		} else {
			include_once('./system/load.compat.php');
			$newpass = trim($_POST['newpass']);
			if (!check_password($newpass))
				echo 'Password contains illegal characters. Please use only a-Z and 0-9. <a href="install.php?page=step&step=5">GO BACK</a> and write other password.';
			else {
				$account = new Account(1, Account::LOADTYPE_NAME);
				if ($account->isLoaded()) {
					$account->setPassword($newpass);
					$account->setPageAccess(3);
					$account->setFlag('pl');
					$account->save();
				} else {
					$newAccount = new Account();
					$newAccount->setName(1);
					$newAccount->setPassword($newpass);
					$newAccount->setMail(rand(0, 999999) . '@gmail.com');
					$newAccount->setPageAccess(3);
					$newAccount->setFlag('pl');
					$newAccount->setCreateIP(Visitor::getIP());
					$newAccount->setCreateDate(time());
				}

				$_SESSION['account'] = 1;
				$_SESSION['password'] = $newpass;
				$logged = true;
				echo '<h1>Admin account login: 1<br>Admin account password: ' . $newpass . '</h1><br/><h3>It\'s end of installation. Installation is blocked!</h3>';
				if (!unlink('install.txt'))
					new Error_Critic('', 'Cannot remove file <i>install.txt</i>. You must remove it to disable installer. I recommend you to go to step <i>0</i> and check if any other file got problems with WRITE permission.');
			}
		}
	}
}
