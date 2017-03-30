-- noinspection SqlNoDataSourceInspectionForFile

create database IF NOT EXISTS SchoolDB_group4;

use SchoolDB_group4;

CREATE TABLE IF NOT EXISTS Student(
StudentID 		INTEGER,
StudentName 	CHAR(80),
UserName        CHAR(80),
Password        CHAR(80),
PRIMARY KEY (StudentID));

CREATE TABLE IF NOT EXISTS Course(
	CourseID 		CHAR(10),
	cSubject 		CHAR(100),
	Credits  		INTEGER,
	PRIMARY KEY (CourseID));

CREATE TABLE IF NOT EXISTS Fees(
	billNum		INTEGER,
	CourseID		CHAR(10) NOT NULL,
	amountDue 		REAL,
	PRIMARY KEY(billNum, amountDue),
	FOREIGN KEY (CourseID) REFERENCES Course(CourseID)
	ON DELETE CASCADE
	ON UPDATE CASCADE);

CREATE TABLE IF NOT EXISTS Pays(
	StudentID 		INTEGER,
	billNum 		INTEGER,
	amountDue			REAL,
	amountPaid			REAL,
	PRIMARY KEY(StudentID, amountDue, billNum),
	FOREIGN KEY(StudentID) REFERENCES Student(StudentID),
	FOREIGN KEY(billNum, amountDue) REFERENCES Fees(billNum,amountDue));


CREATE TABLE IF NOT EXISTS Schedules_Room(
	roomID			CHAR(10),
	CourseID		CHAR(10),
	daysofWeek		CHAR(6),
	Capacity		INTEGER,
	timeSlot		CHAR(20),
	roomtype		CHAR(80),
	PRIMARY KEY(roomID, courseID),
	FOREIGN KEY(CourseID) REFERENCES Course(CourseID));

CREATE TABLE IF NOT EXISTS Takes(
StudentID		INTEGER,
CourseID		CHAR(10),
PRIMARY KEY(StudentID, CourseID),
FOREIGN KEY(StudentID) references Student(StudentID),
FOREIGN KEY(CourseID) references Course(CourseID));

CREATE TABLE IF NOT EXISTS Professor(
    profID		INTEGER,
	profName		CHAR(20),
	officeLocation	CHAR(20),
	Rating		INTEGER,
	Password		CHAR(20),
	PRIMARY KEY(profID));

CREATE TABLE IF NOT EXISTS Research(
    rID			CHAR(6) NOT NULL ,
	profID		INTEGER NOT NULL AUTO_INCREMENT,
	Thesis 		CHAR(200),
	reGrant			REAL,
	labLocation		CHAR(20),
	PRIMARY KEY(rID, profID),
FOREIGN KEY(profID) REFERENCES Professor(profID)

ON DELETE CASCADE
ON UPDATE CASCADE);

CREATE TABLE IF NOT EXISTS TA(
	StudentID	INTEGER,
	TAID		CHAR(6),
	taState 	CHAR(30),
	WageperHour	REAL,
	PRIMARY KEY(TAID),
	FOREIGN KEY(StudentID) REFERENCES Student(StudentID));


CREATE TABLE IF NOT EXISTS TAs_Course(
	TAID		CHAR(6),
	CourseID	CHAR(10),
	taHours		CHAR(50),
	PRIMARY KEY (CourseID),
	FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
	FOREIGN KEY (TAID) references TA(TAID)
	ON DELETE NO ACTION
	ON UPDATE CASCADE);

CREATE TABLE IF NOT EXISTS TA_Helps_Research(
  TAID			CHAR(6),
	rID			CHAR(6) NOT NULL ,
	profID		INTEGER NOT NULL,
	PRIMARY KEY (TAID, rID, profID),
	FOREIGN KEY (rID, profID) REFERENCES Research(rID, profID),
	FOREIGN KEY (TAID) references TA(TAID));



insert into Student(StudentID, StudentName, UserName, Password) values
(11110011, 'Keyla Hughes', 'khughes', 'c4ts4lyfe'),
(11110012, 'Abagail Petersen','aPetersen', 'l0l0l0l0l'),
(11110013, 'Grady Potts', 'gPotts', 't1m3turn3r'),
(11110014, 'Kara Jackson', 'kJackson', 'fg43g2g1'),
(11110015, 'Charlotte Vang', 'cVang', 'f9ewjg09');

insert into Course(CourseID, cSubject, Credits) values
('CPSC 322', 'Artificial Intelligence', 3),
('CPSC 310', 'Software Engineering', 3),
('CPSC 311', 'Definition of Programming Languages', 2),
('CPSC 304', 'Relational Databases', 3),
('CPSC 221', 'Introduction to Algorithms', 3);

insert into Fees(billNum, CourseID, amountDue) values
(7681, 'CPSC 322', 568.91),
(7833,
 'CPSC 310',
 620.51),
(7688,
 'CPSC 311',
 123.25),
(7767,
 'CPSC 304',
 456.72),
(8765,
 'CPSC 221',
 487.25);

insert into Pays values
(11110011, 7681, 568.91, 0),
(11110012, 7833,  620.51, 0),
(11110013, 7688, 123.25, 0),
(11110014, 7767, 456.72, 0),
(11110015, 8765, 487.25, 0);

insert into Schedules_Room values
('HDP110', 'CPSC 322', 'MWF', 200, '11am - 12pm', 'Lecture Hall'),
('HDP310', 'CPSC 310', 'TR', 120, '1pm-2:30pm', 'Lecture Hall'),
('Swing121', 'CPSC 311', 'TR', 190, '7pm-9pm', 'Lecture Hall' ),
('PSB1201', 'CPSC 304', 'MWF', 10, '8am-12pm', 'Studio'),
('ICCS330', 'CPSC 221', 'MWF', 30, '2pm - 3pm', 'Lab');


insert into Takes values
(11110011, 'CPSC 322'),
(11110012, 'CPSC 310'),
(11110013, 'CPSC 311'),
(11110014, 'CPSC 304'),
(11110015, 'CPSC 221');

insert into Professor values
(11, 'Allan', 'ICCS 241', 4, 'Allanb0mb'),
(22, 'Bill', 'ICCS 320', 3, '1PnchM4n'),
(33, 'Claire', 'ICCS 220', 7, 'Br4nchB0und'),
(44, 'Eric', 'ICCS 110', 5, 'R0ckstr7'),
(55, 'Fred', 'ICCS 310', 9, 'ImaLmb3rj4k');

insert into Research values
('RE0012', 11, 'Math', 5000.00, 'buch101'),
('RE0013', 22, 'physics', 10000.00, 'henn201'),
('RE0014', 33, 'biology', 8000.00, 'biol101'),
('RE0015', 44, 'chemistry', 3000.00, 'chem100'),
('RE0016', 55, 'astronomy', 1500.00, 'henn300');


insert into TA values
(11110011, 'TA0023', 'Working', 21.13),
(11110012, 'TA0024', 'Working', 21.13),
(11110013, 'TA0046', 'Not Working', 0),
(11110014, 'TA0047', 'Working', 21.13),
(11110015, 'TA0058', 'Not Working', 0);

insert into TAs_Course values
('TA0023', 'CPSC 322', 'Wed 8am-4pm'),
('TA0024', 'CPSC 310', 'Mon 7am - 11am'),
('TA0046', 'CPSC 311', 'Tue 8am-4pm'),
('TA0047', 'CPSC 304', 'Thur 9am - 5pm'),
('TA0058', 'CPSC 221', 'Fri  6pm - 10pm');


insert into TA_Helps_Research values
('TA0023', 'RE0012', 11),
('TA0024', 'RE0013', 22),
('TA0046', 'RE0014', 33),
('TA0047', 'RE0015', 44),
('TA0058', 'RE0016', 55);
