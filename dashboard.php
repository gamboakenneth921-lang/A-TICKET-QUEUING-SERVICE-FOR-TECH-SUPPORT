<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn,
"SELECT * FROM tickets ORDER BY created_at DESC");

$EmpResult = mysqli_query($conn,
"SELECT * FROM employees");

//show result of all available staffs

$availableCount = mysqli_query($conn,
"SELECT COUNT(*) as total FROM employees WHERE status='Available'");

$availableStaff = mysqli_fetch_assoc($availableCount);


//shows results of tickets
$alltickets = mysqli_query($conn,
"SELECT COUNT(*) as all_total FROM tickets WHERE status='Pending'");

$availableTicket = mysqli_fetch_assoc($alltickets);


$assignedTickets = mysqli_query($conn,
"SELECT *  FROM tickets WHERE status='Assigned'");

//$assignedAvailableTickets = mysqli_fetch_assoc($assignedTickets);




//$Employee_result = mysqli_query($conn,
//"SELECT * FROM employees");






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
<style>

   .header{
    border:1px solid #e2e8f0;
    border-radius:12px;
    padding:18px;
    background:white;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
    margin-bottom:20px;
}

.header h1{
    margin:0;
}

.header p{
    margin-top:6px;
    color:#64748b;
}
body{
    background:#f1f5f9;
    font-family:Segoe UI, sans-serif;
}

.box-container{
    display:flex;
    gap:18px;
    flex-wrap:wrap;
    padding:20px;
}

.box{
    width:220px;
    padding:20px;
    background:white;
    border:1px solid #e2e8f0;
    border-radius:14px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

.box-title{
    color:#64748b;
    font-size:14px;
    margin-bottom:8px;
}

.box-number{
    font-size:30px;
    font-weight:600;
    color:#0f172a;
}
.dashboard-container{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
    padding:20px;
    background:#f1f5f9;
    font-family:Segoe UI, sans-serif;
}

/* Panels */
.panel{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:14px;
    padding:18px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.panel-header{
    border-bottom:1px solid #e2e8f0;
    padding-bottom:12px;
    margin-bottom:15px;
}

/* Ticket Cards */
.ticket-card{
    border:1px solid #e2e8f0;
    border-radius:12px;
    padding:14px;
    margin-bottom:12px;
}

.ticket-title{
    font-weight:600;
    margin-bottom:6px;
}

.ticket-meta{
    font-size:13px;
    color:#64748b;
}

.ticket-desc{
    margin-top:10px;
    font-size:14px;
    color:#475569;
}

/* Employees */
.employee-card{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border:1px solid #e2e8f0;
    border-radius:12px;
    padding:14px;
    margin-bottom:10px;
}

.emp-name{
    font-weight:600;
}

.emp-role{
    font-size:13px;
    color:#64748b;
}

/* Status badges */
.status{
    padding:6px 14px;
    border-radius:999px;
    font-size:12px;
    color:white;
}

.available{
    background:#22c55e;
}

.busy{
    background:#ef4444;
}




/* Responsive */
@media(max-width:900px){
    .dashboard-container{
        grid-template-columns:1fr;
    }
}
</style>
</head>

<body>

<div class="header">
    <header>
        <h1>Tech support dashboard</h1>
        <p class="h1 p">Monitor and assign support tickets</p>
    </header>
</div>

 <div class="box-container">

    <div class="box">
        <div class="box-title">Pending Tickets</div>
            <div class="box-number">
                <?php echo $availableTicket['all_total']; ?>
            </div>
        </div>

    <div class="box">
        <div class="box-title">Assigned Tickets</div>
        <div class="box-number">3</div>
    </div>

    <div class="box">
        <div class="box-title">Available Staff</div>
        <div class="box-number">
        <?php echo $availableStaff['total']; ?>
    </div>
    </div>

</div>

<div class="dashboard-container">

    <!-- LEFT SIDE — Pending Tickets -->
    <div class="panel tickets-panel">

    <?php while($row = mysqli_fetch_assoc($result)){ ?>

        <div class="ticket-card" onclick="toggleEmployees(this)">

            <div class="ticket-title">
                <?php echo $row['title']; ?>
            </div>

            <div class="ticket-meta">
                <?php echo $row['user_name']; ?> |
                <?php echo $row['email']; ?>
            </div>

            <div class="ticket-desc">
                <?php echo $row['description']; ?>
            </div>

            <div class="ticket-status">
                Status: <?php echo $row['status']; ?>
            </div>

            <!-- Hidden Available Employees -->
            <div class="employee-popup" style="display:none;">

                <?php
                $availableEmployees = mysqli_query($conn,
                "SELECT * FROM employees WHERE status='Available'");

                while($emp = mysqli_fetch_assoc($availableEmployees)){
                ?>

                    <div class="employee-card">
                        <div>
                            <div class="emp-name">
                                <?php echo $emp['name']; ?>
                            </div>
                            <div class="emp-role">
                                <?php echo $emp['role']; ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>

        </div>

    <?php } ?>

</div>






    <div class="panel employee-panel">

      <div class="card">

        <h3>Add Employee</h3>

        <form method="POST" action="add_employee.php">

            <input type="text" name="name" placeholder="Employee Name" required>
            <br><br>

            <input type="text" name="role" placeholder="Role / Position" required>
            <br><br>

                <select name="status">
                    <option>Available</option>
                    <option>Busy</option>
                    <option>Offline</option>
                </select>

            <br><br>

            <button type="submit">Add Employee</button>

        </form>

        <?php while($emp = mysqli_fetch_assoc($EmpResult)){ ?>

            <div class="ticket-card">

                <div class="ticket-title">
                    <?php echo $emp['name']; ?>
                </div>

                <div class="ticket-meta">
                    <?php echo $emp['role']; ?> |
                    
                </div>

                <div class="ticket-status">
                    Status: <?php echo $emp['status']; ?>
                </div>

            </div>

        <?php } ?>

</div>


</div>



<div class="panel Assigned-Tickets">
    <?php while($rows = mysqli_fetch_assoc($assignedTickets)){ ?>
    <div class="assigned-card">
        <div class="ticket-title">
            <?php echo $rows['title']; ?>
        </div>

        <div class="ticket-meta">
            <?php echo $rows['user_name']; ?> |
        </div>

        <div class="ticket-status">
            Status: <?php echo $rows['description']; ?>
        </div>
       
    </div>
    <?php } ?>


</div>


<script>
function toggleEmployees(card) {

    let popup = card.querySelector(".employee-popup");

    if (popup.style.display === "none") {
        popup.style.display = "block";
    } else {
        popup.style.display = "none";
    }

}
</script>





</body>
</html>