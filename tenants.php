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
					<div class="col-md-12 mb-2">
						
							</div>
						<b> قائمة المستاجرين </b>
						<br/>
							<br/>
							<a href="index.php?page=payment_report" class="btn btn-sm btn-outline-primary view_payment">	<button class="btn btn-sm btn-outline-primary view_payment" type="button"  >عرض تقارير المستأجرين</button></a>
						<span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_tenant">
					<i class="fa fa-plus"></i>  اضافة مستاجر جديد
				</a></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">الاسم</th>
									<th class="">رقم البوث </th>
									<th class="">أسم المعرض </th>
									<th class="">رقم المعرض </th>
									<th class="">مبلغ التاجير </th>
									
									

									<th class="">المبلغ المتبقي</th>
									<th class="">تاريخ التاجير </th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								
								
								$tenant = $conn->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename)
								 as full_name,h.house_no,h.price,h.category_id,c.name FROM tenants t 
								inner join houses h on h.id = t.house_id 
								inner join categories c on c.id = h.category_id 
								 where t.status = 1 order by h.house_no desc ");
								while($row=$tenant->fetch_assoc()):
								
									$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
									$months = floor(($months) / (30*60*60*24));
									$payable = $row['price'] * 1;
									$paid = $conn->query("SELECT SUM(amount) as paid FROM payments where tenant_id =".$row['id']);
									$last_payment = $conn->query("SELECT * FROM categories, payments where tenant_id =".$row['id']." order by unix_timestamp(date_created) desc limit 1");
									$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
									$last_payment = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
									$outstanding = $payable - $paid;
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td>
										<?php echo ucwords($row['full_name']) ?>
									</td>
									<td class="">
										 <p> <b><?php echo $row['house_no'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['name'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['category_id'] ?></b></p>
									</td>
									
									<td class="">
										 <p> <b><?php echo number_format($row['price'],2) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo number_format($outstanding,2) ?></b></p>
									</td>
									
									<td class="">
										 <p><b><?php echo  $last_payment ?></b></p>
									</td>
									<td class="text-center">
	

										
 

										<button class="btn btn-sm btn-outline-primary edit_tenant" type="button"
										 data-id="<?php echo $row['id'] ?>"
										 data-category_id="<?php echo $row['category_id'] ?>"
										 data-house_no="<?php echo $row['house_no'] ?>" >تعديل</button>
										<button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id'] ?>">حذف</button>
										<a href="index.php?page=mmm&id=<?php echo $row['id'] ?>" class="btn btn-sm btn-outline-primary view_payment">	<button class="btn btn-sm btn-outline-primary view_payment" type="button"  > تقرير المستأجر</button></a>
										</form>

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
	
	$('#new_tenant').click(function(){
		uni_modal("New Tenant","manage_tenant.php","mid-large")
		
	})

	$('.view_payment').click(function(){
		uni_modal("Tenants Payments","view_payment.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.edit_tenant').click(function(){
		let id = $(this).attr('data-id');
		let category_id = $(this).attr('data-category_id');
		let house_no = $(this).attr('data-house_no');
		
		uni_modal("Manage Tenant Details","manage_tenant.php?id="+id +"&category_id="+category_id + "&house_no=" + house_no ,"mid-large")
		
	})
	$('.delete_tenant').click(function(){
		_conf("Are you sure to delete this Tenant?","delete_tenant",[$(this).attr('data-id')])
	})
	
	function delete_tenant($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_tenant',
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