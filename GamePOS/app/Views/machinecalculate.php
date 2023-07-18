<!doctype html>
<html lang="en">
	
	<?= $this->include('common/head') ?>

  <body>
   <?= $this->include('common/menu') ?>

<main role="main">

  <div class="container marketing">
	<h4 class="mt-4" id="mainTitle">기계정산</h4>
	<div class="row mb-3">
    <div class="col-md-10 themed-grid-col">
      <div class="pb-3">
        
        
      </div>
      <div class="row">
        <div class="col-md-7 themed-grid-col">
        	<div class="form-group">
			    <label for="ST_DATE">날짜(시작~종료)</label>
			    <div class="row">
			    	<div class="col-md-6" ><input type="date" id="ST_DATE" name="ST_DATE" class="form-control " value="<?= date("Y-m-d") ?>" onkeypress="return false;"/></div>
			    	<div class="col-md-6" ><input type="date" id="ED_DATE" name="ED_DATE" class="form-control " value="<?= date("Y-m-d",strtotime("+1 days")) ?>"onkeypress="return false;"/></div>
			    </div>
			 </div>
        </div>
      </div>
      
    </div>
    <div class="col-md-2 themed-grid-col"><a href="#" class="btn btn-md  btn-outline-dark my-2 my-sm-0" onclick="clickSearchBtn();return false;">검색</a></div>
  </div>
	<div class="row">
      		<table class="table">
				<thead>
					<tr>
						<th class="table_border" scope="col">적립</th>
						<th class="table_border" scope="col">차감</th>
						<th class="table_border" scope="col">차이</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="table_border color_blue" id="PLUS_POINT" style="padding: .2rem;">0</td>
						<td class="table_border color_red" id="MINUS_POINT" style="padding: .2rem;">0</td>
						<td class="table_border" id="GAP" style="padding: .2rem;">0</td>
					</tr>
					
				</tbody>
			</table>
     
    </div>
    <div class="row featurette" style="min-height: 500px;">
    	<table class="table">
			<thead>
				<tr>
					<th scope="col">기계번호</th>
					<th scope="col">적립</th>
					<th scope="col">차감</th>
					<th scope="col">차이</th>
				</tr>
			</thead>
			<tbody id="dataBody">
				<tr id="dataRow">
					<th scope="row" id="ROW_MACHINE_NO" style="padding: .2rem;"></th>
					<td class="color_blue" id="ROW_PLUS_POINT" style="padding: .2rem;"></td>
					<td class="color_red"id="ROW_MINUS_POINT" style="padding: .2rem;"></td>
					<td id="ROW_GAP" style="padding: .2rem;"></td>
				</tr>
				
			</tbody>
		</table>
	</div>

  </div><!-- /.container -->
  <!-- FOOTER -->
	<?= $this->include('common/foot') ?>
</main>
<!-- MODAL -->
<?= $this->include('common/modal') ?>
<!-- JAVASCRIPT -->
<?= $this->include('common/javascript') ?>

<script >
	var dataRow = $('#dataRow').detach().show().attr('id','');
	var baseMachineCnt = '<?php echo $_SESSION['user']['MACHINE_CNT'] ?> ';
	
   function clickSearchBtn(){
		currentPage = 0;
		var sendParam = {
			ST_DATE : $('#ST_DATE').val()
			,ED_DATE : $('#ED_DATE').val()
		}
		
		$('#dataBody').empty();
		
		
		searchData(sendParam);
	}
	
	function searchData(sendParam) {
		callApi("/ApiWeb/machineCal",sendParam,function (data){

			var sum_plus = 0;
			var sum_minus = 0;
			
			$('#PLUS_POINT').text(0);
			$('#MINUS_POINT').text(0);
			$('#GAP').text(0);
				
			for(var i =1; i <= baseMachineCnt; i++){
				var row = dataRow.clone();
				row.find('#ROW_MACHINE_NO').text(i);
				row.find('#ROW_PLUS_POINT').text(0);
				row.find('#ROW_MINUS_POINT').text(0);
				row.find('#ROW_GAP').text(0);
				row.addClass('m'+i);
				$('#dataBody').append(row);
			}
			
			
			for (var i = 0; i < data.datas.MACHINE_LIST.length; i++) {
				var _data = data.datas.MACHINE_LIST[i];
				
				sum_plus = sum_plus + Number(_data.PLUS_POINT);
				sum_minus = sum_minus + Number(_data.MINUS_POINT);
				
				if($('.m'+_data.MACHINE_NO).html() != undefined){
					$('.m'+_data.MACHINE_NO).find('#ROW_PLUS_POINT').text(addCommas(_data.PLUS_POINT));
					$('.m'+_data.MACHINE_NO).find('#ROW_MINUS_POINT').text(addCommas(_data.MINUS_POINT));
					$('.m'+_data.MACHINE_NO).find('#ROW_GAP').text(addCommas(Number(_data.PLUS_POINT)+Number(_data.MINUS_POINT)));
				}else{
					var row = dataRow.clone();
					row.find('#ROW_MACHINE_NO').text(_data.MACHINE_NO);
					row.find('#ROW_PLUS_POINT').text(addCommas(_data.PLUS_POINT));
					row.find('#ROW_MINUS_POINT').text(addCommas(_data.MINUS_POINT));
					row.find('#ROW_GAP').text(addCommas(Number(_data.PLUS_POINT)+Number(_data.MINUS_POINT)));
					$('#dataBody').append(row);
				}
				

				
				$('#PLUS_POINT').text(addCommas(sum_plus));
				$('#MINUS_POINT').text(addCommas(sum_minus));
				$('#GAP').text(addCommas(sum_plus + sum_minus));
			}
			
			
		});
	}
	
	// 초기 1회 실행 
	clickSearchBtn();
	
</script>
</html>
