CREATE DATABASE literie3000;

USE literie3000;

CREATE table catalogue (
    id int PRIMARY KEY auto_increment,
    image varchar(255) not null,
    name varchar(100) not null,
    decription varchar(255),
    price decimal(6,2)
);

INSERT INTO catalogue 
(image,name,decription,price)
VALUES
("almunda.jpeg","Almunda","Matelas Transition 90x190",759.00),
("bjorn.jpeg","Bjorn","Matelas Stan 90x190",809.00),
("eclipe.jpeg","Eclipse","Matelas Teamasse 140x190",759.00),
("panama.jpeg","Panama","Matelas Coup de boule 160x200",1019.00)