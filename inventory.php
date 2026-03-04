<?php
require 'db.php';

/* INSERT DATA ONLY IF FORM IS SUBMITTED */
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'];
    $category = $_POST['category'];
    $total_qty = $_POST['total_qty'];
    $available = $_POST['available'];
    $status = $_POST['status'];

    mysqli_query($conn,
    "INSERT INTO inventory(name,category,total_qty,available,status)
    VALUES('$name','$category','$total_qty','$available','$status')");

    header("Location: inventory.php");
    exit();
}

/* FETCH DATA */
$result = mysqli_query($conn, "SELECT * FROM inventory ORDER BY category, name");
?>

<h1>Inventory</h1>

<div class="panel employee-panel">
<div class="card">

<h3>Add Item</h3>

<form method="POST">

<input type="text" name="name" placeholder="Item Name" required>
<br><br>

<input type="text" name="category" placeholder="Category" required>
<br><br>

<input type="number" name="total_qty" placeholder="Total Quantity" required>
<br><br>

<input type="number" name="available" placeholder="Available" required>
<br><br>

<select name="status" required>
    <option value="In Stock">In Stock</option>
    <option value="Out of Stock">Out of Stock</option>
</select>

<br><br>
<button type="submit">Add Item</button>

</form>
</div>
</div>

<hr>

<?php if(mysqli_num_rows($result) > 0): ?>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
<th>Name</th>
<th>Category</th>
<th>Total Qty</th>
<th>Available</th>
<th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['total_qty']; ?></td>
<td><?php echo $row['available']; ?></td>
<td><?php echo $row['status']; ?></td>
</tr>
<?php endwhile; ?>

</table>

<?php else: ?>
<p>No items found.</p>
<?php endif; ?>