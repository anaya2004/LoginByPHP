<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$stmt = $con->prepare('SELECT password, email, status, timeStamp FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $status,$timestamp);
$stmt->fetch();
$stmt->close();

// Check if the status is 1 (active), otherwise redirect the user or display an error message
if ($status != 1) {
    // Redirect user to another page
    echo"User not authenticated!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Website Title</h1>
        <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content">
    <h2>Home Page</h2>
    <p>Welcome back, <?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!</p>

    <div>
        <p>Your account details are below:</p>
        <table>
            <tr>
                <td>Username:</td>
                <td><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><?=htmlspecialchars($password, ENT_QUOTES)?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?=htmlspecialchars($email, ENT_QUOTES)?></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td><?= $status == 1 ? 'Active' : 'Inactive' ?></td>
            </tr>
            <tr>
                <td>Time:</td>
                <td><?=htmlspecialchars($timestamp, ENT_QUOTES)?></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
