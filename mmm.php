<?php include 'db_connect.php' ?>
<?php 
$id = isset($_GET['id']) ? $_GET['id'] : '';
$month_of = isset($_GET['month_of']) ? $_GET['month_of'] : '';
// print_r($month_of);
?>
<style>
	.on-print{
		display: none;
	}
</style>
<noscript>
	<style>
		.text-center{
			text-align:center;
		}
		.text-right{
			text-align:right;
		}
		table{
			width: 100%;
			border-collapse: collapse
		}
		tr,td,th{
			border:1px solid black;
		}
	</style>
</noscript>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="col-md-12">
					<form id="filter-report">
					<input type="hidden" name="id" value="<?php echo ($id) ?>">

						<div class="row form-group">
							<label class="control-label col-md-2 offset-md-2 text-right"> حسب شهر: </label>
							<input type="month" name="month_of" class='from-control col-md-4' value="<?php echo ($month_of) ?>" require>
							<button class="btn btn-sm btn-block btn-primary col-md-2 ml-1">Filter</button>
						</div>
					</form>
					<hr>
						<div class="row">
							<div class="col-md-12 mb-2">
							<button class="btn btn-sm btn-block btn-success col-md-2 ml-1 float-right" type="button" id="print"><i class="fa fa-print"></i> طباعه</button>
							</div>
						</div>
					<div id="report">
						<div class="on-print">
							 <p><center>Rental Payments Report</center></p>
							 <p><center>for the Month of <b><?php echo date('F ,Y',strtotime($month_of.'-1')) ?></b></center></p>
						</div>
						<div class="row">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Date</th>
										<th>اسم المستاجر</th>
										<th>اسم المعرض</th>
										<th>رقم البوث #</th>
										<th>رقم الفاتورة</th>
										<th>المدفوع</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									$tamount = 0;
									if(!isset($_GET['month_of'])) {

									$payments  = $conn->query("SELECT p.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as 
									full_name,h.house_no,c.name FROM payments p 
									inner join tenants t on t.id = '$id' 
									inner join houses h on h.id = t.house_id 
									inner join categories c on c.id = h.category_id 
									where p.tenant_id = '$id'
									 order by unix_timestamp(date_created)  asc");
									}else{
										$payments  = $conn->query("SELECT p.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as 
									full_name,h.house_no,c.name FROM payments p 
									inner join tenants t on t.id = '$id' 
									inner join houses h on h.id = t.house_id 
									inner join categories c on c.id = h.category_id 
									where p.tenant_id = '$id'
									AND date_format(p.date_created,'%Y-%m') = '$month_of' 
									 order by unix_timestamp(date_created)  asc");

									}
									if($payments->num_rows > 0 ):
									while($row=$payments->fetch_assoc()):
										$tamount += $row['amount']; 
									?>
									<tr>
										<td><?php echo $i++ ?></td>
										<td><?php echo date('M d,Y',strtotime($row['date_created'])) ?></td>
										<td><?php echo ucwords($row['full_name']) ?></td>
										<td><?php echo ucwords($row['name']) ?></td>
										<td><?php echo $row['house_no'] ?></td>
										
										<td><?php echo $row['invoice'] ?></td>
										<td ><?php echo number_format($row['amount'],2) ?></td>
									</tr>
								<?php endwhile; ?>
								<?php else: ?>
									<tr>
										<th colspan="6"><center>No Data.</center></th>
									</tr>
								<?php endif; ?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan="5">Total Amount</th>
										<th class='text-right'><?php echo number_format($tamount,2) ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#print').click(function(){
		var _style = $('noscript').clone()
		var _content = $('#report').clone()
		var nw = window.open("","_blank","width=800,height=700");
		nw.document.write(_style.html())
		nw.document.write(_content.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
		nw.close()
		},500)
	})
	$('#filter-report').submit(function(e){
		e.preventDefault()
		location.href = 'index.php?page=mmm&'+$(this).serialize()
	})
</script>