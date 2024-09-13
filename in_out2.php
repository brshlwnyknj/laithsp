<?php include('db_connect.php');?>


<?php 
$dbValue = 1;
$pad_len = 7;
?>
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-in_out">
				<div class="card">
					<div class="card-header">
						    اضافة مصروفات 
				  	</div>
					<div class="card-body">
							<div class="form-group" id="msg"></div>
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label"> رقم الفاتورة</label>
								<?php 
									$sql = $conn->query("SELECT in_out_no FROM in_out order by id DESC LIMIT 1");
									if($sql->num_rows > 0):
									$row= $sql->fetch_assoc();
									if ($pad_len <= strlen($row['in_out_no'])):
									$row['in_out_no']++;

									?>
								<input type="text" class="form-control" name="in_out_no" required="" readonly value="<?php echo $dbValue = $row['in_out_no'] ?>">
								<?php endif; ?>

								<?php else: ?>
									<input type="text" class="form-control" name="in_out_no" required="" readonly value="<?php echo $dbValue = "F-".str_pad($dbValue, 7, "0", STR_PAD_LEFT) ?>">
									<?php endif; ?>
							</div>
							<div class="form-group">
								<label class="control-label">اختيار معرض</label>
								<select name="category_id" id="" class="custom-select" required>
									<?php 
									$categories = $conn->query("SELECT * FROM categories order by name asc");
									if($categories->num_rows > 0):
									while($row= $categories->fetch_assoc()) :
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
								<?php endwhile; ?>
								<?php else: ?>
									<option selected="" value="" disabled="">Please check the category .</option>
								<?php endif; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">المبلغ</label>
								<input type="number" class="form-control text-right" name="amount" step="any" required="">
							</div>
							<div class="form-group">
								<label for="" class="control-label">الوصف</label>
								<textarea name="description" id="" cols="30" rows="4" class="form-control" required></textarea>
							</div>
						
					</div>
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> حفظ</button>
								<button class="btn btn-sm btn-default col-sm-3" type="reset" > الغاء</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b> قائمة المصروفات</b>
						
						
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">رقم الفاتورة</th>
									<th class="text-center">المبلغ المصروف</th>
									<th class="text-center">أسم المعرض</th>
									<th class="text-center">الوصف</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>


								<?php 
								$in_out = $conn->query("SELECT i.*,c.name as cname FROM in_out i inner join categories c on c.id = i.category_id order by id asc ");
								while($row=$in_out->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $row['in_out_no'] ?></td>
									<td class=""><b><?php echo number_format($row['amount'],2) ?></b></td>
									<td class=""><?php echo $row['cname'] ?></td>
									<td class=""><?php echo $row['description'] ?></td>

									<td class="text-center">
									<a href="index.php?page=in_out_report&category_id=<?php echo $row['category_id'] ?>" class="btn btn-sm btn-outline-primary view_payment">عرض تقرير</a>
									<button class="btn btn-sm btn-primary edit_in_out" type="button" data-id="<?php echo $row['id'] ?>"  data-in_out_no="<?php echo $row['in_out_no'] ?>"data-category_id="<?php echo $row['category_id'] ?>"data-amount="<?php echo $row['amount'] ?>"data-description="<?php echo $row['description'] ?>" >تعديل</button>
									<button class="btn btn-sm btn-danger delete_in_out" type="button" data-id="<?php echo $row['id'] ?>">حذف</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p {
		margin: unset;
		padding: unset;
		line-height: 1em;
	}
</style>
<script>
	$('#manage-in_out').on('reset',function(e){
		$('#msg').html('')
	})
	$('#manage-in_out').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_in_out',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					$('#msg').html('<div class="alert alert-danger">In Out number already exist.</div>')
					end_load()
				}
			}
		})
	})
	$('.edit_in_out').click(function(){
		start_load()
		var cat = $('#manage-in_out')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='in_out_no']").val($(this).attr('data-in_out_no'))
		cat.find("[name='category_id']").val($(this).attr('data-category_id'))
		cat.find("[name='amount']").val($(this).attr('data-amount'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		end_load()
	})
	$('.delete_in_out').click(function(){
		_conf("Are you sure to delete this house?","delete_in_out",[$(this).attr('data-id')])
	})
	function delete_in_out($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_in_out',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>