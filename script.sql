DROP DATABASE IF EXISTS social_network;
CREATE DATABASE social_network CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use social_network;

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

insert into post (content) values ('Evo danas pada kiša opet :('), ('Mokar sam ko pas...');