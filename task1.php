<?php
ini_set('display_errors','on');
require_once 'sort.php';
require_once 'layout.php';
$object = new taskClass;
$object->sort();
echo "<b>After sorting array looks like:</b><br>";
$object->viewResult();
?>