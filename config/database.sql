#Implementation
#DB name = ecommerce

#DROP SCHEMA IF EXISTS
DROP TABLE IF EXISTS Carts;
DROP TABLE IF EXISTS Users; 
DROP TABLE IF EXISTS Products;


CREATE TABLE Users(
Id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
Username VARCHAR(50) NOT NULL,
Password VARCHAR(50) NOT NULL,
Email VARCHAR(50) NOT NULL, 
Role VARCHAR(10) DEFAULT "User"
)
ENGINE=InnoDB;


CREATE TABLE Products(
Id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
ProductName VARCHAR(30) NOT NULL,
Description VARCHAR(70) NOT NULL,
Price INT NOT NULL,
Image VARCHAR(100) 
)
ENGINE=InnoDB;

INSERT INTO Products (ProductName, Description, Price)
VALUES
("Zign", "Solglasögon - Rectangle, black", "149"),
("Zign UNISEX", "Solglasögon - Rectangle, mint", "129"),
("Vintage Supply", "Solglasögon - Chunky Rectangle, red", "189"),
("Urban Classics", "Solglasögon - Classics with chain, black", "199"),
("Pier One UNISEX", "Solglasögon - Round, silver/blue", "129");

CREATE TABLE Carts(
Id INT NOT NULL AUTO_INCREMENT,
ProductId INT NOT NULL,
Quantity INT NOT NULL,
CreateDate DATETIME NOT NULL, 
UserId INT NOT NULL,
PRIMARY KEY(Id, ProductId),
CONSTRAINT FK_Products FOREIGN KEY(ProductId) REFERENCES Products(Id),
CONSTRAINT FK_Users FOREIGN KEY(UserId) REFERENCES Users(Id)
)
ENGINE=InnoDB;