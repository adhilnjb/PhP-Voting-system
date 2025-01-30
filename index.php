<!DOCTYPE html>
<html>
<head>
  <title>Online Voting System</title>
  <link rel="stylesheet" href="css/style.css"> 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"> 
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Online Voting System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user/index.php">User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-12 hero-content" style="color: white;">
                    <h1 class="hero-title">Welcome to the Online Voting <br>System</h1>
                    <p class="hero-description">Your secure and convenient way to cast your vote.</p>
                    <a href="user/index.php" class="btn btn-success me-3 text" >Vote Now</a>  <a href="admin/login.php" class="btn btn-secondary">Admin Login</a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
    include 'includes/footer.php';
    ?>

</body>
</html>


