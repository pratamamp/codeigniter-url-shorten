<!DOCTYPE html>
<html>
<head>
	<title>Shorten URL</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
	<script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>

</head>
<body>
	<div class="container">
		<div class="row">
			<h1 class="page-header"></h1>
		</div>
		<div class="row">
			<form class="form-horizontal" id="form" action="#">
				<div class="form-group">
					<div class="col-xs-9">
						<input type="text" name="url" id="url" class="form-control" placeholder="Enter full length of url then press Shorten">
					</div>
					<div class="col-xs-3">
						<button class="btn" type="button" onclick="submitForm()">Shorten</button>
					</div>
				</div>
			</form>	
		</div>
	</div>


	<script>
		
		function submitForm() {
			$.ajax({
				url : "<?php echo base_url('shorten/create') ?>",
				type : "post",
				data:$('#form').serialize(),
				dataType:"JSON",
				success : function (data) {
					console.log(data)
				}
			})
		}

	
	</script>
</body>
</html>