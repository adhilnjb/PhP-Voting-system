<?php
include '../includes/header.php';
include '../includes/db.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_candidate'])) {
    // Check if name and description are set
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    // Prevent inserting empty values
    if (empty($name)) {
        echo "<p style='color: red;'>Candidate name is required!</p>";
    } else {
        $photo = null;

        // File upload handling
        if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "../images/";
            $photo_name = time() . "_" . basename($_FILES["photo"]["name"]);
            $photo = $target_dir . $photo_name;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
        }

        // Insert into the database
        $stmt = $pdo->prepare("INSERT INTO candidates (name, description, photo) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $description, $photo])) {
            header("Location: manage_candidates.php"); // Redirect to prevent duplicate submission
            exit;
        } else {
            echo "<p style='color: red;'>Error adding candidate.</p>";
        }
    }
}
?>

<link rel="stylesheet" href="../css/style.css"> 
<div class="hero-section d-flex justify-content-center align-items-center">
    <div class="container" style="color: white;">
        <div class="row">
            <div class="col-md-12">
                <h2>Manage Candidates</h2>
                <?php 
                // ... (Display success/error messages) ...
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h3>Add Candidate</h3>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>
                    <button type="submit" name="add_candidate" class="btn btn-primary">Add Candidate</button>
                    <a href="dashboard.php" class="btn btn-info">Back</a>
                </form>
            </div>

         <!-- Candidate List -->
         <div class="col-md-6">
                <h3 style="color: black;">Candidate List</h3>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search Candidates" aria-label="Search Candidates">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="candidateList">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM candidates ORDER BY id DESC");
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($results) {
                            foreach ($results as $result) {
                                ?>
                                <tr id="row_<?php echo $result['id']; ?>"> 
                                    <td>
                                        <?php if ($result['photo']) { ?>
                                            <img src="<?php echo $result['photo']; ?>" alt="Candidate Photo" style="width: 50px; height: 50px; border-radius: 50%;">
                                        <?php } else { ?>
                                            No Image
                                        <?php } ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($result['name']); ?></td>
                                    <td><?php echo htmlspecialchars($result['description']); ?></td>
                                    <td>
                                        <a href="./update_candidate.php?id=<?php echo $result['id']; ?>" class="btn btn-sm btn-primary">Update</a> 
                                        <a href="./delete_candidate.php?delete=<?php echo $result['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this candidate?');">Delete</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center; color: red;'>No candidates found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery for Search -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#searchButton").click(function() {
            var searchTerm = $("#searchInput").val().toLowerCase();
            $("#candidateList tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchTerm) > -1);
            });
        });
    });
</script>

<?php
include '../includes/footer.php';
?>
