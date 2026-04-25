<?php include 'db.php'; ?>

<!DOCTYPE html>

<html>
<head>
    <title>Money Tracking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
    .is-invalid { border-color: red; }
    .earn-table th, .earn-table td { white-space: nowrap; }
    #engineerList { list-style: none; padding-left: 0; }
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
        <option value="">Select</option>
        <option value="Installation">Installation</option>
        <option value="Service">Service</option>
        <option value="Showroom Sale">Showroom Sale</option>
        <option value="Salary Advance">Salary Advance</option>
    </select>
</div>

<div class="col-md-2">
    <label id="desc_label">Description</label>
    <input type="text" name="description" class="form-control" required>
</div>

<div class="col-md-2">
    <label>Amount</label>
    <input type="number" name="amount" class="form-control" required>
</div>

<div class="col-md-2">
    <label>Expense</label>
    <input type="number" name="expense" class="form-control" value="0">
</div>

<div class="col-md-1">
    <label>Engineer</label>
    <select name="engineer" class="form-control" required>
        <option value="">Select</option>
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

<!-- SUMMARY -->

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Total Profit</h5>
                <h3 id="totalAmount">₹0</h3>
            </div>
        </div>
    </div>


<div class="col-md-4">
    <div class="card text-white bg-primary">
        <div class="card-body">
            <h5>Engineer Earnings</h5>
            <ul id="engineerList"></ul>
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
        <input type="date" id="from" class="form-control">
    </div>
    <div class="col-md-3">
        <input type="date" id="to" class="form-control">
    </div>
    <div class="col-md-2">
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
            <th>Expense</th>
            <th>Profit</th>
            <th>Engineer</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
</div>

<!-- CHART -->

<canvas id="chart"></canvas>

<!-- Scripts -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function(){

// today
$("#date").val(new Date().toISOString().split('T')[0]);

// submit
$("#workForm").submit(function(e){
    e.preventDefault();

    let btn = $("#saveBtn");
    if(btn.prop("disabled")) return;

    btn.prop("disabled", true).text("Adding...");

    $.post("insert.php", $(this).serialize(), function(res){
        if(res.trim() === "Success"){
            $("#workForm")[0].reset();
            $("#date").val(new Date().toISOString().split('T')[0]);
            loadTable();
        } else alert(res);

        btn.prop("disabled", false).text("Add");
    });
});

// edit
$(document).on("click", ".editBtn", function(){
    let id = $(this).data("id");

    $.getJSON("get_single.php", {id:id}, function(d){
        $("#id").val(d.id);
        $("#date").val(d.work_date);
        $("#work_type").val(d.work_type).change();
        $("input[name=description]").val(d.description);
        $("input[name=amount]").val(d.amount);
        $("input[name=expense]").val(d.expense);
        $("select[name=engineer]").val(d.engineer);

        $("#saveBtn").addClass("d-none");
        $("#updateBtn").removeClass("d-none");

        $('html, body').animate({scrollTop:0},500);
    });
});

// update
$("#updateBtn").click(function(){
    let btn = $(this);
    btn.prop("disabled", true).text("Updating...");

    $.post("update.php", $("#workForm").serialize(), function(){
        $("#workForm")[0].reset();
        $("#saveBtn").removeClass("d-none");
        $("#updateBtn").addClass("d-none");
        loadTable();
        btn.prop("disabled", false).text("Update");
    });
});

// delete
$(document).on("click", ".deleteBtn", function(){
    if(confirm("Delete?")){
        $.post("delete.php", {id:$(this).data("id")}, loadTable);
    }
});

// load table
function loadTable(from='',to=''){
    $.get("fetch.php",{from:from,to:to},function(data){
        if ($.fn.DataTable.isDataTable('#table')) {
            $('#table').DataTable().destroy();
        }
        $("#table tbody").html(data);
        $('#table').DataTable();
        loadSummary(from,to);
        loadChart(from,to);
    });
}
loadTable();

// summary
function loadSummary(from='',to=''){
    $.getJSON("summary.php",{from:from,to:to},function(res){
        $("#totalAmount").text("₹"+res.totalAmount);
        $("#totalEntries").text(res.totalEntries);

        let html="";
        res.engineers.forEach((e,i)=>{
            let medal = i==0?"🥇":i==1?"🥈":i==2?"🥉":"";
            html += `<li>${medal} ${e.name} - ₹${e.total}</li>`;
        });
        $("#engineerList").html(html);
    });
}

// chart
let chart;
function loadChart(from='',to=''){
    $.getJSON("chart.php",{from:from,to:to},function(res){
        if(chart) chart.destroy();
        chart = new Chart($("#chart"),{
            type:'bar',
            data:{ labels:res.labels, datasets:[{label:'Profit', data:res.data}] }
        });
    });
}

// filter
$("#filter").click(function(){
    loadTable($("#from").val(), $("#to").val());
});

$("#clearFilter").click(function(){
    $("#from,#to").val('');
    loadTable();
});

});
</script>

</body>
</html>
