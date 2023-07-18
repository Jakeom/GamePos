<!doctype html>
<html lang="en">
	
	<?= $this->include('common/head') ?>

  <body>
   <?= $this->include('common/menu') ?>

<main role="main">

  <div class="container marketing">
	<h4 class="mt-4" id="mainTitle">일일정산</h4>
	
	<div class="row">
      		<table class="table">
				<thead>
					<tr>
						<th class="table_border" scope="col">적립</th>
						<th class="table_border" scope="col">차감</th>
						<th class="table_border" scope="col">누적 포인트</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="table_border color_blue" id="PLUS_POINT">0</td>
						<td class="table_border color_red" id="MINUS_POINT">0</td>					
						<td class="table_border" id="ALL_SUM">0</td>
					</tr>
					
				</tbody>
			</table>
     
    </div>
    <div id="ttt"></div>
	<div class="row featurette" style="min-height: 500px;">
    	<table class="table">
			<thead>
				<tr>
					<th scope="col">날짜</th>
					<th scope="col">적립</th>
					<th scope="col">차감</th>
					<th scope="col">서비스</th>
					<th scope="col">차이</th>
				</tr>
			</thead>
			<tbody id="dataBody">
				<tr id="dataRow">
					<th scope="row" id="ROW_DATE"></th>
					<td class="color_blue" id="ROW_PLUS_POINT"></td>
					<td class="color_red" id="ROW_MINUS_POINT"></td>
					<td class="color_green" id="ROW_SERVICE_POINT"></td>
					<td id="ROW_GAP"></td>
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
	var dateArry = getDateRangeData('<?= date("Y-m-d",strtotime("-90 days")) ?>','<?= date("Y-m-d",strtotime("+3 days")) ?>');
	//var dateArry = getDateRangeData('2020-01-08','2020-01-10');

	
	callApi("/ApiWeb/dayCal",{},function (data){
			var sum_plus = 0;
			var sum_minus = 0;
			
			$('#PLUS_POINT').text(0);
			$('#MINUS_POINT').text(0);
			$('#GAP').text(0);
			
			for(var i =dateArry.length-1; i > -1; i--){
				var row = dataRow.clone();
				row.find('#ROW_DATE').text(dateArry[i]);
				row.find('#ROW_PLUS_POINT').text(0);
				row.find('#ROW_MINUS_POINT').text(0);
				row.find('#ROW_SERVICE_POINT').text(0);
				row.find('#ROW_GAP').text(0);
				
				row.addClass('d'+dateArry[i]);
				$('#dataBody').append(row);
			}
			
			for (var i = 0; i < data.datas.DAY_LIST.length; i++) {
				var _data = data.datas.DAY_LIST[i];
				if($('.d'+_data.CREATE_TIME).html() != undefined){
					$('.d'+_data.CREATE_TIME).find('#ROW_PLUS_POINT').text(addCommas(_data.PLUS_POINT));
					$('.d'+_data.CREATE_TIME).find('#ROW_MINUS_POINT').text(addCommas(_data.MINUS_POINT));
					$('.d'+_data.CREATE_TIME).find('#ROW_SERVICE_POINT').text(addCommas(_data.SERVICE_POINT));
					$('.d'+_data.CREATE_TIME).find('#ROW_GAP').text(addCommas(Number(_data.PLUS_POINT)+Number(_data.MINUS_POINT)));
				}
			}
			
			$('#PLUS_POINT').text(addCommas(data.datas.POINT_SUM.PLUS_POINT));
			$('#MINUS_POINT').text(addCommas(data.datas.POINT_SUM.MINUS_POINT));
			$('#ALL_SUM').text(addCommas(data.datas.POINT_SUM.ALL_SUM));

	});
</script>
</html>
