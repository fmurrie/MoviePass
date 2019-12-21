/*
Database - MoviePass
*/

create database moviepass;
use moviepass;

create table movie_theaters
(id int auto_increment,
name varchar(40),
state tinyint(1),
address varchar(40),
opening_time varchar(10),
closing_time varchar(10),
constraint pk_movie_theaters primary key(id),
constraint unq_movie_theaters unique(name));

create table auditoriums
(id int auto_increment,
id_movietheater int,
name varchar(40),
state tinyint(1),
capacity int,
ticket_price int,
constraint pk_auditoriums primary key(id),
constraint fk_auditoriums_movie_theaters foreign key(id_movietheater) references movie_theaters(id),
constraint unq_auditoriums unique(name, id_movietheater));           
                                      
create table user_roles
(id int auto_increment,
description varchar(40),
constraint pk_user_roles primary key(id),
constraint unq_user_roles unique(description));

create table users
(id int auto_increment,
email varchar(40),
password varchar(100),
first_name varchar(40),
last_name varchar(40),
photo mediumblob,
id_user_role int,
id_facebook varchar(100),
constraint pk_users primary key(id),
constraint unq_users unique(email),
constraint fk_users_user_roles foreign key(id_user_role) references user_roles(id));

create table genres
(id_genre int auto_increment,
id_api_genre int,
name_genre varchar(40),
constraint pk_genres primary key(id_genre),
constraint unq_genres unique(id_api_genre));

create table movies
(id_movie int auto_increment,
id_api_movie int,
name_movie varchar(40),
synopsis varchar(700),
poster varchar(100),
background varchar(100),
score float,
uploading_date varchar(15),
constraint pk_movies primary key(id_movie),
constraint unq_movies unique(id_api_movie));

create table genres_per_movie
(id_movie int,
id_genre int,
constraint pk_genres_per_movie primary key(id_movie, id_genre),
constraint fk_genres_per_movie_movies foreign key(id_movie) references movies(id_movie),
constraint fk_genres_per_movie_genres foreign key(id_genre) references genres(id_genre));

create table showtimes
(id int auto_increment,
date varchar(15),
opening_time varchar(10),
closing_time varchar(10),
tickets_sold int,
total_tickets int,
ticket_price int,
id_auditorium int,
id_movie int,
constraint pk_showtimes primary key(id),
constraint fk_showtimes_auditoriums foreign key(id_auditorium) references auditoriums(id),
constraint fk_showtimes_movies foreign key(id_movie) references movies(id_movie));
                
create table payments
(id_payment int auto_increment,
total int,
id_purchase int,
constraint pk_payments primary key(id_payment));
                                   
create table purchases
(id_purchase int auto_increment,
purchased_tickets int,
date_purchase varchar(15),
discount int,
qr mediumblob,
id_user int,
id_payment int,
constraint pk_purchases primary key(id_purchase),
constraint fk_purchases_users foreign key(id_user) references users(id),
constraint fk_purchases_payments foreign key(id_payment) references payments(id_payment));
                     
alter table payments add constraint fk_payments_purchases foreign key(id_purchase) references purchases(id_purchase);

create table tickets
(id_ticket int auto_increment,
number int,
id_purchase int,
id_showtime int,
constraint pk_tickets primary key(id_ticket),
constraint fk_tickets_showtimes foreign key(id_showtime) references showtimes(id),
constraint fk_tickets_purchases foreign key(id_purchase) references purchases(id_purchase));

create table users_online(id_user int,ip_address varchar(100),country varchar(100),
region varchar(100),city varchar(100),last_time datetime default now(),
user_agent varchar(150),
constraint pk_users_online primary key(id_user),
constraint fk_users_online foreign key(id_user) references users(id) on update cascade on delete cascade);

insert into user_roles (description) values ('user'),('admin');
insert into users (email, password, id_user_role) values ('admin@admin', '$2y$12$q.zS7nRxhM.PJvoc/HjptOEqy6o5ba.H34thBnw7spPjCPOeP9y7u', 2);