<!doctype html>
<html lang="en">
	
	<?= $this->include('common/head') ?>

  <body>
   <?= $this->include('common/menu') ?>

<main role="main">

  <div class="container marketing">
				<h4 class="mt-4" id="mainTitle">설정</h4>
				<form style="min-height: 600px;">
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">정산 시간</label>
						<div class="col-10">
							<select class="form-control" id="CAL_TIME">
						      <option value="">선택</option>
						    </select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-search-input" class="col-2 col-form-label">기계 수</label>
						<div class="col-10">
							<input class="form-control" type="number" id="MACHINE_CNT" value="<?php echo $_SESSION['user']['MACHINE_CNT'] ?>" id="MACHINE_CNT">
						</div>
					</div>
					<div class="col text-center">
						<button type="button" class="btn btn-primary btn-lg" onclick="save();return false;">저장</button>
					</div>
				</form>

			</div><!-- /.container -->
  <!-- FOOTER -->
	<?= $this->include('common/foot') ?>
</main>
<!-- MODAL -->
<?= $this->include('common/modal') ?>
<!-- JAVASCRIPT -->
<?= $this->include('common/javascript') ?>

<script >
	var calTime = <?php echo $_SESSION['user']['CAL_TIME'] ?>;
	$('#CAL_TIME').empty();
	for(var i=0; i < 24; i++){
		var selected = '';
		if(calTime == i){
			selected = 'selected';
		}
		$('#CAL_TIME').append('<option value="'+i+'" '+selected+'>'+(i<10?'0'+i:i)+':00</option>');
	}
	
	function save(){
		var sendParam = {
			CAL_TIME : $('#CAL_TIME').val()
			,MACHINE_CNT : $('#MACHINE_CNT').val()
		}
		callApi("/ApiWeb/updateSetting",sendParam,function (data){
			
		});
	}
	
</script>
</html>
