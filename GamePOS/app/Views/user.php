<!doctype html>
<html lang="en">
	
	<?= $this->include('common/head') ?>

  <body>
   <?= $this->include('common/menu') ?>

<main role="main">

  <div class="container marketing">
	<h4 class="mt-4" id="mainTitle">회원관리</h4>
	<div class="row mb-3">
			<div class="col-md-10 themed-grid-col">
				<div class="pb-3">

				</div>
				<div class="row">
					<div class="col-md-2 themed-grid-col">
						<div class="form-group">
							<label for="SEARCH_KEY">정렬</label>
							<select id="SORT" class="form-control">
									<option value="CREATE_TIME">가입일순</option>
									<option value="LAST_POINT">포인트</option>
									<option value="USER_NO">회원번호</option>
								</select>
						</div>
					</div>
					<div class="col-md-3 themed-grid-col">
						<div class="form-group">
							<label for="SEARCH_KEY">회원명/4자리전번</label>
							<input type="text" class="form-control" id="SEARCH_KEY" name="SEARCH_KEY"  aria-describedby="name" placeholder="회원정보">
						</div>
					</div>
					<div class="col-md-5 themed-grid-col">
						<div class="form-group">
							<br>
							<h5> 총 회원수 : <span id="USER_ALL_CNT"></span> / 누적 포인트 : <span id="ALL_SUM"></span></h5>
							<br>
						</div>
					</div>	
				</div>
				</div>
				<div class="col-md-2 themed-grid-col">
					<a href="#" class="btn btn-md  btn-outline-dark my-2 my-sm-0" onclick="clickSearchBtn();return false;">검색</a>
				</div>
			
	</div>
	
	<div class="row featurette" style="min-height: 500px;">
    	<table class="table">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">회원명</th>
					<th scope="col">전화번호</th>
					<th scope="col">포인트</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody id="dataBody">
				<tr id="dataRow">
					<th scope="row" id="ROW_USER_NO"></th>
					<td id="ROW_USER_NM"></td>
					<td id="ROW_PHONE_NO"></td>
					<td id="ROW_LAST_POINT"></td>
					<td id="ROW_DATA_FIELD">
						<a class="btn btn-sm  btn-success btnSearchUser" style="width: 58px ;font-size: 10px ;">내역보기</a> 
						<!-- <a class="btn btn-sm  btn-outline-dark u_t_btn" style="width: 58px ;font-size: 10px ;">적립차감</a>  -->
						<a class="btn btn-sm  u_t_btn btnAction"   style="width: 58px ;font-size: 10px ;"></a>
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
	var dataRow = $('#dataRow').detach().show().attr('id','');
	var currentPage = 0;
	var currentRowCnt = 30;
	var rtnParams = null;
	
	function clickSearchBtn(){
		currentPage = 0;
		var sendParam = {
			USER_NO : ''
			,SEARCH_KEY : $('#SEARCH_KEY').val()
			,PAGE : currentPage
			,ROW_CNT : currentRowCnt
			,SORT : $('#SORT').val()
		}
		$('#dataBody').empty();

		searchData(sendParam);
	}
	
	function searchData(sendParam) {
			callApi("/ApiWeb/searchUser",sendParam,function (data){
	
					if (data.code == '200') {
						// 중복처리일 경우 페이지 호출하지 않음
						if (currentPage == data.datas.PARAMS.PAGE) {
							return;
						}
						// 넘어온 파라메터값
						rtnParams = data.datas.PARAMS;
						// 다음페이지 설정
						currentPage = rtnParams.PAGE;
						if(currentPage == 1){
				            if(data.datas.POINT_SUM != null){
				            	$('#USER_ALL_CNT').html(addCommas(data.datas.POINT_SUM.USER_ALL_CNT));
				            	$('#ALL_SUM').html(addCommas(data.datas.POINT_SUM.TOTAL));
				            	
				            }
						}
						// 데이타 출력
						var datas = data.datas;
						for (var i = 0; i < datas.USER_LIST.length; i++) {
							var userData = datas.USER_LIST[i];
							var row = dataRow.clone();
							
							row.find('#ROW_USER_NO').text(userData.USER_NO);
							row.find('#ROW_USER_NM').text(userData.USER_NM);
							row.find('#ROW_PHONE_NO').text(userData.PHONE_NO);
							row.find('#ROW_LAST_POINT').text(addCommas(userData.LAST_POINT));
							row.find('#ROW_DATA_FIELD').data(userData);
							
							 
							if(userData.DEL_FLG == ''){
								row.find('.btnAction').text("사용정지");
								row.find('.btnAction').addClass('btn-danger');
							}else{
								row.find('.btnAction').text("사용복귀");
								row.find('.btnAction').addClass('btn-info');
							}
							
							$('#dataBody').append(row);
						}
					}
					
					$('.btnSearchUser').on("click",function(){
						var _data =$(this).closest('#ROW_DATA_FIELD').data();
						location.href="/cal?USER_NO="+_data.USER_NO;
					});
					
					$('.btnAction').on("click",function(){
						var _this  = $(this);
						var _data =$(this).closest('#ROW_DATA_FIELD').data();
						
						var txt;
						var r = confirm((_data.DEL_FLG==''?'사용정지':'사용복귀')+"를 처리하시겠습니까?");
						if (r == true) {
							callApi("/ApiWeb/updateUser",_data,function (data){
								if (data.code == '200') {
									alert('변경처리 되었습니다.');
									if(_data.DEL_FLG == ''){
										_data.DEL_FLG = 'Y';
										_this.text("사용복귀");
										_this.removeClass('btn-danger');
										_this.addClass('btn-info');
									}else{
										_data.DEL_FLG = '';
										_this.text("사용정지");
										_this.removeClass('btn-info');
									    _this.addClass('btn-danger');
									}
								}
							});
						}
					});
			});
		}
		
		clickSearchBtn();
		
		$(window).scroll(function() {
		if ($(window).scrollTop() + $(window).height() ==  $(document).height()) {
			searchData(rtnParams);
		}
	}); 
</script>
</html>
