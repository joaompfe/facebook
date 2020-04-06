CREATE DATABASE facebook;
USE facebook;

CREATE TABLE persons
(
	id 			INT AUTO_INCREMENT,
	name 		VARCHAR(60) NOT NULL,
    email 		VARCHAR(100) NOT NULL,
    password 	VARCHAR(50) NOT NULL,
    CONSTRAINT pk_persons PRIMARY KEY (id)
);

CREATE TABLE posts
(
	id INT AUTO_INCREMENT,
    author INT,
    creation_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    text TEXT,
    likes INT DEFAULT 0,
    CONSTRAINT pk_posts PRIMARY KEY (id),
    CONSTRAINT fk_posts_author FOREIGN KEY (author) REFERENCES persons (id)
);