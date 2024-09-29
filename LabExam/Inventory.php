<?php
session_start();

// Initialize inventory if not set
if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = [];
}

// Add new item logic
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_name']) && isset($_POST['quantity'])) {
    $item_name = trim($_POST['item_name']);
    $quantity = intval($_POST['quantity']);

    // Validate item input
    if (empty($item_name)) {
        $error = 'Item name cannot be empty.';
    } elseif (isset($_SESSION['inventory'][$item_name])) {
        $error = 'Item already exists in the inventory.';
    } elseif ($quantity <= 0) {
        $error = 'Quantity must be a positive number.';
    } else {
        // Add the item to inventory
        $_SESSION['inventory'][$item_name] = $quantity;
    }
}

// Search functionality
$search_result = '';
if (isset($_GET['search_name'])) {
    $search_name = trim($_GET['search_name']);
    if (isset($_SESSION['inventory'][$search_name])) {
        $search_result = 'Item: ' . htmlspecialchars($search_name) . ' | Quantity: ' . $_SESSION['inventory'][$search_name];
    } else {
        $search_result = 'Product not found!';
    }
}

$inventory = $_SESSION['inventory'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 28px;
            color: #5a67d8;
            border-bottom: 2px solid #5a67d8;
            padding-bottom: 10px;
        }
        h2 {
            font-size: 22px;
            color: #2b6cb0;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #48bb78;
            color: white;
            border: none;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #38a169;
        }
        .error {
            color: #e53e3e;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4a5568;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #e2e8f0;
        }
        .search-result {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Inventory Management</h1>

    <!-- Add New Item -->
    <h2>Add New Item</h2>
    <form method="POST" action="">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required><br>
        <label for="quantity">Item Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br>
        <button type="submit">Add Item</button>
    </form>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error; ?></p>
    <?php endif; ?>

    <!-- Search Item -->
    <h2>Search Item</h2>
    <form method="GET" action="">
        <label for="search_name">Enter Item Name:</label>
        <input type="text" id="search_name" name="search_name" required>
        <button type="submit">Search</button>
    </form>
    <p class="search-result"><?= $search_result; ?></p>

    <!-- Display Inventory -->
    <h2>Inventory</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory as $item => $quantity): ?>
                <tr>
                    <td><?= htmlspecialchars($item); ?></td>
                    <td><?= htmlspecialchars($quantity); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
