<!DOCTYPE html>
<html>
<head>
	<link rel="shorcut icon" href="<?php echo base_url('favicon.ico'); ?>" />
	<title>Statistik</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>">
</head>
<body>
	<div class="container">
		<div class="row">
			<h1 class="page-header">Top <?php echo ($country==TRUE) ? "Country" : "Links "; ?>
				
			</h1>
		</div>
		<div class="row">
			<table class="table table-striped" id="table">
				<thead>
					<tr>
						<?php if($country==TRUE) : ?>
							<th>Country</th>
							<th>Alias</th>
							<th>Count</th>
						<?php else : ?>
							<th>Alias</th>
							<th>URL</th>
							<th>Hit Count</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody></tbody>
				
			</table>
		</div>
	</div>

	<script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
	<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
	<script src="<?php echo base_url('assets/datatables/js/datatables.bootstrap.js'); ?>"></script>

	<script>
		let table
		let country = "<?php echo $country ?>"
		
		let columns

		if(country){
			columns = [{"data":"ip_country"}, {"data":"url_string"},{"data": "number"}]
			}else { 
			columns = [{"data":"alias"}, {"data":"url"}, {"data": "hit_count"}]
		}
		
		$(function(){

			table = $("#table").DataTable({
				"bProcessing" : true,
		        "deferRender": true,
				// AJAX Source
				"ajax" : {
					"url" : "<?php echo site_url('stats/json/'); ?>"+country,
					"type" : "POST",
				},
				"columns" : columns,
				
			})
		})
	</script>
</body>
</html>