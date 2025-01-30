
<?php
include '../includes/header.php';
include '../includes/db.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $candidate_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = :id");
        $stmt->bindParam(':id', $candidate_id);
        $stmt->execute();

        $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error fetching candidate data: " . $e->getMessage();
    }
}

if (isset($_POST['update_candidate'])) {
    $candidate_id = $_POST['candidate_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Handle photo upload (optional)
    $target_dir = "../images/";
    $target_file = $target_dir . uniqid() . "_" . basename($_FILES["photo"]["name"]); 
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if (isset($_FILES["photo"]["tmp_name"]) && !empty($_FILES["photo"]["tmp_name"])) {
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // Display error message
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Update candidate with new photo
                try {
                    $stmt = $pdo->prepare("UPDATE candidates SET name = :name, description = :description, photo = :photo WHERE id = :id");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':photo', $target_file);
                    $stmt->bindParam(':id', $candidate_id);
                    $stmt->execute();

                    // Delete old photo (if it exists)
                    if (!empty($candidate['photo'])) {
                        if (file_exists($candidate['photo'])) {
                            unlink($candidate['photo']); 
                        }
                    }

                    $success_message = "Candidate updated successfully!";
                } catch (PDOException $e) {
                    $error = "Error updating candidate: " . $e->getMessage();
                }
            } else {
                $error = "Error uploading file.";
            }
        }
    } else {
        // Update candidate without changing photo
        try {
            $stmt = $pdo->prepare("UPDATE candidates SET name = :name, description = :description WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $candidate_id);
            $stmt->execute();

            $success_message = "Candidate updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating candidate: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Candidate</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="hero-section d-flex justify-content-center align-items-center mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Update Candidate</h2>
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="candidate_id" value="<?php echo isset($candidate) ? $candidate['id'] : ''; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Candidate Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($candidate) ? $candidate['name'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo isset($candidate) ? $candidate['description'] : ''; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo">
                            </div>
                            <button type="submit" name="update_candidate" class="btn btn-primary">Update Candidate</button>
                            <a href="manage_candidates.php" class="btn btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
