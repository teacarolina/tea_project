#Implementation
#DB name = ecommerce

#DROP SCHEMA IF EXISTS
DROP TABLE IF EXISTS CartItems; 
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
Price INT NOT NULL
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
Id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
TimeCreated DATETIME NOT NULL,
TimeUpdated DATETIME, 
Token VARCHAR(100) NOT NULL,  
UserId INT NOT NULL,
CONSTRAINT FK_Users FOREIGN KEY(UserId) REFERENCES Users(Id)
)
ENGINE=InnoDB;

CREATE TABLE CartItems(
Id INT NOT NULL AUTO_INCREMENT, 
CartId INT NOT NULL, 
ProductId INT NOT NULL,
Quantity INT NOT NULL, 
PRIMARY KEY(Id, CartId),
CONSTRAINT FK_Carts FOREIGN KEY(CartId) REFERENCES Carts(Id) ON DELETE CASCADE,
CONSTRAINT FK_Products FOREIGN KEY(ProductId) REFERENCES Products(Id)
)
ENGINE=InnoDB;