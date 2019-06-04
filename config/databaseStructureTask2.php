<?php
return [
    "CREATE TABLE IF NOT EXISTS  people(
    `id` INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(32) NOT NULL,
    `balance` INT(10) NOT NULL
    );",
    "CREATE TABLE IF NOT EXISTS   records(
    `id` INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `lenderId` INT(8) NOT NULL,
    `borrowerId` INT(8) NOT NULL,
    `loanAmount` INT(10) NOT NULL
    );",
    "CREATE TRIGGER `loansRecord`
    AFTER INSERT ON `records`
    FOR EACH ROW BEGIN
    UPDATE people p SET p.balance =  p.balance - NEW.loanAmount WHERE id = NEW.lenderId;
    UPDATE people p SET p.balance =  p.balance + NEW.loanAmount WHERE id = NEW.borrowerId;
    END;"
];
?>