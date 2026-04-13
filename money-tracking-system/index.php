<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Money Traking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .is-invalid {
            border-color: red;
        }
        .deleteBtn{
            display: none;
        }
        .earn-table th, .earn-table td{
            white-space: nowrap;
        }
        #engineerList {
            list-style: none;
            padding-left: 0;
        }
        #engineerList li {
            padding: 5px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
    </style>
</head>

<body class="container mt-4">

<h3>Work Entry</h3>

<form id="workForm">
    <div class="row">
        <input type="hidden" name="id" id="id">
        <div class="col-md-2">
            <label>Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label>Work Type</label>
            <select name="work_type" id="work_type" class="form-control" required>
                <option value="">Select Work Type</option>
                <option value="Installation">Installation</option>
                <option value="Service">Service</option>
            </select>
        </div>

        <div class="col-md-3">
            <label id="desc_label">Description</label>
            <input type="text" name="description" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label>Amount</label>
            <input type="number" name="amount" class="form-control" required min="1">
        </div>

        <div class="col-md-2">
            <label>Engineer</label>
            <select name="engineer" class="form-control" required>
                <option value="">Select Engineer</option>
                <option>Abdulla</option>
                <option>Dinesh</option>
                <option>Dhanvath</option>
                <option>Karthik</option>
                <option>Jayaprakash</option>
                <option>Vicky</option>
            </select>
        </div>

        <div class="col-md-1 mt-4 text-center">
            <button class="btn btn-success" id="saveBtn">Add</button>
            <button type="button" class="btn btn-primary d-none" id="updateBtn">Update</button>
        </div>

    </div>
</form>

<hr>

<div class="row mb-3">

    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5>Total Earnings</h5>
                <h3 id="totalAmount">₹0</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5>Engineer Earnings</h5>
                <ul id="engineerList" class="mb-0"></ul>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <h5>Total Entries</h5>
                <h3 id="totalEntries">0</h3>
            </div>
        </div>
    </div>

</div>

<!-- FILTER -->
<div class="row mb-3">
    <div class="col-md-3">
        <label>From Date</label>
        <input type="date" id="from" class="form-control">
    </div>
    <div class="col-md-3">
        <label>To Date</label>
        <input type="date" id="to" class="form-control">
    </div>
    <div class="col-md-2 mt-4 text-center">
        <button id="filter" class="btn btn-primary">Filter</button>
        <button id="clearFilter" class="btn btn-secondary">Clear</button>
    </div>
</div>

<!-- TABLE -->
<div class="table-responsive">
    <table id="table" class="table table-bordered earn-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Engineer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- GRAPH -->
<canvas id="chart" height="100"></canvas>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function(){

$("#workForm").submit(function(e){
    e.preventDefault();

    let valid = true;

    $("#workForm [required]").each(function(){
        if($(this).val() === ""){
            $(this).addClass("is-invalid");
            valid = false;
        } else {
            $(this).removeClass("is-invalid");
        }
    });

    if(!valid){
        alert("Please fill all fields");
        return;
    }

    let btn = $("#saveBtn");

    // prevent multiple click
    if(btn.prop("disabled")) return;

    btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Adding...');

    $.post("insert.php", $(this).serialize(), function(res){

        if(res.trim() === "Success"){
            $("#workForm")[0].reset();
            $("#date").val(new Date().toISOString().split('T')[0]);
            loadTable();
        } else {
            alert(res);
        }

        btn.prop("disabled", false).text("Add");
    });
});

$("#work_type").change(function(){
    let type = $(this).val();

    if(type === ""){
        $("#desc_label").text("Description");
    } else {
        $("#desc_label").text(type + " Description");
    }
});

$(document).on("click", ".deleteBtn", function(){
    if(confirm("Are you sure to delete?")){
        let id = $(this).data("id");

        $.post("delete.php", {id:id}, function(){
            loadTable();
        });
    }
});

function loadSummary(from='', to=''){
    $.getJSON("summary.php", {from:from, to:to}, function(res){

        $("#totalAmount").text("₹" + res.totalAmount);
        $("#totalEntries").text(res.totalEntries);

        let html = "";

        res.engineers.forEach((eng, index) => {

            let medal = "";
            if(index === 0) medal = "🥇";
            else if(index === 1) medal = "🥈";
            else if(index === 2) medal = "🥉";

            html += `<li>${medal} ${eng.name} - ₹${eng.total}</li>`;
        });

        $("#engineerList").html(html);
    });
}

$(document).on("click", ".editBtn", function(){

    let id = $(this).data("id");

    $.getJSON("get_single.php", {id:id}, function(data){

        $("#id").val(data.id);
        $("#date").val(data.work_date);
        $("#work_type").val(data.work_type).change();
        $("input[name='description']").val(data.description);
        $("input[name='amount']").val(data.amount);
        $("select[name='engineer']").val(data.engineer);

        $("#saveBtn").addClass("d-none");
        $("#updateBtn").removeClass("d-none");

        // 🔥 ADD THIS (scroll to top)
        $('html, body').animate({ scrollTop: 0 }, 500);
    });
});

$('html, body').animate({ scrollTop: 0 }, 500);
$("#workForm").addClass("border border-warning");

setTimeout(() => {
    $("#workForm").removeClass("border border-warning");
}, 1500);

$("#updateBtn").click(function(){

    let btn = $("#updateBtn");

    // prevent multiple click
    if(btn.prop("disabled")) return;

    btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Updating...');

    let formData = $("#workForm").serialize();

    $.post("update.php", formData, function(res){

        $("#workForm")[0].reset();
        $("#id").val('');

        $("#saveBtn").removeClass("d-none");
        $("#updateBtn").addClass("d-none");

        $("#date").val(new Date().toISOString().split('T')[0]);

        loadTable();

        btn.prop("disabled", false).text("Update");
    });
});


    // Auto today date
    let today = new Date().toISOString().split('T')[0];
    $("#date").val(today);

    // Change label dynamically
    $("#work_type").change(function(){
        let type = $(this).val();
        $("#desc_label").text(type + " Description");
    });

    // Load table
    function loadTable(from='', to=''){
        $.get("fetch.php", {from:from, to:to}, function(data){

            if ($.fn.DataTable.isDataTable('#table')) {
                $('#table').DataTable().destroy();
            }

            $("#table tbody").html(data);

            $('#table').DataTable({
                responsive: true
            });

            loadChart(from, to);
            loadSummary(from, to); // 🔥 ADD THIS
        });
    }

    loadTable();

    // Filter
    $("#filter").click(function(){
        let from = $("#from").val();
        let to = $("#to").val();

        if(from === "" || to === ""){
            alert("Please select both dates");
            return;
        }

        loadTable(from, to);
    });

    $("#clearFilter").click(function(){

        $("#from").val('');
        $("#to").val('');

        loadTable(); // reload all data + chart
    });

    // Chart
    let myChart;

    function loadChart(from='', to=''){
        $.getJSON("chart.php", {from:from, to:to}, function(res){

            if(myChart){
                myChart.destroy(); // destroy old chart
            }

            myChart = new Chart(document.getElementById("chart"), {
                type: 'bar',
                data: {
                    labels: res.labels,
                    datasets: [{
                        label: 'Earnings',
                        data: res.data
                    }]
                }
            });
        });
    }

    $("#from, #to").change(function(){
        if($("#from").val() && $("#to").val()){
            loadTable($("#from").val(), $("#to").val());
        }
    });

});
</script>

</body>
</html>