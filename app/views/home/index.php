<?php
$username   = $username   ?? '';
$loginTime  = $loginTime  ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="/public/css/style.css">
  <title>Dashboard</title>
</head>
<body>
  <h2>Hello, <?= htmlspecialchars($username) ?>!</h2>

  <?php if ($loginTime): ?>
    <p>You logged in at <?= date('F j, Y \a\t g:i A', strtotime($loginTime)) ?></p>
  <?php endif; ?>

  <a href="/Logout">Logout</a>
</body>
</html>
