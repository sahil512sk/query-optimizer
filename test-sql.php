<?php

// Test file with SQL queries in different patterns

// Raw SQL in PDO style
$pdo->query("SELECT * FROM users");
$pdo->prepare("SELECT * FROM users WHERE id = 1");

// Raw SQL in mysqli style
mysqli_query($conn, "SELECT * FROM users ORDER BY created_at");

// Laravel DB facade
DB::select("SELECT * FROM users WHERE email LIKE '%gmail.com'");
DB::statement("DELETE FROM logs WHERE created_at < '2023-01-01'");

// More complex queries
$pdo->query("SELECT * FROM users u JOIN orders o ON u.id = o.user_id");
$pdo->prepare("SELECT * FROM products ORDER BY price DESC, name ASC");
