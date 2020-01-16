SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
-- Table structure for table `addons`
--
CREATE TABLE IF NOT EXISTS `addons` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isactive` tinyint(2) NOT NULL DEFAULT '0',
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

INSERT INTO `addons` (`id`, `name`, `isactive`, `dt_created`) VALUES
(1, 'DBRD', 1, '2017-03-30 09:31:32'),
(2, 'TLG', 1, '2017-03-30 09:31:32'),
(3, 'TPAY', 1, '2017-03-30 09:31:32'),
(4, 'GTLG', 1, '2017-03-30 09:31:32'),
(5, 'INV', 1, '2017-03-30 09:31:32'),
(6, 'GNC', 1, '2017-03-30 09:31:32');

--
-- Table structure for table `statuses`
--
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL,
  `seq_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;
--
-- Dumping data for table `statuses`
--
INSERT INTO `statuses` (`id`, `workflow_id`, `name`, `color`, `percentage`, `seq_order`) VALUES
(1, 0, 'New', '#F19A91', 0, 1),
(2, 0, 'In Progress', '#8dc2f8', 33, 2),
(3, 0, 'Closed', '#8ad6a3', 100, 4),
(5, 0, 'Resolved', '#f3c788', 66, 3);

ALTER TABLE `projects` ADD `workflow_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `logo`; 
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `code` char(3) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`id`),
  KEY `idx_currency_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


INSERT INTO `currencies` (`id`, `name`, `code`, `status`) VALUES
(1, 'Andorran Peseta', 'ADP', 'Active'),
(2, 'United Arab Emirates Dirham', 'AED', 'Active'),
(3, 'Afghanistan Afghani', 'AFA', 'Active'),
(4, 'Albanian Lek', 'ALL', 'Active'),
(5, 'Netherlands Antillian Guilder', 'ANG', 'Active'),
(6, 'Angolan Kwanza', 'AOK', 'Active'),
(7, 'Argentine Peso', 'ARS', 'Active'),
(9, 'Australian Dollar', 'AUD', 'Active'),
(10, 'Aruban Florin', 'AWG', 'Active'),
(11, 'Barbados Dollar', 'BBD', 'Active'),
(12, 'Bangladeshi Taka', 'BDT', 'Active'),
(14, 'Bulgarian Lev', 'BGN', 'Active'),
(15, 'Bahraini Dinar', 'BHD', 'Active'),
(16, 'Burundi Franc', 'BIF', 'Active'),
(17, 'Bermudian Dollar', 'BMD', 'Active'),
(18, 'Brunei Dollar', 'BND', 'Active'),
(19, 'Bolivian Boliviano', 'BOB', 'Active'),
(20, 'Brazilian Real', 'BRL', 'Active'),
(21, 'Bahamian Dollar', 'BSD', 'Active'),
(22, 'Bhutan Ngultrum', 'BTN', 'Active'),
(23, 'Burma Kyat', 'BUK', 'Active'),
(24, 'Botswanian Pula', 'BWP', 'Active'),
(25, 'Belize Dollar', 'BZD', 'Active'),
(26, 'Canadian Dollar', 'CAD', 'Active'),
(27, 'Swiss Franc', 'CHF', 'Active'),
(28, 'Chilean Unidades de Fomento', 'CLF', 'Active'),
(29, 'Chilean Peso', 'CLP', 'Active'),
(30, 'Yuan (Chinese) Renminbi', 'CNY', 'Active'),
(31, 'Colombian Peso', 'COP', 'Active'),
(32, 'Costa Rican Colon', 'CRC', 'Active'),
(33, 'Czech Republic Koruna', 'CZK', 'Active'),
(34, 'Cuban Peso', 'CUP', 'Active'),
(35, 'Cape Verde Escudo', 'CVE', 'Active'),
(36, 'Cyprus Pound', 'CYP', 'Active'),
(40, 'Danish Krone', 'DKK', 'Active'),
(41, 'Dominican Peso', 'DOP', 'Active'),
(42, 'Algerian Dinar', 'DZD', 'Active'),
(43, 'Ecuador Sucre', 'ECS', 'Active'),
(44, 'Egyptian Pound', 'EGP', 'Active'),
(45, 'Estonian Kroon (EEK)', 'EEK', 'Active'),
(46, 'Ethiopian Birr', 'ETB', 'Active'),
(47, 'Euro', 'EUR', 'Active'),
(49, 'Fiji Dollar', 'FJD', 'Active'),
(50, 'Falkland Islands Pound', 'FKP', 'Active'),
(52, 'British Pound', 'GBP', 'Active'),
(53, 'Ghanaian Cedi', 'GHC', 'Active'),
(54, 'Gibraltar Pound', 'GIP', 'Active'),
(55, 'Gambian Dalasi', 'GMD', 'Active'),
(56, 'Guinea Franc', 'GNF', 'Active'),
(58, 'Guatemalan Quetzal', 'GTQ', 'Active'),
(59, 'Guinea-Bissau Peso', 'GWP', 'Active'),
(60, 'Guyanan Dollar', 'GYD', 'Active'),
(61, 'Hong Kong Dollar', 'HKD', 'Active'),
(62, 'Honduran Lempira', 'HNL', 'Active'),
(63, 'Haitian Gourde', 'HTG', 'Active'),
(64, 'Hungarian Forint', 'HUF', 'Active'),
(65, 'Indonesian Rupiah', 'IDR', 'Active'),
(66, 'Irish Punt', 'IEP', 'Active'),
(67, 'Israeli Shekel', 'ILS', 'Active'),
(68, 'Indian Rupee', 'INR', 'Active'),
(69, 'Iraqi Dinar', 'IQD', 'Active'),
(70, 'Iranian Rial', 'IRR', 'Active'),
(73, 'Jamaican Dollar', 'JMD', 'Active'),
(74, 'Jordanian Dinar', 'JOD', 'Active'),
(75, 'Japanese Yen', 'JPY', 'Active'),
(76, 'Kenyan Schilling', 'KES', 'Active'),
(77, 'Kampuchean (Cambodian) Riel', 'KHR', 'Active'),
(78, 'Comoros Franc', 'KMF', 'Active'),
(79, 'North Korean Won', 'KPW', 'Active'),
(80, '(South) Korean Won', 'KRW', 'Active'),
(81, 'Kuwaiti Dinar', 'KWD', 'Active'),
(82, 'Cayman Islands Dollar', 'KYD', 'Active'),
(83, 'Lao Kip', 'LAK', 'Active'),
(84, 'Lebanese Pound', 'LBP', 'Active'),
(85, 'Sri Lanka Rupee', 'LKR', 'Active'),
(86, 'Liberian Dollar', 'LRD', 'Active'),
(87, 'Lesotho Loti', 'LSL', 'Active'),
(89, 'Libyan Dinar', 'LYD', 'Active'),
(90, 'Moroccan Dirham', 'MAD', 'Active'),
(91, 'Malagasy Franc', 'MGF', 'Active'),
(92, 'Mongolian Tugrik', 'MNT', 'Active'),
(93, 'Macau Pataca', 'MOP', 'Active'),
(94, 'Mauritanian Ouguiya', 'MRO', 'Active'),
(95, 'Maltese Lira', 'MTL', 'Active'),
(96, 'Mauritius Rupee', 'MUR', 'Active'),
(97, 'Maldive Rufiyaa', 'MVR', 'Active'),
(98, 'Malawi Kwacha', 'MWK', 'Active'),
(99, 'Mexican Peso', 'MXP', 'Active'),
(100, 'Malaysian Ringgit', 'MYR', 'Active'),
(101, 'Mozambique Metical', 'MZM', 'Active'),
(102, 'Namibian Dollar', 'NAD', 'Active'),
(103, 'Nigerian Naira', 'NGN', 'Active'),
(104, 'Nicaraguan Cordoba', 'NIO', 'Active'),
(105, 'Norwegian Kroner', 'NOK', 'Active'),
(106, 'Nepalese Rupee', 'NPR', 'Active'),
(107, 'New Zealand Dollar', 'NZD', 'Active'),
(108, 'Omani Rial', 'OMR', 'Active'),
(109, 'Panamanian Balboa', 'PAB', 'Active'),
(110, 'Peruvian Nuevo Sol', 'PEN', 'Active'),
(111, 'Papua New Guinea Kina', 'PGK', 'Active'),
(112, 'Philippine Peso', 'PHP', 'Active'),
(113, 'Pakistan Rupee', 'PKR', 'Active'),
(114, 'Polish Zloty', 'PLN', 'Active'),
(116, 'Paraguay Guarani', 'PYG', 'Active'),
(117, 'Qatari Rial', 'QAR', 'Active'),
(118, 'Romanian Leu', 'RON', 'Active'),
(119, 'Rwanda Franc', 'RWF', 'Active'),
(120, 'Saudi Arabian Riyal', 'SAR', 'Active'),
(121, 'Solomon Islands Dollar', 'SBD', 'Active'),
(122, 'Seychelles Rupee', 'SCR', 'Active'),
(123, 'Sudanese Pound', 'SDP', 'Active'),
(124, 'Swedish Krona', 'SEK', 'Active'),
(125, 'Singapore Dollar', 'SGD', 'Active'),
(126, 'St. Helena Pound', 'SHP', 'Active'),
(127, 'Sierra Leone Leone', 'SLL', 'Active'),
(128, 'Somali Schilling', 'SOS', 'Active'),
(129, 'Suriname Guilder', 'SRG', 'Active'),
(130, 'Sao Tome and Principe Dobra', 'STD', 'Active'),
(131, 'Russian Ruble', 'RUB', 'Active'),
(132, 'El Salvador Colon', 'SVC', 'Active'),
(133, 'Syrian Potmd', 'SYP', 'Active'),
(134, 'Swaziland Lilangeni', 'SZL', 'Active'),
(135, 'Thai Baht', 'THB', 'Active'),
(136, 'Tunisian Dinar', 'TND', 'Active'),
(137, 'Tongan Paanga', 'TOP', 'Active'),
(138, 'East Timor Escudo', 'TPE', 'Active'),
(139, 'Turkish Lira', 'TRY', 'Active'),
(140, 'Trinidad and Tobago Dollar', 'TTD', 'Active'),
(141, 'Taiwan Dollar', 'TWD', 'Active'),
(142, 'Tanzanian Schilling', 'TZS', 'Active'),
(143, 'Uganda Shilling', 'UGX', 'Active'),
(144, 'US Dollar', 'USD', 'Active'),
(145, 'Uruguayan Peso', 'UYU', 'Active'),
(146, 'Venezualan Bolivar', 'VEF', 'Active'),
(147, 'Vietnamese Dong', 'VND', 'Active'),
(148, 'Vanuatu Vatu', 'VUV', 'Active'),
(149, 'Samoan Tala', 'WST', 'Active'),
(150, 'CommunautÃ© FinanciÃ¨re Africaine BEAC, Francs', 'XAF', 'Active'),
(151, 'Silver, Ounces', 'XAG', 'Active'),
(152, 'Gold, Ounces', 'XAU', 'Active'),
(153, 'East Caribbean Dollar', 'XCD', 'Active'),
(154, 'International Monetary Fund (IMF) Special Drawing Rights', 'XDR', 'Active'),
(155, 'CommunautÃ© FinanciÃ¨re Africaine BCEAO - Francs', 'XOF', 'Active'),
(156, 'Palladium Ounces', 'XPD', 'Active'),
(157, 'Comptoirs FranÃ§ais du Pacifique Francs', 'XPF', 'Active'),
(158, 'Platinum, Ounces', 'XPT', 'Active'),
(159, 'Democratic Yemeni Dinar', 'YDD', 'Active'),
(160, 'Yemeni Rial', 'YER', 'Active'),
(161, 'New Yugoslavia Dinar', 'YUD', 'Active'),
(162, 'South African Rand', 'ZAR', 'Active'),
(163, 'Zambian Kwacha', 'ZMK', 'Active'),
(164, 'Zaire Zaire', 'ZRZ', 'Active'),
(165, 'Zimbabwe Dollar', 'ZWD', 'Active'),
(166, 'Slovak Koruna', 'SKK', 'Active'),
(167, 'Armenian Dram', 'AMD', 'Active');
ALTER TABLE `company_users` ADD `is_access_change` TINYINT NOT NULL DEFAULT '0' COMMENT '1-email changed, 2-password changed, 3-user to admin, 4-user to client, 5 - Admin to client, 6 - client to user, 7 - client to admin, 8 - disable user' AFTER `is_active`;
ALTER TABLE `company_users` ADD `change_timestamp` BIGINT NOT NULL DEFAULT '0' AFTER `is_access_change`;

--
-- Table structure for table `task_views`
--

CREATE TABLE `task_views` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sub_name` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task_views`
--

INSERT INTO `task_views` (`id`, `name`, `sub_name`, `created`) VALUES
(1, 'Task', 'List', '0000-00-00 00:00:00'),
(3, 'Task', 'Kanban', NULL),
(5, 'Task', 'Calendar', NULL),
(6, 'Milestone', 'Card', NULL),
(7, 'Milestone', 'Kanban', NULL),
(8, 'Project', 'Card', NULL),
(9, 'Project', 'List', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task_views`
--
ALTER TABLE `task_views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task_views`
--
ALTER TABLE `task_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Table structure for table `default_task_views`
--

CREATE TABLE `default_task_views` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_view_id` tinyint(2) NOT NULL DEFAULT '1',
  `milestone_view_id` int(11) DEFAULT NULL,
  `kanban_view_id` tinyint(2) NOT NULL DEFAULT '7',
  `timelog_view_id` tinyint(2) NOT NULL DEFAULT '5',
  `project_view_id` tinyint(2) NOT NULL DEFAULT '8',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `default_task_views`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `default_task_views`
--
ALTER TABLE `default_task_views`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `default_task_views`
--
ALTER TABLE `default_task_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
  
ALTER TABLE `milestones` ADD `assign_id` INT NULL AFTER `id_seq`;

-- Table structure for table `log_times`
--

CREATE TABLE IF NOT EXISTS `log_times` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `task_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` int(10) NOT NULL COMMENT 'stored in seconds',
  `is_billable` tinyint(2) NOT NULL COMMENT '1-billable,0-not billable',
  `description` text NOT NULL,
  `task_status` tinyint(4) NOT NULL COMMENT '1-closed, 0-not closed',
  `created` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `break_time` int(10) NOT NULL DEFAULT '0' COMMENT 'stored in seconds',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

ALTER TABLE `easycases` CHANGE `estimated_hours` `estimated_hours` INT(10) NOT NULL ;
ALTER TABLE `easycases` CHANGE `hours` `hours` INT(10) NOT NULL;


CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `payee_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `payment_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `issue_date` datetime NOT NULL,
  `receive_date` datetime DEFAULT NULL,
  `currency` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price` float DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `terms` text COLLATE utf8_unicode_ci,
  `payment_from` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_to` text COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `discount_type` enum('Percentage','Flat') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Percentage',
  `is_paid` tinyint(2) NOT NULL DEFAULT '0',
  `is_active` tinyint(2) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_activities`
--

CREATE TABLE `payment_activities` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `activity` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `invoice` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `rate` float DEFAULT NULL,
  `task_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `total_hours` float DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `task_status` tinyint(4) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_activities`
--
ALTER TABLE `payment_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `payment_activities`
--
ALTER TABLE `payment_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

CREATE TABLE IF NOT EXISTS `project_booked_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `easycase_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `booked_hours` int(11) NOT NULL,
  `overload` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Table structure for table `overloads`
--

CREATE TABLE `overloads` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `easycase_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `overload` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `overloads`
--
ALTER TABLE `overloads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `overloads`
--
ALTER TABLE `overloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Table structure for table `user_leaves`
--

CREATE TABLE `user_leaves` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `dt_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Indexes for table `user_leaves`
--
ALTER TABLE `user_leaves`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_leaves`
--
ALTER TABLE `user_leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;



-- Dashboard

ALTER TABLE `project_technologies` ADD `company_id` INT(11) NOT NULL AFTER `id`;
ALTER TABLE `project_technologies` CHANGE `technology_id` `technology` varchar(255) NULL DEFAULT NULL ;
ALTER TABLE `project_technologies` ADD `dt_created` DATETIME NOT NULL AFTER `technology`;




CREATE TABLE IF NOT EXISTS `technologies` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `technology` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dt_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `technologies`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `technologies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  

CREATE TABLE `project_types` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dt_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `project_types`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `project_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
 

CREATE TABLE `project_statuses` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dt_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `project_statuses`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `project_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
  

CREATE TABLE `business_units` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `business_unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dt_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `business_units`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `business_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
  
  
  CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
 ALTER TABLE `user_roles` ADD `company_id` INT(11) NOT NULL AFTER `role_name`;
 
 

CREATE TABLE IF NOT EXISTS `role_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
   `type_id` int(11)  NULL,
  `role_id` int(11) DEFAULT NULL,
  `rate` float(7,2) DEFAULT '0.00',
  `actual_rate` float(7,2) DEFAULT '0.00',
  `profit` float(7,2) DEFAULT '0.00',
  `dt_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 

ALTER TABLE  `users` ADD  `is_client` TINYINT( 2 ) NOT NULL DEFAULT  '0' COMMENT  '1- Invoice Client,0- Not Invoice Client' AFTER  `show_default_inner`;
ALTER TABLE `users` ADD `role_id` INT(11) NULL AFTER `is_client`;

ALTER TABLE `users` ADD `salary` BIGINT(20) NULL AFTER `role_id`; 
ALTER TABLE `users` ADD `contact_no` VARCHAR(255) NULL AFTER `salary`, ADD `address` TEXT NULL AFTER `contact_no`, ADD `city` VARCHAR(255) NULL AFTER `address`, ADD `country` VARCHAR(255) NULL AFTER `city`;
 

ALTER TABLE `companies` ADD `work_hour` FLOAT(5,2) NOT NULL DEFAULT '8.00' AFTER `contact_phone`;

ALTER TABLE `companies` ADD `work_days` INT(11) NOT NULL DEFAULT '5' AFTER `work_hour`;

ALTER TABLE `companies` ADD `is_allowed` INT(11) NOT NULL DEFAULT '0' COMMENT '0:not allowed--1:allowed' AFTER `is_deactivated`;
  


ALTER TABLE `projects` ADD `estimated_hours` DECIMAL(6,1) NULL DEFAULT '0.0' AFTER `isactive`, ADD `start_date` DATETIME NULL DEFAULT NULL AFTER `estimated_hours`, ADD `end_date` DATETIME NULL DEFAULT NULL AFTER `start_date`, ADD `budget` FLOAT(15,2) NULL DEFAULT '0.0' AFTER `end_date`, ADD `cost_approved` FLOAT(15,2) NULL DEFAULT '0.0' AFTER `budget`, ADD `status_id` INT(11) NULL DEFAULT NULL AFTER `cost_approved`, ADD `type_id` INT(11) NULL DEFAULT NULL AFTER `status_id`, ADD `technology_id` INT(11) NULL DEFAULT NULL AFTER `type_id`, ADD `manager` INT(11) NULL DEFAULT NULL AFTER `technology_id`, ADD `hourly_rate` INT(11) NULL DEFAULT '0' AFTER `manager`, ADD `min_range_percent` INT(11) NULL DEFAULT '0' AFTER `hourly_rate`;

ALTER TABLE `projects` ADD `max_range_percent` INT(11) NULL DEFAULT '0' AFTER `min_range_percent`;
ALTER TABLE `projects`  ADD `currency` VARCHAR(50) NULL DEFAULT 'USD'  AFTER `max_range_percent`;

ALTER TABLE `projects` ADD `industry_type` VARCHAR(80) NULL AFTER `currency`;

ALTER TABLE `projects` ADD `invoice_customer_id` INT(11) NOT NULL DEFAULT '0' AFTER `industry_type`;

-- ALTER TABLE `projects` ADD `rate` FLOAT(5,2) NULL DEFAULT NULL AFTER `isactive` ;


ALTER TABLE `easycases` CHANGE `due_date` `due_date` DATETIME NULL DEFAULT NULL;

ALTER TABLE `easycases` ADD `gantt_start_date` DATETIME NULL DEFAULT NULL AFTER `assign_to`;

ALTER TABLE `easycases` ADD `case_reminder` INT(11) NULL DEFAULT '0' COMMENT '0->No,1->Yes' AFTER `gantt_start_date`;


ALTER TABLE `user_notifications` ADD `case_reminder` INT(11) NOT NULL DEFAULT '0' COMMENT '0->No,1->Yes' AFTER `weekly_usage_alert`;


CREATE TABLE `invoice_customers` (
  `id` int(10) NOT NULL,
  `uniq_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_id` int(10) NOT NULL DEFAULT '0',
  `project_id` int(10) DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `city` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `organization` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  `user_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `invoice_customers`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `invoice_customers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;


CREATE TABLE IF NOT EXISTS `industries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_display` tinyint(3) NOT NULL DEFAULT '1' COMMENT '0:No, 1: Yes'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


INSERT INTO `industries` (`id`, `name`, `is_display`) VALUES
(1, 'Accounting', 1),
(2, 'Automobile', 1),
(3, 'Architecture & Planning', 1),
(4, 'Banking', 1),
(5, 'Broadcasting', 1),
(6, 'Capital Markets', 1),
(7, 'Construction & Manufacturing', 1),
(8, 'Consumer Services', 1),
(9, 'Education', 1),
(10, 'Entertainment', 1),
(11, 'E-Commerce', 1),
(12, 'Financial Services & Insurance', 1),
(13, 'Hospitality', 1),
(14, 'Health Services', 1),
(15, 'Human Resources', 1),
(16, 'Import and Export', 1),
(17, 'Information Technology and Services', 1),
(18, 'Leisure, Travel & Tourism', 1),
(19, 'Logistics and Supply Chain', 1),
(20, 'Marketing and Advertising', 1),
(21, 'Newspaper & Online Media', 1),
(22, 'Online Booking', 1),
(23, 'Pharmaceuticals', 1),
(24, 'Photography', 1),
(25, 'Real Estate', 1),
(26, 'Sports & Gaming', 1),
(27, 'Staffing and Recruiting', 1),
(28, 'Transportation', 1),
(29, 'Venture Capital & Private Equity', 1),
(30, 'Others', 1),
(31, 'wqerqwerqwerqwer', 0),
(32, 'testetewwetwewe', 0),
(33, 'sfasdf', 0),
(34, 'In-house', 0);



-- Gantt Chart

ALTER TABLE `easycases` ADD `seq_id` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `easycases` ADD `depends` varchar(255) NULL, ADD `children` varchar(255) NULL AFTER `depends`;

CREATE TABLE `gantt_settings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `access_type` int(11) NOT NULL DEFAULT '0' COMMENT '0->Not allowed,1->only view,2->view and edit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `gantt_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `gantt_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;




-- search filter table 

CREATE TABLE `search_filters` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `json_array` text,
  `first_records` int(1) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `search_filters`
  ADD PRIMARY KEY (`id`);
  
  
ALTER TABLE `search_filters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
  
  
ALTER TABLE `users` ADD `custom_active_dashboard_tab` INT NULL AFTER `show_default_inner`;


-- previous legend

ALTER TABLE `easycases` ADD `previous_legend` INT NULL AFTER `status`;



  
ALTER TABLE `users` ADD `time_format` INT NULL DEFAULT '1' COMMENT '1- 12 , 2- 24' AFTER `country`, ADD `date_format` INT NULL DEFAULT '1' COMMENT '1- mmm dd, yyy, 2- dd mmm,yyyy'AFTER `time_format`;


-- Invoice

CREATE TABLE IF NOT EXISTS `invoice_activities` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `company_id` int(12) NOT NULL,
  `project_id` int(12) NOT NULL,
  `invoice_id` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `created` datetime NOT NULL,
  `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `activity` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `invoice_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_id` int(11) DEFAULT NULL,
  `rate` float DEFAULT NULL,
  `task_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `total_hours` float DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `task_status` tinyint(4) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniq_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `issue_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `currency` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float DEFAULT NULL,
  `po_number` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `terms` text COLLATE utf8_unicode_ci,
  `invoice_from` text COLLATE utf8_unicode_ci,
  `invoice_to` text COLLATE utf8_unicode_ci,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `discount_type` enum('Percentage','Flat') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Percentage',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `invoice_term` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `invoice_settings` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `company_id` int(12) NOT NULL,
  `project_id` int(12) NOT NULL DEFAULT '0',
  `layout` enum('portrait','landscape') NOT NULL DEFAULT 'portrait',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  `invoices` ADD  `is_paid` TINYINT( 2 ) NOT NULL DEFAULT  '0' AFTER  `discount_type` ,ADD  `is_active` TINYINT( 2 ) NOT NULL DEFAULT  '1' AFTER  `is_paid`;