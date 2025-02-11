<?php include 'db_connect.php' ?>
<?php 
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
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
					<input type="hidden" name="category_id" value="<?php echo ($category_id) ?>">

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
							 <p><center>Rental in_out Report</center></p>
							 <p><center>for the Month of <b><?php echo date('F ,Y',strtotime($month_of.'-1')) ?></b></center></p>
						</div>
						<div class="row">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Date</th>
										<th>رقم الفاتورة</th>
										<th>اسم المعرض</th>
										<th>المبلغ المدفوع</th>
										<th>الوصف</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									$tamount = 0;
									if(!isset($_GET['month_of'])) {
										$in_out = $conn->query("SELECT i.*,c.name as cname FROM in_out i 
										inner join categories c 
										on c.id = '$category_id'
									where i.category_id = '$category_id'
                                   order by unix_timestamp(date_created)  asc");
									}else{

										$in_out = $conn->query("SELECT i.*,c.name as cname FROM in_out i 
										inner join categories c on c.id ='$category_id' 
										where i.category_id = '$category_id'
										AND date_format(i.date_created,'%Y-%m') = '$month_of' 
                                   order by unix_timestamp(date_created)  asc");


									}
									if($in_out->num_rows > 0 ):
									while($row=$in_out->fetch_assoc()):
										$tamount += $row['amount'];
									?>
									<tr>
										<td><?php echo $i++ ?></td>
										<td><?php echo date('M d,Y',strtotime($row['date_created'])) ?></td>
										<td><?php echo ucwords($row['in_out_no']) ?></td>
										<td><?php echo ucwords($row['cname']) ?></td>
										<td ><?php echo number_format($row['amount'],2) ?></td>
										<!-- <td><?php echo $row['house_no'] ?></td> -->
										
										<td><?php echo $row['description'] ?></td>
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
		location.href = 'index.php?page=in_out_report&'+$(this).serialize()
	})
</script>