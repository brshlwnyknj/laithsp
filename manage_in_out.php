<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM in_out where id= " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }

    
}

$dbValue = 1;
    $pad_len = 7;
?>
<div class="container-fluid">
    <form action="" id="manage-in_out">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg"></div>


        <div class="form-group">
            <label class="control-label"> رقم الفاتورة</label>
            <?php
            $sql = $conn->query("SELECT in_out_no FROM in_out order by id DESC LIMIT 1");
            if ($sql->num_rows > 0):
                $row = $sql->fetch_assoc();
                if ($pad_len <= strlen($row['in_out_no'])):
                    $row['in_out_no']++;

            ?>
                    <input type="text" class="form-control" name="in_out_no" required="" readonly value="<?php echo isset($in_out_no) ? $in_out_no : $dbValue = $row['in_out_no'] ?>">
                <?php endif; ?>

            <?php else: ?>
                <input type="text" class="form-control" name="in_out_no" required="" readonly value="<?php echo $dbValue = "F-" . str_pad($dbValue, 7, "0", STR_PAD_LEFT) ?>">
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label class="control-label">اختيار معرض</label>
            <select name="category_id" id="category_id" class="custom-select select2">
                <option value=""></option>

                <?php
                $categories = $conn->query("SELECT * FROM categories order by name asc");
                while ($row = $categories->fetch_assoc()) :
                ?>

                    <option value="<?php echo $row['id'] ?>" <?php echo isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
                <?php endwhile; ?>
            </select>

        </div>





        <div class="form-group">
            <label for="" class="control-label">المبلغ المدفوع : </label>
            <input type="number" class="form-control text-right" step="any" name="amount" value="<?php echo isset($amount) ? $amount : '' ?>">
        </div>

                      <div class="form-group">
								<label for="" class="control-label">الوصف</label>
								<textarea name="description" id="" cols="30" rows="4" class="form-control" required value="<?php echo isset($description) ? $description : '' ?>"><?php echo isset($description) ? $description : '' ?></textarea>
							</div>
</div>
</form>
</div>

<script>
    $(document).ready(function() {
        if ('<?php echo isset($id) ? 1 : 0 ?>' == 1)
            $('#category_id').trigger('change')
    })
    $('.select2').select2({
        placeholder: "Please Select Here",
        width: "100%"
    })

    $('#manage-in_out').submit(function(e) {
        e.preventDefault()
        start_load()
        $('#msg').html('')
        $.ajax({
            url: 'ajax.php?action=save_in_out',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved.", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }
            }
        })
    })
</script>