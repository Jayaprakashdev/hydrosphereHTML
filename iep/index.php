<?php
session_start();

// ================= SIMPLE LOGIN CONFIG =================
$valid_username = "hydrosphereiep";
$valid_password = "hyro2ws2pft$"; // change this

// ================= LOGOUT =================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// ================= LOGIN CHECK =================
if (!isset($_SESSION['logged_in'])) {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === $valid_username && $password === $valid_password) {
            $_SESSION['logged_in'] = true;
        } else {
            $error = "Invalid Login";
        }
    }

    if (!isset($_SESSION['logged_in'])) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { font-family: Arial; background:#f4f6f9; display:flex; justify-content:center; align-items:center; height:100vh; }
                form { background:#fff; padding:30px; border-radius:10px; width:300px; }
                input { width:100%; padding:10px; margin:10px 0; }
                button { width:100%; padding:10px; background:#007bff; color:#fff; border:none; }
                .error { color:red; }
            </style>
        </head>
        <body>
        <form method="POST">
            <h3>Admin Login</h3>
            <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="login">Login</button>
        </form>
        </body>
        </html>
        <?php
        exit();
    }
}

// ================= DATABASE CONNECTION =================

$host = "localhost";  // usually localhost
$username = "hydrosph_servicetrack";  
$password = "servicetract012983$$%^#";
$database = "hydrosph_service_tracking";

$conn = mysqli_connect($host, $username, $password, $database);

// $conn = new mysqli("localhost", "root", "", "service_tracking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ================= CREATE TABLE =================
$conn->query("CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE,
    type ENUM('income','expense'),
    category VARCHAR(100),
    description TEXT,
    amount DECIMAL(10,2)
)");

// ================= ADD ENTRY =================
if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $desc = $_POST['description'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO transactions (date,type,category,description,amount) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssssd", $date, $type, $category, $desc, $amount);
    $stmt->execute();
}

// ================= FILTER LOGIC =================
$filter = $_GET['filter'] ?? 'day';
$today = date('Y-m-d');
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

if (!empty($from) && !empty($to)) {
    $where = "date BETWEEN '$from' AND '$to'";
} else {
    switch ($filter) {
        case 'week':
            $where = "YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'month':
            $where = "MONTH(date) = MONTH(CURDATE()) AND YEAR(date)=YEAR(CURDATE())";
            break;
        case 'year':
            $where = "YEAR(date) = YEAR(CURDATE())";
            break;
        default:
            $where = "date = '$today'";
    }
}

// ================= FETCH DATA =================
$result = $conn->query("SELECT * FROM transactions WHERE $where ORDER BY id DESC");

// ================= CALCULATE =================
$income = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE type='income' AND $where")->fetch_assoc()['total'] ?? 0;
$expense = $conn->query("SELECT SUM(amount) as total FROM transactions WHERE type='expense' AND $where")->fetch_assoc()['total'] ?? 0;
$profit = $income - $expense;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hydrosphere Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial; margin:0; background:#f4f6f9; }
        .container { width: 95%; margin:auto; }
        h2 { text-align:center; }
        .topbar { display:flex; justify-content:space-between; align-items:center; }
        .logout { text-decoration:none; color:red; }

        .cards { display:flex; flex-wrap:wrap; gap:10px; }
        .card { flex:1; min-width:250px; padding:20px; color:#fff; border-radius:10px; text-align:center; }
        .income { background:#28a745; }
        .expense { background:#dc3545; }
        .profit { background:#007bff; }

        .filters { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin:10px 0; }
        .filters a { padding:8px 15px; background:#ddd; text-decoration:none; border-radius:20px; font-size:14px; }
        .active { background:#007bff !important; color:#fff; }

        form { background:#fff; padding:15px; margin-top:15px; border-radius:10px; display:flex; flex-wrap:wrap; gap:10px; }
        input, select { flex:1; min-width:140px; padding:10px; }
        button { padding:10px; background:#007bff; color:#fff; border:none; border-radius:5px; }

        .table-container { overflow-x:auto; margin-top:15px; }
        table { width:100%; border-collapse: collapse; background:#fff; }
        th, td { padding:10px; border:1px solid #ccc; text-align:left; font-size:14px; }

        @media (max-width: 600px) {
            h2 { font-size:18px; }
            .card { padding:15px; font-size:14px; }
            form { flex-direction:column; }
            input, select, button { width:100%; }
        }
    </style>
</head>
<body>
<div class="container">

<div class="topbar">
    <h2>Hydrosphere Dashboard</h2>
    <a href="?logout=true" class="logout">Logout</a>
</div>

<div class="filters">
    <a href="?filter=day" class="<?php if($filter=='day') echo 'active'; ?>">Today</a>
    <a href="?filter=week" class="<?php if($filter=='week') echo 'active'; ?>">Week</a>
    <a href="?filter=month" class="<?php if($filter=='month') echo 'active'; ?>">Month</a>
    <a href="?filter=year" class="<?php if($filter=='year') echo 'active'; ?>">Year</a>
</div>

<div class="cards">
    <div class="card income">Total Income<br>₹<?php echo $income; ?></div>
    <div class="card expense">Total Expense<br>₹<?php echo $expense; ?></div>
    <div class="card profit">Net Profit<br>₹<?php echo $profit; ?></div>
</div>

<form method="GET">
    <input type="date" name="from" value="<?php echo $from; ?>">
    <input type="date" name="to" value="<?php echo $to; ?>">
    <button type="submit">Filter</button>
</form>

<form method="POST">
    <input type="date" name="date" required>
    <select name="type">
        <option value="income">Income</option>
        <option value="expense">Expense</option>
    </select>
    <input type="text" name="category" placeholder="Category" required>
    <input type="text" name="description" placeholder="Description">
    <input type="number" name="amount" placeholder="Amount" required>
    <button name="submit">Save</button>
</form>

<div class="table-container">
<table>
<tr>
<th>Date</th>
<th>Type</th>
<th>Category</th>
<th>Description</th>
<th>Amount</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?php echo $row['date']; ?></td>
<td><?php echo $row['type']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['description']; ?></td>
<td>₹<?php echo $row['amount']; ?></td>
</tr>
<?php } ?>

</table>
</div>

</div>
</body>
</html>