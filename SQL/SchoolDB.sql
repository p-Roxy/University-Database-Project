create database SchoolDB_group4;
go

use SchoolDB_group4;
go


CREATE TABLE Student(
StudentID 		INTEGER,
StudentName 	CHAR(80),
UserName        CHAR(80),
Password        CHAR(80),
PRIMARY KEY (StudentID));

CREATE TABLE Pays(
StudentID 		INTEGER,
	CourseID 		CHAR(6),
	billNum 		INTEGER,
	PRIMARY KEY(StudentID, CourseID, billNum),
	FOREIGN KEY(Student ID) REFERENCES Student,
	FOREIGN KEY(CourseID, billNum) REFERENCES Fees);

CREATE TABLE  Course(
CourseID 		CHAR(10),
Subject 		CHAR(100),
Credits  		INTEGER,
PRIMARY KEY (CourseID));

CREATE TABLE  Fees(
billNum		INTEGER,
	CourseID		CHAR(6) NOT NULL,
	amountDue 		REAL,
	PRIMARY KEY(billNum, CourseID),
	FOREIGN KEY (CourseID) REFERENCES Course
ON DELETE CASCADE
ON UPDATE CASCADE);

CREATE TABLE  Schedules_Room(
roomID		INTEGER,
	CourseID		CHAR(6),
	daysofWeek		CHAR(6),
	Capacity		INTEGER,
	timeSlot		CHAR[9],
	Type			CHAR[20],
	PRIMARY KEY(roomID, courseID),
	FOREIGN KEY(courseID) REFERENCES Course)

CREATE TABLE  Takes(
StudentID		INTEGER,
CourseID		CHAR(6),
PRIMARY KEY(StudentID, CourseID),
FOREIGN KEY(StudentID) references Student,
FOREIGN KEY(CourseID) references Course);

CREATE TABLE Professor(
    profID		INTEGER,
	profName		CHAR(20),
	officeLocation	CHAR(20),
	Rating		INTEGER,
	Password		CHAR(20),
	PRIMARY KEY(profID)
UNIQUE KEY(officeLocation);

CREATE TABLE Research(
    rID			CHAR(6),
	profID		INTEGER NOT NULL,
	Thesis 		CHAR(400),
	Grant			REAL,
	labLocation		CHAR(20),
	PRIMARY KEY(rID, profID),
FOREIGN KEY(profID) REFERENCES Professor
ON DELETE CASCADE
ON UPDATE CASCADE);

CREATE TABLE TA(
StudentID		INTEGER,
	TAID			CHAR(6)),
	State 			CHAR(30),
	WageperHour	REAL,
	PRIMARY KEY(StudentID, TAID)
	FOREIGN KEY(StudentID) REFERENCES Student);


CREATE TABLE TAs_Course(
    TAID			CHAR(6),
	CourseID		CHAR(10),
	Hours			CHAR(50),
	PRIMARY KEY (CourseID),
	FOREIGN KEY (CourseID) REFERENCES Course,
	FOREIGN KEY (TAID) REFERENCES TA
ON DELETE NO ACTION
ON UPDATE CASCADE);

CREATE TABLE TA_Helps_Research(
TAID			CHAR(6),
	rID			CHAR(6),
	profID		INTEGER,
	PRIMARY KEY (rID, profID),
	FOREIGN KEY (rID, profID) REFERENCES Research,
	FOREIGN KEY (TAID) REFERENCES TA);


--Insert instances into tables from part 2--
--Student--
insert into Student values
(11110011, 'Keyla Hughes')
insert into Student values
(11110012, 'Abagail Petersen')
insert into Student values
(11110013, 'Grady Potts')
insert into Student values
(11110014, 'Kara Jackson')
insert into Student values
(11110015, 'Charlotte Vang')

--Pays--
insert into Pays values
(11110011, 'CPSC 322', 7681)
insert into Pays values
(11110012, 'CPSC 310', 7833)
insert into Pays values
(11110013, 'CPSC 311', 7688)
insert into Pays values
(11110014, 'CPSC 304', 7767)
insert into Pays values
(11110015, 'CPSC 221', 8765)

--Course--
insert into Course values
('CPSC 322', 'Artificial Intelligence', 3)
insert into Course values
('CPSC 310', 'Software Engineering', 3)
insert into Course values
('CPSC 311', 'Definition of Programming Languages', 2)
insert into Course values
('CPSC 304', 'Relational Databases', 3)
insert into Course values
('CPSC 221', 'Introduction to Algorithms', 3)

--Fees--
insert into Fees values
(7681, 'CPSC 322', 568.91)
insert into Fees values
(7833,
 'CPSC 310',
 620.51)
insert into Fees values
(7688,
 'CPSC 311',
 123.25)
insert into Fees values
(7767,
 'CPSC 304',
 456.72)
insert into Fees values
(8765,
 'CPSC 221',
 487.25)

--Schedules Rooms--
insert into Schedules_Room values
('HDP 110', 'CPSC 322', 'MWF', 200, '11am - 12pm', 'Lecture Hall')
insert into Schedules_Room values
('HDP 310', 'CPSC 310', 'TR', 120, '1pm-2:30pm', 'Lecture Hall')
insert into Schedules_Room values
('Swing 121', 'CPSC 311', 'TR', 190, '7pm-9pm', 'Lecture Hall' )
insert into Schedules_Room values
('PSB 1201', 'CPSC 304', 'MWF', 10, '8am-12pm', 'Studio')
insert into Schedules_Room values
('ICCS 330', 'CPSC 221', 'MWF', 30, '2pm - 3pm', 'Lab')


--Takes--
insert into Takes values
(11110011, 'CPSC 322')
insert into Takes values
(11110012, 'CPSC 310')
insert into Takes values
(11110013, 'CPSC 311')
insert into Takes values
(11110014, 'CPSC 304')
insert into Takes values
(11110015, 'CPSC 221')

--Professor--
insert into Professor values
(11, 'Allan', 'ICCS 241', 4, 'Allanb0mb')
insert into Professor values
(22, 'Bill', 'ICCS 320', 3, '1PnchM4n')
insert into Professor values
(33, 'Claire', 'ICCS 220', 7, 'Br4nchB0und')
insert into Professor values
(44, 'Eric', 'ICCS 110', 5, 'R0ckstr7')
insert into Professor values
(55, 'Fred', 'ICCS 310', 9, 'ImaLmb3rj4k')

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

