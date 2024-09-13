<?php 
include 'db_connect.php'; 
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM tenants where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-tenant">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row form-group">
			<div class="col-md-4">
				<label for="" class="control-label"> الاسم الاول</label>
				<input type="text" class="form-control" name="lastname"  value="<?php echo isset($lastname) ? $lastname :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label"> الاسم الاوسط</label>
				<input type="text" class="form-control" name="firstname"  value="<?php echo isset($firstname) ? $firstname :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label"> اللقب </label>
				<input type="text" class="form-control" name="middlename"  value="<?php echo isset($middlename) ? $middlename :'' ?>">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-4">
				<label for="" class="control-label">الايميل</label>
				<input type="email" class="form-control" name="email"  value="<?php echo isset($email) ? $email :'' ?>" required>
			</div>
			<div class="col-md-4">
				<label for="" class="control-label">رقم الهاتف #</label>
				<input type="text" class="form-control" name="contact"  value="<?php echo isset($contact) ? $contact :'' ?>" required>
			</div>
			
		</div>
		<div class="form-group row">
		<div class="col-md-6">
		<label class="control-label">اختيار معرض</label>
            <select name="category_id" id="category_id" class="custom-select select2">
                <!-- <option value=""></option> -->

                <?php
                $categories = $conn->query("SELECT * FROM categories order by name asc");
                while ($row = $categories->fetch_assoc()) :
                ?>

                    <option value="<?php echo $row['id'] ?>" <?php echo isset($category_id) && $category_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
                <?php endwhile; ?>
            </select>

        </div>
	

			
			<div class="col-md-4" id="details_boot">
			<label for="" class="control-label">اختيار بوث</label>
                        <select class="custom-select select2" id="house_id" name="house_id">

					<?php 
					if(isset($house_id)):
						
					$house = $conn->query("SELECT * FROM houses where category_id = '$category_id' AND id not in (SELECT house_id from tenants where status = 1 ) ".(isset($house_id)? " or id = $house_id": "" )." ");
					while($row= $house->fetch_assoc()):
					?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($house_id) && $house_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['house_no'] ?></option>
					<?php endwhile; ?>
					<?php else: ?>
                            required>
                            <option selected disabled><?php echo 'Select Boot' ?></option>
					<?php endif ?>

                        </select>

                    </div>
            </div>

			<div class="col-md-4">
				<label for="" class="control-label">التاريخ </label>
				<input type="date" class="form-control" name="date_in"  value="<?php echo isset($date_in) ? date("Y-m-d",strtotime($date_in)) :'' ?>" required>
			</div>
		</div>
	</form>
</div>
<script>
	

	$(document).ready(function(){
    $('#category_id').on('change', function(){
        var categoryID = $(this).val();
        if(categoryID){
            $.ajax({
                type:'POST',
			url:'ajax.php?action=getBootByCategory',
			data:'category_id='+categoryID,
                success:function(html){
                    $('#house_id').html(html);
                }
            }); 
        }else{
            $('#house_id').html('<option value="">أختر المعرض اولا</option>');
        }
    });
});


	$('#manage-tenant').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_tenant',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved.",'success')
						setTimeout(function(){
							location.reload()
						},1000)
				}
			}
		})
	})
</script>