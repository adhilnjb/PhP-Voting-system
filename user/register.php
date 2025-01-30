<?php
include '../includes/header.php';
include '../includes/db.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Input validation (improved)
    $errors = []; // Array to store error messages

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) { // Example: Minimum password length
        $errors[] = "Password must be at least 6 characters long.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) { // Proceed only if there are no validation errors
        try {
            // Check if email already exists (using prepared statement)
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $errors[] = "Email already exists."; // Add to errors array
            } else {
                // Hash the password (using password_hash())
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into the database (using prepared statement)
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->execute();

                $success = "Registration successful. You can now login.";
                // Optionally redirect to login page after successful registration
                header("Location: index.php"); // Redirect to the login page
                exit;
            }

        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage(); // Add to errors array
        }
    }
}

?>
<link rel="stylesheet" href="css/style.css"> 
<div class="hero-section d-flex justify-content-center align-items-center mt-1" style="min-height: 80vh;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow user-registration-card">
                    <div class="card-body">
                        <h2 class="card-title text-center">User Registration</h2>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>


                        <form method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="register" class="btn btn-primary">Register</button>
                            </div>
                           <div class="mt-3 text-center">
                                Already have an account? <a href="./index.php">Login</a><br>
                                <a href="../index.php" class="btn btn-info btn-sm mt-2">Back to Home</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>