<?php
include '../../admin/Controller/db.php'; // Ensure this file connects to your database

// Query to retrieve all articles
$sql = "SELECT id, name_plant, description_plant, image FROM article";
$sql2 = "SELECT * FROM hero_section WHERE id = 1";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);

// If there's data for the hero section
if ($result2->num_rows > 0) {
    $hero = $result2->fetch_assoc();
} else {
    echo "Data not found for hero section.";
    exit;
}

// Edit product and hero section logic
if (isset($_GET['edit'])) {
    $idToEdit = $_GET['edit'];

    // Fetch the specific article for editing
    $editQuery = "SELECT * FROM article WHERE id = $idToEdit";
    $editResult = $conn->query($editQuery);
    $editProduct = $editResult->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
        // Update product data
        $name = $_POST['name_plant'];
        $description = $_POST['description_plant'];
        $image = $_POST['image']; // Ensure image input is handled properly

        // Update the article in the database
        $updateQuery = "UPDATE article SET name_plant = ?, description_plant = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $name, $description, $image, $idToEdit);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
        } else {
            echo "Error updating product!";
        }
    }
}

// Handle updating the hero section
if (isset($_POST['update_hero'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tagline = $_POST['tagline'];

    $updateHeroQuery = "UPDATE hero_section SET title = ?, description = ?, tagline = ? WHERE id = 1";
    $stmt = $conn->prepare($updateHeroQuery);
    $stmt->bind_param("sss", $title, $description, $tagline);

    if ($stmt->execute()) {
        // echo "Hero section updated successfully!";
        header("Location: dashboard.php");
    } else {
        echo "Error updating hero section!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora Trade Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Style/style-menuadmin.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .profile img {
            width: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
        }

        .sidebar ul li a.active,
        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        .content h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .edit-hero-section,
        .edit-form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #ecf0f1;
            border-radius: 5px;
        }

        .edit-hero-section h2,
        .edit-form h2 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .product img {
            max-width: 100%;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .product h2 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .product p {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .product a {
            display: inline-block;
            padding: 5px 10px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .product a:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="profile">
            <img src="../../../../../Assets/img/logo/logo.svg" alt="Profile Picture">
            <h3>TanamanSae</h3>
        </div>
        <ul class="menu">
            <li><a id="top-bar" href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
            <li><a id="top-bar" href="products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">Products</a></li>
            <li><a id="top-bar" href="../../Users/View/shop.php" class="<?= basename($_SERVER['PHP_SELF']) == '../../Users/View/shop.php' ? 'active' : '' ?>">Keluar</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>WELCOME</h1>
        <main>
            <!-- Hero Section Edit Form -->
            <div class="edit-hero-section">
                <h2>Edit Hero Section</h2>
                <form action="dashboard.php" method="POST">
                    <label for="title">Hero Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($hero['title']); ?>" required><br>

                    <label for="description">Hero Description:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($hero['description']); ?></textarea><br>

                    <label for="tagline">Hero Tagline:</label>
                    <input type="text" id="tagline" name="tagline" value="<?php echo htmlspecialchars($hero['tagline']); ?>" required><br>

                    <button type="submit" name="update_hero">Update Hero Section</button>
                </form>
            </div>

            <!-- Form to Edit Product -->
            <?php if (isset($_GET['edit']) && $editProduct): ?>
                <div class="edit-form">
                    <h2>Edit Product: <?php echo htmlspecialchars($editProduct['name_plant']); ?></h2>
                    <form action="dashboard.php?edit=<?php echo $editProduct['id']; ?>" method="POST">
                        <label for="name_plant">Product Name:</label>
                        <input type="text" id="name_plant" name="name_plant" value="<?php echo htmlspecialchars($editProduct['name_plant']); ?>" required><br>

                        <label for="description_plant">Description:</label>
                        <textarea id="description_plant" name="description_plant" required><?php echo htmlspecialchars($editProduct['description_plant']); ?></textarea><br>

                        <label for="image">Image URL:</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($editProduct['image']); ?>"><br>

                        <button type="submit" name="update_product">Update Product</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Select a product to edit.</p>
            <?php endif; ?>

            <!-- Display Products -->
            <div class="container">
                <?php if ($result->num_rows > 0): ?>
                    <section class="products">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <article class="product">
                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name_plant']) ?>">
                                <h2><?= htmlspecialchars($row['name_plant']) ?></h2>
                                <p><?= htmlspecialchars($row['description_plant']) ?></p>
                                <a href="dashboard.php?edit=<?= $row['id']; ?>">Edit</a>
                            </article>
                        <?php endwhile; ?>
                    </section>
                <?php else: ?>
                    <p>No products available. Check back later!</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

</body>

</html>


<?php $conn->close(); ?>
