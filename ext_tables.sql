#
# Table structure for table 'tx_ublbooking_domain_model_room'
#
CREATE TABLE tx_ublbooking_domain_model_room (
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
# Table structure for table 'tx_ublbooking_domain_model_booking'
#
CREATE TABLE tx_ublbooking_domain_model_booking (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	room int(11) DEFAULT '0' NOT NULL,
	time int(11) DEFAULT '0' NOT NULL,
	fe_user int(11) DEFAULT '0' NOT NULL,
	comment varchar(255) DEFAULT '',

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY room (room),
	KEY time (time),
	KEY fe_user (fe_user),
	UNIQUE one_booking_per_room_and_time (room, time),
);

#
# Table structure for table 'tx_ublbooking_domain_model_closingday'
#
CREATE TABLE tx_ublbooking_domain_model_closingday (
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
	KEY parent (pid),
	KEY date (date),
);

#
# Table structure for table 'tx_ublbooking_domain_model_openinghours'
#
CREATE TABLE tx_ublbooking_domain_model_openinghours (
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