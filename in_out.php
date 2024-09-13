<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b> قائمة المصروفات </b>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_in_out">
					<i class="fa fa-plus"></i> اضافة جديدة 
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>

								<th class="text-center">رقم الفاتورة</th>
								<th class="text-center">أسم المعرض</th>
									<th class="text-center">المبلغ المصروف</th>
									<th class="text-center">الوصف</th>
									<th class="text-center">التاريخ</th>

									<th class="text-center">Action</th>

								</tr>
							</thead>
							<tbody>
								<?php 
					$in_out = $conn->query("SELECT i.*,c.name as cname FROM in_out i inner join categories c on c.id = i.category_id order by id asc ");
					while($row=$in_out->fetch_assoc()):
									
								?>
								<tr>
								<td class="">
										 <p> <b><?php echo ucwords($row['in_out_no']) ?></b></p>
								
								

									<td class="">
										 <p> <b><?php echo ucwords($row['cname']) ?></b></p>
									</td>
									
									<td >
										 <p> <b><?php echo number_format($row['amount'],2) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['description']) ?></b></p>
									</td>
									</td>									<td>
										<?php echo date('M d, Y',strtotime($row['date_created'])) ?>
									</td>
									
									
									<td class="text-center">
									<a href="index.php?page=in_out_report&category_id=<?php echo $row['category_id'] ?>" class="btn btn-sm btn-outline-primary view_payment">عرض تقرير</a>
										<button class="btn btn-sm btn-outline-primary edit_in_out" type="button" data-id="<?php echo $row['id'] ?>" >تعديل</button>
										<button class="btn btn-sm btn-outline-danger delete_in_out" type="button" data-id="<?php echo $row['id'] ?>">حذف</button>
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
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('#new_in_out').click(function(){
		uni_modal("New invoice","manage_in_out.php","mid-large")
		
	}) 
	$('.edit_in_out').click(function(){
		uni_modal("Manage invoice Details","manage_in_out.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_in_out').click(function(){
		_conf("Are you sure to delete this invoice?","delete_in_out",[$(this).attr('data-id')])
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
</script>