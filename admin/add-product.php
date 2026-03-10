<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit();
}
require_once '../config/db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $author      = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $category    = trim($_POST['category'] ?? '');
    $image_name  = '';

    if (empty($title) || empty($description) || $price <= 0 || empty($category)) {
        $error = "Please fill all required fields.";
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $allowed    = ['jpg', 'jpeg', 'png', 'webp'];
            $ext        = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, PNG, WEBP images allowed.";
            } else {
                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $upload_dir = '../assets/images/';
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                    $error = "Image upload failed.";
                    $image_name = '';
                }
            }
        }

        if (!$error) {
            $stmt = $conn->prepare(
                "INSERT INTO products (title, author, description, price, category, image) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssdss", $title, $author, $description, $price, $category, $image_name);
            if ($stmt->execute()) {
                $success = "Product \"$title\" added successfully!";
            } else {
                $error = "Failed to add product. Try again.";
            }
            $stmt->close();
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<main class="container" style="padding:40px 20px; max-width:700px;">
    <div style="display:flex; align-items:center; gap:15px; margin-bottom:25px;">
        <a href="dashboard.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i></a>
        <h2 style="color:var(--primary);">Add New Product</h2>
    </div>

    <?php if ($error): ?>
        <div style="color:red; background:#fef2f2; border:1px solid #fecaca; padding:12px; border-radius:8px; margin-bottom:20px;">
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div style="color:green; background:#f0fdf4; border:1px solid #bbf7d0; padding:12px; border-radius:8px; margin-bottom:20px;">
            <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <div class="card" style="padding:30px;">
        <form method="POST" action="add-product.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Title *</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Theory of Computation" required>
            </div>
            <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" class="form-control" placeholder="e.g. Vivek Kulkarni">
            </div>
            <div class="form-group">
                <label>Category *</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Textbook">Textbook</option>
                    <option value="Guide">Decode / Guide</option>
                    <option value="Notes">Notes</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price (₹) *</label>
                <input type="number" name="price" class="form-control" placeholder="450" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" class="form-control" rows="4"
                          placeholder="Describe the book..." required></textarea>
            </div>
            <div class="form-group">
                <label>Product Image (JPG/PNG/WEBP)</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:13px; font-size:1rem;">
                <i class="fa-solid fa-plus"></i> Add Product
            </button>
        </form>
    </div>
</main>

<?php include '../includes/footer.php'; ?>