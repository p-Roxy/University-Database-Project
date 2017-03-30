drop database SchoolDB_group4;

create database SchoolDB_group4;

use SchoolDB_group4;

CREATE TABLE Student(
StudentID 		INTEGER,
StudentName 	CHAR(80),
UserName        CHAR(80),
Password        CHAR(80),
PRIMARY KEY (StudentID));

CREATE TABLE  Course(
	CourseID 		CHAR(10),
	cSubject 		CHAR(100),
	Credits  		INTEGER,
	PRIMARY KEY (CourseID));

CREATE TABLE Fees(
	billNum		INTEGER,
	CourseID		CHAR(10) NOT NULL,
	amountDue 		REAL,
	PRIMARY KEY(billNum),
	FOREIGN KEY (CourseID) REFERENCES Course(CourseID)
	ON DELETE CASCADE
	ON UPDATE CASCADE);

CREATE TABLE Pays(
	StudentID 		INTEGER,
	billNum 		INTEGER,
	amountTotal			REAL,
	amountPaid			REAL,
	PRIMARY KEY(StudentID, billNum),
	FOREIGN KEY(StudentID) REFERENCES Student(StudentID),
	FOREIGN KEY(billNum) REFERENCES Fees(billNum));


CREATE TABLE  Schedules_Room(
	roomID			CHAR(10),
	CourseID		CHAR(10),
	daysofWeek		CHAR(6),
	Capacity		INTEGER,
	timeSlot		CHAR(20),
	roomtype		CHAR(80),
	PRIMARY KEY(roomID, courseID),
	FOREIGN KEY(CourseID) REFERENCES Course(CourseID));

CREATE TABLE  Takes(
StudentID		INTEGER,
CourseID		CHAR(10),
PRIMARY KEY(StudentID, CourseID),
FOREIGN KEY(StudentID) references Student(StudentID),
FOREIGN KEY(CourseID) references Course(CourseID) );

CREATE TABLE Professor(
    profID		INTEGER,
	profName		CHAR(20),
	officeLocation	CHAR(20),
	Rating		INTEGER,
	Password		CHAR(20),
	PRIMARY KEY(profID));

CREATE TABLE Research(
    rID			CHAR(6),
	profID		INTEGER NOT NULL,
	Thesis 		CHAR(200),
	reGrant			REAL,
	labLocation		CHAR(20),
	PRIMARY KEY(rID, profID),
FOREIGN KEY(profID) REFERENCES Professor(profID)
ON DELETE CASCADE
ON UPDATE CASCADE);

CREATE TABLE TA(
	StudentID	INTEGER,
	TAID		CHAR(6),
	taState 	CHAR(30),
	WageperHour	REAL,
	PRIMARY KEY(TAID),
	FOREIGN KEY(StudentID) REFERENCES Student(StudentID));


CREATE TABLE TAs_Course(
	TAID		CHAR(6),
	CourseID	CHAR(10),
	taHours		CHAR(50),
	PRIMARY KEY (CourseID),
	FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
	FOREIGN KEY (TAID) references TA(TAID)
	ON DELETE NO ACTION
	ON UPDATE CASCADE);

CREATE TABLE TA_Helps_Research(
TAID			CHAR(6),
	rID			CHAR(6),
	profID		INTEGER,
	PRIMARY KEY (rID, profID),
	FOREIGN KEY (rID, profID) REFERENCES Research(rID, profID),
	FOREIGN KEY (TAID) references TA(TAID));


--Insert instances into tables from part 2--
--Student--
insert into Student (StudentID, StudentName, UserName, Password) values
(11110011, 11110012, 11110013, 11110014,11110015), ('Keyla Hughes', 'Abagail Petersen', 'Grady Potts', 'Kara Jackson', 'Charlotte Vang'),
('khughes', 'aPetersen','aPetersen', 'kJackson', 'cVang'), ('c4ts4lyfe', 'l0l0l0l0l', 't1m3turn3r', 'fg43g2g1',  'f9ewjg09')

--Course--
insert into Course (CourseID, cSubject, Credits) values
('CPSC 322', 'CPSC 310','CPSC 311','CPSC 304','CPSC 221'),('Artificial Intelligence', 'Software Engineering','Definition of Programming Languages','Relational Databases','Introduction to Algorithms'),
(3, 3, 2, 3, 3)

--Fees--
insert into Fees (billNum, CourseID, amountDue) values
(7681, 7833, 7688, 7767, 8765),('CPSC 322', 'CPSC 310','CPSC 311','CPSC 304','CPSC 221'), (568.91, 620.51, 123.25, 456.72, 487.25)

--Pays--
insert into Pays (StudentID, billNum, amountTotal, amountPaid) values
(11110011, 11110012, 11110013, 11110014,11110015),(7681, 7833, 7688, 7767, 8765),(568.91, 620.51, 123.25, 456.72, 487.25), (0,0,0,0,0)

--Schedules Rooms--
insert into Schedules_Room values
('HDP110','HDP310', 'Swing121','PSB1201','ICCS330'),('CPSC 322', 'CPSC 310','CPSC 311','CPSC 304','CPSC 221'), (200,120,190,10,30),
('11am - 12pm','1pm-2:30pm', '7pm-9pm','8am-12pm','2pm - 3pm'), ('Lecture Hall','Lecture Hall','Lecture Hall','Studio', 'Lab')


--Takes--
insert into Takes (StudentID, CourseID) values
(11110011, 11110012, 11110013, 11110014,11110015),('CPSC 322', 'CPSC 310','CPSC 311','CPSC 304','CPSC 221')

--Professor--
insert into Professor values
(11, 22, 33, 44, 55), ('Allan', 'Bill', 'Claire', 'Eric', 'Fred'), ('ICCS 241','ICCS 321','ICCS 220','ICCS 110','ICCS 310'), (4,3,7,5,9),
('Allanb0mb','1PnchM4n','Br4nchB0und','R0ckstr7', 'ImaLmb3rj4k')

--Research--
insert into Research values
('RE0012', 11, 'Math', 5000.00, 'buch101')
insert into Research values
('RE0013', 22, 'physics', 10000.00, 'henn201')
insert into Research values
('RE0014', 33, 'biology', 8000.00, 'biol101')
insert into Research values
('RE0015', 44, 'chemistry', 3000.00, 'chem100')
insert into Research values
('RE0016', 55, 'astronomy', 1500.00, 'henn300')


--TA--
insert into TA values
(11110011, 11110012, 11110013, 11110014,11110015), ('TA0023','TA0023','TA0023','TA0023','TA002',), ('Working','Working','Not Working','Working','Not Working'), (21.13,21.13,0,21.13,0)
(11110011, 'TA0023', 'Working', 21.13)
insert into TA values
(11110012, 'TA0024', 'Working', 21.13)
insert into TA values
(11110013, 'TA0046', 'Not Working', 0)
insert into TA values
(11110014, 'TA0047', 'Working', 21.13)
insert into TA values
(11110015, 'TA0058', 'Not Working', 0)

--TAs Course--
insert into TAs_Course values
('TA0023', 'CPSC 322', 'Wed 8am-4pm')
insert into TAs_Course values
('TA0024', 'CPSC 310', 'Mon 7am - 11am')
insert into TAs_Course values
('TA0046', 'CPSC 311', 'Tue 8am-4pm')
insert into TAs_Course values
('TA0047', 'CPSC 304', 'Thur 9am - 5pm')
insert into TAs_Course values
('TA0058', 'CPSC 221', 'Fri  6pm - 10pm')


--TA Helps Research--
insert into TA_Helps_Research values
('TA0023', 'RE0012', 11)
insert into TA_Helps_Research values
('TA0024', 'RE0013', 22)
insert into TA_Helps_Research values
('TA0046', 'RE0014', 33)
insert into TA_Helps_Research values
('TA0047', 'RE0015', 44)
insert into TA_Helps_Research values
('TA0058', 'RE0016', 55)
