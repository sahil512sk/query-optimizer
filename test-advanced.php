<?php

// Advanced test file with various query patterns

// 1. Raw SQL with PDO
$pdo = new PDO("mysql:host=localhost;dbname=test", "user", "pass");

// Simple SELECT *
$pdo->query("SELECT * FROM users");

// SELECT with WHERE (should be good)
$pdo->query("SELECT id, name, email FROM users WHERE active = 1");

// ORDER BY without LIMIT
$pdo->query("SELECT * FROM posts ORDER BY created_at DESC");

// Leading wildcard in LIKE
$pdo->query("SELECT * FROM users WHERE email LIKE '%gmail.com'");

// Complex JOIN without WHERE
$pdo->query("SELECT u.*, p.title FROM users u JOIN posts p ON u.id = p.user_id JOIN comments c ON p.id = c.post_id");

// Subquery
$pdo->query("SELECT * FROM users WHERE id IN (SELECT user_id FROM posts WHERE status = 'published')");

// 2. mysqli patterns
$mysqli = new mysqli("localhost", "user", "pass", "test");

// Multiple JOINs
mysqli_query($mysqli, "SELECT u.*, p.*, c.* FROM users u JOIN posts p ON u.id = p.user_id JOIN comments c ON p.id = c.post_id JOIN likes l ON c.id = l.comment_id JOIN categories cat ON p.category_id = cat.id");

// 3. Laravel Query Builder patterns
use Illuminate\Support\Facades\DB;

// Query Builder with problematic patterns
DB::table('users')->select('*')->get();
DB::table('posts')->orderBy('created_at')->get();
DB::table('users')->where('email', 'LIKE', '%domain.com')->get();

// Complex Query Builder with JOINs
DB::table('users')
    ->select('users.*', 'posts.title', 'categories.name')
    ->join('posts', 'users.id', '=', 'posts.user_id')
    ->join('categories', 'posts.category_id', '=', 'categories.id')
    ->join('comments', 'posts.id', '=', 'comments.post_id')
    ->get();

// 4. Laravel Eloquent ORM patterns
use App\Models\Post;
use App\Models\User;
use PDO;


// Eloquent with problematic patterns
User::all(); // SELECT *
User::orderBy('created_at')->get(); // ORDER BY without LIMIT
User::where('email', 'LIKE', '%gmail.com')->get(); // Leading wildcard

// Eloquent with complex relationships
User::with(['posts.comments', 'posts.likes', 'profile'])->get();

// Eloquent with subquery-like behavior
User::whereIn('id', function($query) {
    $query->select('user_id')->from('posts')->where('status', 'published');
})->get();

// 5. Prepared statements with issues
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY price DESC, name ASC");
$stmt->execute();

$stmt2 = $pdo->prepare("SELECT * FROM orders o JOIN customers c ON o.customer_id = c.id JOIN products p ON o.product_id = p.id");
$stmt2->execute();

// 6. Database-specific patterns
DB::statement("DELETE FROM logs WHERE created_at < '2023-01-01'"); // Should be fine
DB::select("SELECT * FROM audit_logs ORDER BY timestamp DESC"); // ORDER BY without LIMIT

// 7. More complex scenarios
// Nested subqueries
$pdo->query("SELECT * FROM users WHERE id IN (SELECT user_id FROM posts WHERE id IN (SELECT post_id FROM comments WHERE created_at > '2023-01-01'))");

// Multiple ORDER BY without LIMIT
$pdo->query("SELECT * FROM products ORDER BY category_id, price DESC, name ASC");

// JOIN with potential performance issues
$pdo->query("SELECT a.*, b.*, c.*, d.* FROM table_a a JOIN table_b b ON a.id = b.a_id JOIN table_c c ON b.id = c.b_id JOIN table_d d ON c.id = d.c_id WHERE a.status = 'active'");

echo "Advanced test file with various query patterns loaded.\n";
