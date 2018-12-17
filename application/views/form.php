<!DOCTYPE html>
<html>
<head>
	<link rel="shorcut icon" href="<?php echo base_url('favicon.ico'); ?>" />
	<title>Shorten URL</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/datatables.min.css') ?>">
	
	<script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/datatables.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>

</head>
<body>
	<div class="container">
		
		<div class="row">
			<h1 class="page-header">Shorten URL</h1>
		</div>
		<div class="row">
			<div class="center-block">
				<form class="form-horizontal" id="form" action="#" method="post">
					<div class="form-group">
						<div class="col-xs-9">
							<input type="text" name="url" id="url" class="form-control" placeholder="Enter full length of url and press 'shorten' " required >
						</div>
						<div class="col-xs-3">
							<button class="btn" type="submit">Shorten!</button>
						</div>
					</div>
				</form>
				<div id="alias" class="form-inline"></div>

			</div>
		</div>
	</div>


	<script>
		
		$("#form").on('submit', function(e) {
			e.preventDefault()

			$.ajax({
				url : "<?php echo base_url('shorten/create') ?>",
				type : "post",
				data:$('#form').serialize(),
				dataType:"JSON",
				success : function (data) {
					$("#alias").html("<i><a target='_blank' href='"+data.result+"'>"+data.result+"</a></i>")
					
				}
			})
		})
		

	
	</script>
</body>
</html>