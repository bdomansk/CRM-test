<?php
# У каждого человека  есть некий баланс денег. Будем считать, что если кредитор дает займ, то деньги на его балансе
# уменьшаются, а у заемщика соответсвенно баланс увеличивается. Запись о займе появляется в таблице "records".
ini_set('display_errors','on');
require_once 'layout.php';
require_once 'Database.php';
# Создаем БД, таблицы people и records, и также триггер который меняет баланс кредитора и заемщика при добавлении
# новой записи в таблицу records.  В файле databaseStructureTask2.php находятся запросы на создание таблиц и триггера.
$model = new Database;
# Заполняем данными таблицу people
$model->execute("INSERT INTO `people` (`name`, `balance`)  VALUES  ('Bohdan', '500')");
$model->execute("INSERT INTO `people` (`name`, `balance`)  VALUES  ('Oleg', '100')");
$model->execute("INSERT INTO `people` (`name`, `balance`)  VALUES  ('Vova', '300')");
# Запросы на добавление записи о факте займа
$model->execute("INSERT INTO `records` (`lenderId`, `borrowerId`, `loanAmount`)  VALUES  ('2', '1', '63')");
$model->execute("INSERT INTO `records` (`lenderId`, `borrowerId`, `loanAmount`)  VALUES  ('2', '1', '25')");
$model->execute("INSERT INTO `records` (`lenderId`, `borrowerId`, `loanAmount`)  VALUES  ('1', '3', '200')");
$model->execute("INSERT INTO `records` (`lenderId`, `borrowerId`, `loanAmount`)  VALUES  ('1', '2', '50')");
# Узнаем сколько должен человек с id 1 должен человеку с id 2. Сумму долга счиатаем, как разность сумы долгов.
# Первая сумма - все займы человека 1 у человека 2. Вторая сумма - все займы человека 2 у человека 1.
$debt = $model->request("SELECT (SELECT  SUM(`loanAmount`)  FROM `records` WHERE `borrowerId` = 1 AND `lenderId` = 2) - 
(SELECT  SUM(`loanAmount`)  FROM `records` WHERE `borrowerId` = 2 AND `lenderId` = 1);");
echo $debt[0][0] . " - person 1 owes money to person 2 <br><br>";
# Узнаем баланс человека 1 при учете всех займов. Благодаря триггеру, уже не нужно ничего считать.
$balance = $model->request("SELECT `balance` FROM `people` WHERE id = 1");
echo $balance[0]['balance'] . " - balance for man with id 1 <br><br>";
# Узнаем кто и сколько должен человеку с id 1.
$records = $model->request("SELECT `borrowerId`, `loanAmount` FROM `records` WHERE `lenderId` = 1");
echo '<pre>' . print_r($records, true) . '</pre>';
# Удаляем таблицу people
$model->execute("DROP TABLE `people`");
# Удаляем всю БД
$model->execute("DROP DATABASE `loans`;");
# Просмотр всех выполненых запросов.
$model->viewCommands();
?>