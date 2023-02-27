CREATE DATABASE literie3000;

USE literie3000;

CREATE table catalogue (
    id int PRIMARY KEY auto_increment,
    image varchar(255) not null,
    name varchar(100) not null,
    MattressName varchar(255),
    MattressSize varchar(50),
    price decimal(6,2),
    promo decimal(6,2)
);

INSERT INTO catalogue 
(image,name,MattressName,MattressSize,price,promo)
VALUES
("almunda.jpeg","Almunda","Matelas Transition","90x190",759.00,529.00),
("bjorn.jpeg","Bjorn","Matelas Stan","90x190",809.00,709.00),
("eclipse.jpeg","Eclipse","Matelas Teamasse","140x190",759.00,529.00),
("panama.jpeg","Panama","Matelas Coup de boule","160x200",1019.00,509.00)