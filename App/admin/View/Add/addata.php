<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script>
      // Function to handle form submission via AJAX
      function addProduct(event) {
          event.preventDefault();
  
          // Get form data
          var formData = new FormData();
          formData.append("name", document.getElementById("name").value);
          formData.append("description", document.getElementById("description").value);
          formData.append("price", document.getElementById("price").value);
          formData.append("image", document.getElementById("image").files[0]); // Attach file
  
          // Create XMLHttpRequest object
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "../../Controller/add.php", true);
  
          // Handle response
          xhr.onreadystatechange = function () {
              if (xhr.readyState == 4 && xhr.status == 200) {
                  var response = JSON.parse(xhr.responseText);
                  alert(response.message);
                  if (response.status === "success") {
                      document.getElementById("addProductForm").reset();
                  }
              }
          };
  
          // Send form data
          xhr.send(formData);
      }
  </script>
  
</head>
<body>

    <h1>Add New Product</h1>
    <form id="addProductForm" onsubmit="addProduct(event)">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required><br><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <button type="submit">Add Product</button>
    </form>

</body>
</html>
