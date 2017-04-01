DROP TABLE TA_Helps_Research;
DROP TABLE TAs_Course;
DROP TABLE TA;
DROP TABLE Takes;
DROP TABLE Research;
DROP TABLE Schedules_Room;
DROP TABLE Pays;
DROP TABLE Fees;
DROP TABLE Professor;
DROP TABLE Course;
DROP TABLE Student;

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
	profID        	INTEGER not null,
	PRIMARY KEY (CourseID),
	FOREIGN key (profid) references professor);

CREATE TABLE Professor(
    profID		INTEGER,
	profName		CHAR(20),
	officeLocation	CHAR(20),
	Rating		INTEGER,
UserName        CHAR(80),
	Password		CHAR(20),
	PRIMARY KEY(profID));
    
CREATE TABLE Fees(
	billNum		INTEGER,
	CourseID		CHAR(10) NOT NULL,
	amountDue 		REAL,
	PRIMARY KEY(billNum),
	FOREIGN KEY (CourseID) REFERENCES Course
	ON DELETE CASCADE);

CREATE TABLE Pays(
	StudentID 		INTEGER,
	billNum 		INTEGER,
	amountPaid			REAL,
	PRIMARY KEY(StudentID, billNum),
	FOREIGN KEY(StudentID) REFERENCES Student,
	FOREIGN KEY(billNum) REFERENCES Fees(billNum));

CREATE TABLE  Schedules_Room(
	roomID			CHAR(10),
	CourseID		CHAR(10),
	daysofWeek		CHAR(6),
	Capacity		INTEGER,
	timeSlot		CHAR(20),
	roomtype		CHAR(80),
	PRIMARY KEY(roomID, courseID),
	FOREIGN KEY(CourseID) REFERENCES Course);

CREATE TABLE Research(
    rID			CHAR(6),
	profID		INTEGER NOT NULL,
	Thesis 		CHAR(400),
	reGrant			REAL,
	labLocation		CHAR(20),
	PRIMARY KEY(rID, profID),
FOREIGN KEY(profID) REFERENCES Professor
ON DELETE CASCADE);

CREATE TABLE  Takes(
StudentID		INTEGER,
CourseID		CHAR(10),
PRIMARY KEY(StudentID, CourseID),
FOREIGN KEY(StudentID) references Student,
FOREIGN KEY(CourseID) references Course);

CREATE TABLE TA(
	StudentID	INTEGER,
	TAID		CHAR(6),
	taState 	CHAR(30),
	WageperHour	REAL,
	PRIMARY KEY(TAID),
	FOREIGN KEY(StudentID) REFERENCES Student);


CREATE TABLE TAs_Course(
	TAID		CHAR(6),
	CourseID	CHAR(10),
	taHours		CHAR(50),
	PRIMARY KEY (CourseID),
	FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
	FOREIGN KEY (TAID) references TA(TAID));
    
CREATE TABLE TA_Helps_Research(
TAID			CHAR(6),
	rID			CHAR(6),
	profID		INTEGER,
	PRIMARY KEY (rID, profID),
	FOREIGN KEY (rID, profID) REFERENCES Research,
	FOREIGN KEY (TAID) references TA);

-- Insert instances into tables from part 2--
-- Student--
insert into Student values
(000, 'test', 'test', 'test');
insert into Student values
(11110010, 'Bill Lee', 'blee', 'b33skn33s');
insert into Student values
(11110011, 'Keyla Hughes', 'khughes', 'c4ts4lyfe');
insert into Student values
(11110012, 'Abagail Petersen','aPetersen', 'l0l0l0l0l');
insert into Student values
(11110013, 'Grady Potts', 'gPotts', 't1m3turn3r');
insert into Student values
(11110014, 'Kara Jackson', 'kJackson', 'fg43g2g1');
insert into Student values
(11110015, 'Charlotte Vang', 'cVang', 'f9ewjg09');

-- Course--
insert into Course values
('CPSC 322', 'Artificial Intelligence', 3, 11);
insert into Course values
('CPSC 310', 'Software Engineering', 4, 11);
insert into Course values
('CPSC 311', 'Definition of Programming Languages', 2, 22);
insert into Course values
('CPSC 304', 'Relational Databases', 3, 33);
insert into Course values
('CPSC 221', 'Introduction to Algorithms', 4, 55);

-- Fees--
insert into Fees values
(7681, 'CPSC 322', 568.91);
insert into Fees values
(7833,
 'CPSC 310',
 620.51);
insert into Fees values
(7688,
 'CPSC 311',
 123.25);
insert into Fees values
(7767,
 'CPSC 304',
 456.72);
insert into Fees values
(8765,
 'CPSC 221',
 487.25);

-- Pays--
insert into Pays values
(11110011, 7681, 0);
insert into Pays values
(11110012, 7833, 0);
insert into Pays values
(11110013, 7688, 0);
insert into Pays values
(11110014, 7767, 0);
insert into Pays values
(11110015, 8765, 0);

-- Schedules Rooms--
insert into Schedules_Room values
('HDP110', 'CPSC 322', 'MWF', 200, '11am - 12pm', 'Lecture Hall');
insert into Schedules_Room values
('HDP310', 'CPSC 310', 'TR', 120, '1pm-2:30pm', 'Lecture Hall');
insert into Schedules_Room values
('Swing121', 'CPSC 311', 'TR', 190, '7pm-9pm', 'Lecture Hall' );
insert into Schedules_Room values
('PSB1201', 'CPSC 304', 'MWF', 10, '8am-12pm', 'Studio');
insert into Schedules_Room values
('ICCS330', 'CPSC 221', 'MWF', 30, '2pm - 3pm', 'Lab');


-- Takes--
insert into Takes values
(11110011, 'CPSC 322');
insert into Takes values
(11110012, 'CPSC 310');
insert into Takes values
(11110013, 'CPSC 311');
insert into Takes values
(11110014, 'CPSC 304');
insert into Takes values
(11110015, 'CPSC 221');

-- Professor--
insert into Professor values
(11, 'Allan', 'ICCS 241', 4, 'Allan', 'Allanb0mb');
insert into Professor values
(22, 'Bill', 'ICCS 320', 3, 'Bill' , '1PnchM4n');
insert into Professor values
(33, 'Claire', 'ICCS 220', 7, 'Claire', 'Br4nchB0und');
insert into Professor values
(44, 'Eric', 'ICCS 110', 5, 'Eric', 'R0ckstr7');
insert into Professor values
(55, 'Fred', 'ICCS 310', 9, 'Fred', 'ImaLmb3rj4k');

-- Research--
insert into Research values
('RE0012', 11, 'Math', 5000.00, 'buch101');
insert into Research values
('RE0013', 22, 'physics', 10000.00, 'henn201');
insert into Research values
('RE0014', 33, 'biology', 8000.00, 'biol101');
insert into Research values
('RE0015', 44, 'chemistry', 3000.00, 'chem100');
insert into Research values
('RE0016', 55, 'astronomy', 1500.00, 'henn300');


-- TA--
insert into TA values
(000, 'test', 'working', 21.13);
insert into TA values
(11110011, 'TA0023', 'Working', 21.13);
insert into TA values
(11110012, 'TA0024', 'Working', 21.13);
insert into TA values
(11110013, 'TA0046', 'Not Working', 0);
insert into TA values
(11110014, 'TA0047', 'Working', 21.13);
insert into TA values
(11110015, 'TA0058', 'Not Working', 0);

-- TAs Course--
insert into TAs_Course values
('TA0023', 'CPSC 322', 'Wed 8am-4pm');
insert into TAs_Course values
('TA0024', 'CPSC 310', 'Mon 7am - 11am');
insert into TAs_Course values
('TA0046', 'CPSC 311', 'Tue 8am-4pm');
insert into TAs_Course values
('TA0047', 'CPSC 304', 'Thur 9am - 5pm');
insert into TAs_Course values
('TA0058', 'CPSC 221', 'Fri  6pm - 10pm');


-- TA Helps Research--
insert into TA_Helps_Research values
('TA0023', 'RE0012', 11);
insert into TA_Helps_Research values
('TA0024', 'RE0013', 22);
insert into TA_Helps_Research values
('TA0046', 'RE0014', 33);
insert into TA_Helps_Research values
('TA0047', 'RE0015', 44);
insert into TA_Helps_Research values
('TA0058', 'RE0016', 55);