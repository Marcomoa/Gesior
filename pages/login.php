<?php
header('Content-Type: application/json');

if(!defined('INITIALIZED'))
    exit;

function sendError($msg){
	$ret = [];
	$ret["errorCode"] = 3;
	$ret["errorMessage"] = $msg;
	die(json_encode($ret));
}

$request = file_get_contents('php://input');
$result = json_decode($request);
$action = isset($result->type) ? $result->type : '';
$playersonline = $SQL->query("SELECT * FROM `players_online` WHERE 1")->fetchAll();

switch ($action) {
		case 'cacheinfo':
		die(json_encode([
			'playersonline' => (count($playersonline)),
			'twitchstreams' => 0,
			'twitchviewer' => 0,
			'gamingyoutubestreams' => 0,
			'gamingyoutubeviewer' => 0
		]));
	break;

	case 'eventschedule':
		die(json_encode([
			'eventlist' => []
		]));
	break;

	case 'boostedcreature':
		die(json_encode([
			'boostedcreature' => false,
		]));
	break;

	case 'login':
		$port = Website::getServerConfig()->getValue('gameProtocolPort');

		$world = [
			'id' => 0,
			'name' => Website::getServerConfig()->getValue('serverName'),
			'externaladdressprotected' => Website::getServerConfig()->getValue('ip'),
			'externalportprotected' => $port,
			'externaladdressunprotected' => Website::getServerConfig()->getValue('ip'),
			'externalportunprotected' => $port,
			'previewstate' => 0,
			'location' => 'BRA',
			'anticheatprotection' => false,
			'pvptype' => array_search(Website::getServerConfig()->getValue('worldType'), ['pvp', 'no-pvp', 'pvp-enforced']),
			'istournamentworld' => false,
			'restrictedstore' => false,
			'currenttournamentphase' => 2
		];

		$characters = [];
		$account = null;

		$columns = 'name, level, sex, vocation, looktype, lookhead, lookbody, looklegs, lookfeet, lookaddons, deleted, lastlogin';

		$account = new Account();
		$account->loadByName($result->accountname);
		$current_password = Website::encryptPassword($result->password);
		if (!$account->isLoaded() || !$account->isValidPassword($result->password)) {
			sendError('Account name or password is not correct.');
		}

        $players = $SQL->query("select {$columns} from players where account_id = " . $account->getId() . " order by name asc")->fetchAll();
		foreach ($players as $player) {
			$characters[] = create_char($player);
		}

		$worlds = [$world];
		$playdata = compact('worlds', 'characters');
		$session = [
			'sessionkey' => "$result->accountname\n$result->password",
			'lastlogintime' => (!$account) ? 0 : $account->getLastLogin(),
			'ispremium' => (!$account) ? true : $account->isPremium(),
			'premiumuntil' => (!$account) ? 0 : (time() + ($account->getPremDays() * 86400)),
			'status' => 'active',
			'returnernotification' => false,
			'showrewardnews' => true,
			'isreturner' => true,
			'fpstracking' => false,
			'optiontracking' => false,
			'tournamentticketpurchasestate' => 0,
			'emailcoderequest' => false
		];
		die(json_encode(compact('session', 'playdata')));
	break;

	default:
		sendError("Unrecognized event {$action}.");
	break;
}

function create_char($player) {
	return [
		'worldid' => 0,
		'name' => $player['name'],
		'ismale' => intval($player['sex']) === 1,
		'tutorial' => false,
		'level' => intval($player['level']),
		'vocation' => Website::getVocationName($player['vocation']),
		'outfitid' => intval($player['looktype']),
		'headcolor' => intval($player['lookhead']),
		'torsocolor' => intval($player['lookbody']),
		'legscolor' => intval($player['looklegs']),
		'detailcolor' => intval($player['lookfeet']),
		'addonsflags' => intval($player['lookaddons']),
		'ishidden' => intval($player['deletion']) === 1,
		'istournamentparticipant' => false,
		'remainingdailytournamentplaytime' => 0
	];
}
