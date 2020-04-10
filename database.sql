CREATE DATABASE facebook;
USE facebook;

CREATE TABLE persons
(
	id 			INT AUTO_INCREMENT,
    firstName 	VARCHAR(30) NOT NULL,
    lastName 	VARCHAR(30) NOT NULL,
	fullName 	VARCHAR(60) AS (CONCAT(firstName, " ", lastName)),
    email 		VARCHAR(100) UNIQUE NOT NULL,
    password 	VARCHAR(50) NOT NULL,
    birthday	DATE NOT NULL,
    gender 		CHAR(1),
    CONSTRAINT pk_persons PRIMARY KEY (id),
    CONSTRAINT ck_persons_gender CHECK(gender = 'M' OR gender = 'F')
);

CREATE TABLE posts
(
	id INT AUTO_INCREMENT,
    author INT,
    creationTime DATETIME DEFAULT NOW(),
    text TEXT,
    likes INT DEFAULT 0,
    CONSTRAINT pk_posts PRIMARY KEY (id),
    CONSTRAINT fk_postsAuthor FOREIGN KEY (author) REFERENCES persons (id)
);

CREATE TABLE postComments 
(
	id INT AUTO_INCREMENT,
    author INT,
    creationTime DATETIME DEFAULT NOW(),
    text VARCHAR(300),
    likes INT DEFAULT 0,
    post INT,
    CONSTRAINT pk_postComments PRIMARY KEY (id),
    CONSTRAINT fg_postComments_author FOREIGN KEY (author) REFERENCES persons (id),
    CONSTRAINT fg_postComments_post FOREIGN KEY (post) REFERENCES posts (id)
);

CREATE TABLE commentComments 
(
	id INT AUTO_INCREMENT,
    author INT,
    creationTime DATETIME DEFAULT NOW(),
    text VARCHAR(300),
    likes INT DEFAULT 0,
    comment INT,
    CONSTRAINT pk_commentComments PRIMARY KEY (id),
    CONSTRAINT fg_commentComments_author FOREIGN KEY (author) REFERENCES persons (id),
    CONSTRAINT fg_commentComments_comment FOREIGN KEY (comment) REFERENCES postComments (id)
);

INSERT INTO persons (firstName, lastName, email, password, birthday, gender)
VALUES ('João', 'Fé', 'jf@sapo.pt', '123', '2000-10-25', 'M');