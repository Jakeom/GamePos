<!doctype html>
<html lang="en">
	
	<?= $this->include('common/head') ?>

  <body>
   <?= $this->include('common/menu') ?>

<main role="main">

  <div class="container marketing">
	<h4 class="mt-4" id="mainTitle">키관리</h4>
	<div class="row" style="padding: 10px;border: 4px solid #f1f1f1;">
		<p style="width: 100%;"><font color="red">국민은행 XXXXXX-xx-xxxxx 아무게</font></p>
		<p style="width: 100%;">- 위 계좌로 입금 후 신청해주세요.</p>
		<p style="width: 100%;">* 신청 후 12시간 이내에 기간이 연장됩니다.</p>
		<p style="width: 100%;">* 입금자명이 다를 시 신청이 취소될 수 있습니다.</p>
		
		<div class="line"></div>
		<p style="width: 100%;">입금자정보</p>
		<div class="row" style="width: 100%">
			<div class="col-md-2" >
	    		<select class="form-control" id="SEND_MONTH">
			    </select>
	    	</div>
	    	<div class="col-md-8" >
	    		<div class="form-group">
					<input type="text" class="form-control" id="SENDER_NM" name="SENDER_NM"  maxlength="20"  placeholder="입금자명을 정확히 입력해주세요.">
				</div>
	    	</div>
	    	<div class="col-md-2" ><a href="#" class="btn btn-md  btn-outline-primary  my-2 my-sm-0" onclick="return false;">신청</a></div>
	    </div>
	</div>
	
	<div class="row featurette" style="min-height: 500px;">
    	<table class="table">
			<thead>
				<tr>
					<th scope="col"><input type="checkbox" class="t" id="all_chb"></th>
					<th scope="col">키</th>
					<th scope="col">상태</th>
					<th scope="col">유효기간</th>
					<th scope="col">관리</th>
				</tr>
			</thead>
			<tbody id="dataBody">
				<tr id="dataRow">
					<td scope="row" ><input type="checkbox" class="_chb"></td>
					<th id="ROW_ADMIN_ID"></th>
					<td id="ROW_ADMIN_ABLE_YN"></td>
					<td id="ROW_USE_ABLE_TIME"></td>
					<td id="ROW_DATA_FIELD">
						<a class="btn btn-sm  btn-success" id="ROW_STOP_BTN"  style="width: 58px ;font-size: 10px ;"></a> 
					</td>
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
	$('#SEND_MONTH').empty();
	for(var i=1; i <= 12; i++){
		var selected = '';
		if(1 == i){
			selected = 'selected';
		}
		$('#SEND_MONTH').append('<option value="'+i+'" '+selected+'>'+i+'달 연장</option>');
	}
	
	var dataRow = $('#dataRow').detach().show().attr('id','');
	
	function searchAdmin(){
		$('#dataBody').empty();
		callApi("/ApiWeb/getAdmin",{},function (data){
			// 데이타 출력
						var datas = data.datas;
						for (var i = 0; i < datas.ADMIN_LIST.length; i++) {
							var adminData = datas.ADMIN_LIST[i];
							var row = dataRow.clone();
							row.find('#ROW_ADMIN_ID').text(adminData.ADMIN_ID);
							row.find('#ROW_ADMIN_ABLE_YN').text(adminData.ADMIN_ABLE_YN=='N'?'사용중':'미사용');
							row.find('#ROW_USE_ABLE_TIME').text(adminData.USE_ABLE_TIME);
							if(adminData.ADMIN_ABLE_YN == 'N'){
								row.find('#ROW_STOP_BTN').data(adminData);
								row.find('#ROW_STOP_BTN').text('정지');
								row.find('#ROW_STOP_BTN').on("click",function(){
									var tmpData = $(this).data();
									var r = confirm(tmpData.ADMIN_ID + "를 정지하시겠습니까?");
									if (r == true) {
									  callApi("/ApiWeb/stopAppAdmin",tmpData,function (data){
									  	searchAdmin();  // 리스트 다시 로드함
									  });
									}
								});
							}else if(adminData.ADMIN_ABLE_YN == 'Y'){
								row.find('#ROW_STOP_BTN').text('활성중');
								row.find('#ROW_STOP_BTN').removeClass('btn-success');
							}else{							
								row.find('#ROW_STOP_BTN').remove();
							}
							$('#dataBody').append(row);
						}
						
						// 전체클릭 체크 부분 확인 
						$("._chb").change(function() {
							var chbIsall = true;
						    $('._chb').each(function () {
					           if (!this.checked) {
					           	 chbIsall = false;
					           }
							});
							$("#all_chb").prop("checked", chbIsall);
						});
		});
	}
	
	searchAdmin(); // 초기 로드
	
	// 전체 클릭 
	$("#all_chb").change(function() {
		var chbIsall = this.checked; 
		$('._chb').each(function () {
           $(this).prop("checked", chbIsall);
		});
	});
	
</script>
</html>
