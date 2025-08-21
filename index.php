<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

require 'api/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Nordlich Employee Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
  <?php require 'includes/sidebar.php'; ?>
  <?php require 'includes/navbar.php'; ?>
  <main class="main-content">
    <?php require 'page/dashboard-view.php'; ?>
    <?php require 'page/attendance-view.php'; ?>
    <?php require 'page/leave-view.php'; ?>
  </main>
  <?php require 'includes/modals.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/app.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="assets/js/employee.js"></script>
  <script src="assets/js/leave.js"></script>
</body>

</html>