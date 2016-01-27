create database workspace;
use workspace;
create table images( id int primary key auto increment, file varchar(255) unique, notes text, category int);
create table categories (id int primary key auto increment, name varchar(255) unique);