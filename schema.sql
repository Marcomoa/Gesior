-- Create Tables
CREATE TABLE IF NOT EXISTS `announcements` (
    `id` int(10) NOT NULL ,
    `title` varchar(50) NOT NULL,
    `text` varchar(255) NOT NULL,
    `date` varchar(20) NOT NULL,
    `author` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `newsticker` (
    `id` int(10) UNSIGNED NOT NULL ,
    `date` int(11) NOT NULL,
    `text` varchar(255) NOT NULL,
    `icon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pagseguro_transactions` (
    `transaction_code` varchar(36) NOT NULL,
    `name` varchar(200) DEFAULT NULL,
    `payment_method` varchar(50) NOT NULL,
    `status` varchar(50) NOT NULL,
    `item_count` int(11) NOT NULL,
    `data` datetime NOT NULL,
    `payment_amount` float DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `player_former_names` (
    `id` int(11) NOT NULL ,
    `player_id` int(11) NOT NULL,
    `former_name` varchar(35) NOT NULL,
    `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tickets` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

CREATE TABLE IF NOT EXISTS `tickets_reply` (
    `ticket_replyid` int(11) NOT NULL ,
    `ticket_id` int(11) NOT NULL,
    `reply_author` varchar(255) DEFAULT NULL,
    `reply_message` text,
    `reply_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_forum` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_ots_comunication` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_ots_guildcomunication` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_polls` (
    `id` int(11) NOT NULL ,
    `question` varchar(255) NOT NULL,
    `end` int(11) NOT NULL,
    `start` int(11) NOT NULL,
    `answers` int(11) NOT NULL,
    `votes_all` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_polls_answers` (
    `poll_id` int(11) NOT NULL ,
    `answer_id` int(11) NOT NULL,
    `answer` varchar(255) NOT NULL,
    `votes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_shop_category` (
    `id` int(11) NOT NULL ,
    `name` varchar(50) NOT NULL,
    `desc` varchar(255) NOT NULL,
    `button` varchar(50) NOT NULL,
    `hide` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_shop_donates` (
    `id` int(11) NOT NULL ,
    `date` int(11) NOT NULL,
    `reference` varchar(50) NOT NULL,
    `account_name` varchar(50) NOT NULL,
    `method` varchar(50) NOT NULL,
    `price` varchar(20) NOT NULL,
    `coins` int(11) NOT NULL,
    `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_shop_donate_confirm` (
    `id` int(11) NOT NULL ,
    `date` int(11) NOT NULL,
    `account_name` varchar(50) NOT NULL,
    `donate_id` int(11) NOT NULL,
    `msg` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_shop_history_item` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `z_shop_offer` (
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

CREATE TABLE IF NOT EXISTS `z_shop_payment` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Alter Tables
ALTER TABLE `accounts`
    ADD `coins` int(12) NOT NULL DEFAULT '0',
    ADD `key` varchar(20) NOT NULL DEFAULT '0',
    ADD `create_ip` int(11) NOT NULL DEFAULT '0',
    ADD `page_access` int(11) NOT NULL DEFAULT '0',
    ADD `location` varchar(255) NOT NULL DEFAULT '0',
    ADD `rlname` varchar(255) NOT NULL DEFAULT '0',
    ADD `birth_date` varchar(50) NOT NULL DEFAULT '0',
    ADD `gender` varchar(20) NOT NULL DEFAULT '0',
    ADD `guild_points_stats` int(11) NOT NULL DEFAULT '0',
    ADD `email_new` varchar(255) NOT NULL DEFAULT '0',
    ADD `email_new_time` int(11) NOT NULL DEFAULT '0',
    ADD `email_code` varchar(255) NOT NULL DEFAULT '0',
    ADD `next_email` int(11) NOT NULL DEFAULT '0',
    ADD `create_date` int(11) NOT NULL DEFAULT '0',
    ADD `last_post` int(11) NOT NULL DEFAULT '0',
    ADD `flag` varchar(80) NOT NULL DEFAULT '',
    ADD `vote` int(11) NOT NULL DEFAULT '0',
    ADD `loyalty_points` bigint(20) NOT NULL DEFAULT '0',
    ADD `guild_points` int(11) NOT NULL DEFAULT '0',
    ADD `authToken` varchar(100) NOT NULL;

ALTER TABLE `guilds`
    ADD `description` text NOT NULL,
    ADD `create_ip` int(11) NOT NULL DEFAULT '0',
    ADD `guild_logo` mediumblob;

ALTER TABLE `guild_invites`
    ADD `date` int(11) NOT NULL;

ALTER TABLE `players`
    ADD `signature` varchar(255) NOT NULL,
    ADD `marriage_spouse` int(11) NOT NULL DEFAULT '-1',
    ADD `marriage_status` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
    ADD `deleted` tinyint(1) NOT NULL DEFAULT '0',
    ADD `description` varchar(255) NOT NULL DEFAULT '',
    ADD `comment` text NOT NULL,
    ADD `create_ip` int(11) NOT NULL DEFAULT '0',
    ADD `create_date` int(11) NOT NULL DEFAULT '0',
    ADD `hide_char` int(11) NOT NULL DEFAULT '0',
    ADD `cast` tinyint(1) NOT NULL DEFAULT '0',
    ADD `hide_skills` int(11) DEFAULT NULL,
    ADD `hide_set` int(11) DEFAULT NULL,
    ADD `former` varchar(255) NOT NULL DEFAULT '-',
    ADD `loyalty_ranking` tinyint(1) NOT NULL DEFAULT '0';

-- Insert Tables
INSERT INTO `z_shop_category` (`id`, `name`, `desc`, `button`, `hide`) VALUES
(2, 'Extra Services', 'Buy an extra service to transfer a character to another game world, to change your character name or sex, to change your account name, or to get a new recovery key.', '_sbutton_getextraservice.gif', 0),
(3, 'Mounts', 'Buy your characters one or more of the fabulous mounts offered here.', '_sbutton_getmount.gif', 1),
(4, 'Outfits', 'Buy your characters one or more of the fancy outfits offered here.', '_sbutton_getoutfit.gif', 1),
(5, 'Items', 'Buy items for your character be more stronger in the game.', '_sbutton_getextraservice.gif', 1);

INSERT INTO `z_shop_offer` (`id`, `category`, `coins`, `price`, `itemid`, `mount_id`, `addon_name`, `count`, `offer_type`, `offer_description`, `offer_name`, `offer_date`, `default_image`, `hide`) VALUES
(5, 2, 250, '', 0, '', '', 1, 'changename', 'Buy a character name change to rename one of your characters.', 'Character Change Name', 1416865577, 'changename.png', 0),
(6, 2, 10, '', 0, '', '', 1, 'changesex', 'Buy a character sex change to turn your male character into a female one, or your female character into a male one.', 'Character Change Sex', 1416874417, 'changesex.png', 1),
(7, 2, 250, '', 0, '', '', 1, 'changeaccountname', 'Buy an account name change to select a different name for your account.', 'Account Name Change', 1416874601, 'changeaccountname.png', 0),
(8, 2, 300, '', 0, '', '', 1, 'newrk', 'If you need a new recovery key, you can order it here. Note that the letter for the new recovery key can only be sent to the address in the account registration.', 'Recovery Key', 1416874816, 'newrk.png', 0);

INSERT INTO `accounts`
(`id`,  `name`, `password`,                           `type`) VALUES
(1,     '1',  '356a192b7913b04c54574d18c28d46e6395428ab',  5);

INSERT INTO `players`
(`id`, `name`,           `group_id`, `account_id`, `level`, `vocation`, `health`, `healthmax`, `experience`, `lookbody`, `lookfeet`, `lookhead`, `looklegs`, `looktype`, `mana`, `manamax`, `town_id`, `conditions`, `cap`, `sex`) VALUES
(1,    'Community Manager',       3,          1,            1,       0,          150,      150,         0,            106,        95,         78,         116,        128,        5,      5,         2,         '',           400,   1 );
