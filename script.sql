DROP DATABASE IF EXISTS polaznik22_mvc;
CREATE DATABASE polaznik22_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use polaznik22_mvc;

create table post(
id int not null primary key auto_increment,
content text,
image_location text,
ts timestamp DEFAULT CURRENT_TIMESTAMP
)engine=InnoDB;

create table comments(
id int not null primary key auto_increment,
postid int,
content text,
ts timestamp DEFAULT CURRENT_TIMESTAMP
)engine=InnoDB;