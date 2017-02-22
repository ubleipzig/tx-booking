#
# Table structure for table 'tx_ubleipzigbooking'
#
CREATE TABLE tx_ubleipzigbooking_object (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	opening_times_storage varchar(255),
	booking_storage int(11),

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_ubleipzigbooking_booking'
#
CREATE TABLE tx_ubleipzigbooking (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	objectuid int(11) DEFAULT '0' NOT NULL,
	startdate int(11) DEFAULT '0' NOT NULL,
	enddate int(11) DEFAULT '0' NOT NULL,
	feuseruid int(11) DEFAULT '0' NOT NULL,
	memo varchar(255) DEFAULT '',

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_ubleipzigbooking_domain_model_closingday'
#
CREATE TABLE tx_ubleipzigbooking_domain_model_closingday (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	name VARCHAR(255) DEFAULT '',
	date int(11) not null,
	description TEXT DEFAULT '',

	PRIMARY KEY (uid),
	KEY parent (pid)
);

#
# Table structure for table 'tx_ubleipzigbooking_domain_model_dutyhours'
#
CREATE TABLE tx_ubleipzigbooking_domain_model_dutyhours (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	hours VARCHAR(255) DEFAULT '',
	week_day tinyint(4) not null,

	PRIMARY KEY (uid),
	KEY parent (pid),
	UNIQUE weekday (pid, week_day)
);