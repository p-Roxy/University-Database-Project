create database SchoolDB_group4;
go

use SchoolDB_group4;
go


CREATE TABLE Student(
StudentID 		INTEGER,
StudentName 	CHAR(80),
PRIMARY KEY (StudentID));

CREATE TABLE Pays(
StudentID 		INTEGER,
	CourseID 		INTEGER,
	billNum 		INTEGER,
	PRIMARY KEY(StudentID, CourseID, billNum),
	FOREIGN KEY(Student ID) REFERENCES Student,
	FOREIGN KEY(CourseID, billNum) REFERENCES Fees);

CREATE TABLE  Course(
CourseID 		INTEGER,
Credits  		INTEGER,
Subject 		CHAR(100),
PRIMARY KEY (CourseID));

CREATE TABLE  Fees(
billNum		INTEGER,
	CourseID		INTEGER NOT NULL,
	amountDue 		REAL,
	PRIMARY KEY(billNum, CourseID),
	FOREIGN KEY (CourseID) REFERENCES Course
ON DELETE CASCADE
ON UPDATE CASCADE);

CREATE TABLE  Schedules_Room(
roomID		INTEGER,
	CourseID		INTEGER,
	daysofWeek		DATE,
	Capacity		INTEGER,
	timeSlot		CHAR[9],
	Type			CHAR[20],
	PRIMARY KEY(roomID, courseID),
	FOREIGN KEY(courseID) REFERENCES Course)

CREATE TABLE  Takes(
StudentID		INTEGER,
CourseID		INTEGER,
PRIMARY KEY(StudentID, CourseID),
FOREIGN KEY(StudentID) references Student,
FOREIGN KEY(CourseID) references Course);

CREATE TABLE Professor(
profID		INTEGER,
	Password		CHAR(20),
	profName		CHAR(20),
	officeLocation	CHAR(20),
	Rating		INTEGER,
	PRIMARY KEY(profID)
UNIQUE KEY(officeLocation);

CREATE TABLE Research(
rID			INTEGER,
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
	TAID			INTEGER,
	State 			CHAR(30),
	WageperHour	REAL,
	PRIMARY KEY(StudentID, TAID)
	FOREIGN KEY(StudentID) REFERENCES Student);


CREATE TABLE TAs_Course(
TAID			INTEGER,
	CourseID		INTEGER,
	Hours			INTEGER,
	PRIMARY KEY (CourseID),
	FOREIGN KEY (CourseID) REFERENCES Course,
	FOREIGN KEY (TAID) REFERENCES TA
ON DELETE NO ACTION
ON UPDATE CASCADE);

CREATE TABLE TA_Helps_Research(
TAID			INTEGER,
	rID			INTEGER,
	profID		INTEGER,
	PRIMARY KEY (rID, profID),
	FOREIGN KEY (rID, profID) REFERENCES Research,
	FOREIGN KEY (TAID) REFERENCES TA);


--Insert instances into tables from part 2--
--Student--
insert into Student values
()
insert into Student values
()
insert into Student values
()
insert into Student values
()
insert into Student values
()

--Pays--
insert into Student values
()
insert into Student values
()
insert into Student values
()
insert into Student values
()
insert into Student values
()

--Course--
insert into Student values
()
insert into Student values
()
insert into Student values
()
insert into Student values
()
insert into Student values
()
