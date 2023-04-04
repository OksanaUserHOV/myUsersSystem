<?php 

  $user = 'root';
  $password = 'root';
  $db = 'my_users_system';
  $host = 'localhost';

  
  $dsn = 'mysql:host='. $host . ';dbname=' . $db; 
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);