<table id="serviceTable" class="table table-bordered table-striped">
<thead>
<tr>
<th>Date</th>
<th>Name</th>
<th>Mobile</th>
<th>Service</th>
<th>Service Assign By</th>
<th>Total</th>
<th>Paid</th>
<th>Pending</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
</table>

<!-- Edit Modal -->
<div class="modal fade" id="editModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5>Edit Service</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<form id="editForm">
<input type="hidden" name="id" id="edit_id">

<label>Total Amount</label>
<input type="number" name="total_amount" id="edit_total" class="form-control">

<label class="mt-2">Paid Amount</label>
<input type="number" name="paid_amount" id="edit_paid" class="form-control">

<label class="mt-2">Status</label>
<select name="status" id="edit_status" class="form-select">
<option>Open</option>
<option>Closed</option>
</select>

<button class="btn btn-success mt-3 w-100">Update</button>
</form>
</div>
</div>
</div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$('#serviceTable').DataTable({
"ajax": "fetch_service.php",
"responsive": true
});
var table = $('#serviceTable').DataTable({
    ajax: "fetch_service.php",
    responsive: true
});

// DELETE
$(document).on("click",".deleteBtn",function(){
    if(confirm("Are you sure?")){
        let id = $(this).data("id");

        $.post("delete_service.php",{id:id},function(res){
            alert(res);
            table.ajax.reload();
        });
    }
});

// EDIT OPEN
$(document).on("click",".editBtn",function(){
    let id = $(this).data("id");

    $.post("get_single_service.php",{id:id},function(data){
        let row = JSON.parse(data);

        $("#edit_id").val(row.id);
        $("#edit_total").val(row.total_amount);
        $("#edit_paid").val(row.paid_amount);
        $("#edit_status").val(row.status);

        $("#editModal").modal("show");
    });
});

// UPDATE
$("#editForm").submit(function(e){
    e.preventDefault();

    $.post("update_service.php",$(this).serialize(),function(res){
        alert(res);
        $("#editModal").modal("hide");
        table.ajax.reload();
    });
});
</script>