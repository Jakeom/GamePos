<!doctype html>
<html lang="en">
  <head>
    	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>GamePos</title>
		<link rel="shortcut icon" href="/IMAGE/ico.ico">
		<?=link_tag('CSS/bootstrap.min.css')?>
		<?=link_tag('CSS/carousel.css') ?>
        <?=link_tag('CSS/datepicker.css') ?>
        <?=link_tag('CSS/style.css') ?>
		
         <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
         
  </head>
  <body>
    <header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#" onClick="return false;">GamePos</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item ">
          <a class="nav-link" href="#" onClick="return false;">회원관리 </a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#">정산</a>
          
        </li>
        <li class="nav-item">
        	<!-- <a class="nav-link disabled" href="#" onClick="return false;">정산</a> -->
          <a class="nav-link" href="#" onClick="return false;">기계정산</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onClick="return false;"><span class="">일일정산</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onClick="return false;"><span class="">키관리</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onClick="return false;"><span class="">설정</span></a>
        </li>
      </ul>
      <form class="form-inline mt-2 mt-md-0">
        <a href="/logout" class="btn btn-sm btn-primary my-2 my-sm-0" >로그아웃</a>
      </form>
    </div>
  </nav>
</header>

<main role="main">

  


  <!-- Marketing messaging and featurettes
  ================================================== -->
  <!-- Wrap the rest of the page in another container to center all the content. -->

  <div class="container marketing">
	<h4 class="mt-4">정산</h4>
	<div class="row mb-3">
    <div class="col-md-10 themed-grid-col">
      <div class="pb-3">
        
      </div>
      <div class="row">
        <div class="col-md-5 themed-grid-col">
        	<div class="form-group">
			    <label for="ST_DATE">날짜(시작~종료)</label>
			    <div class="row">
			    	<div class="col-md-5" ><input type="text" id="ST_DATE" name="ST_DATE" class="form-control datepicker" value="<?= date("Y-m-d") ?>" onkeypress="return false;"/></div>
			    	<div class="col-md-5" ><input type="text" id="ED_DATE" name="ED_DATE" class="form-control datepicker" value="<?= date("Y-m-d",strtotime("+1 days")) ?>"onkeypress="return false;"/></div>
			    </div>
			 </div>
        </div>
        
        
         
        <div class="col-md-2 themed-grid-col">
        	<div class="form-group">
			    <label for="MACHINE_NO">기계번호</label>
			    <input type="number" class="form-control" id="MACHINE_NO" name="MACHINE_NO"  aria-describedby="number" placeholder="기계번호">
			 </div>
        </div>
        <div class="col-md-2 themed-grid-col">
        	<div class="form-group">
			    <label for="ADMIN_ID">관리자아이디</label>
			    <input type="hidden" id="USER_NO" name="USER_NO"  >
			    <input type="text" class="form-control" id="ADMIN_ID" name="ADMIN_ID"  aria-describedby="name" placeholder="관리자 아이디 입력">
			 </div>
        </div>
        <div class="col-md-3 themed-grid-col">
        	<div class="form-group">
			    <label for="SEARCH_KEY">회원명/4자리전번</label>
			    <input type="text" class="form-control" id="SEARCH_KEY" name="SEARCH_KEY"  aria-describedby="name" placeholder="회원정보">
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
						<th class="table_border" scope="col">적립<span id="PLUS_POINT_CNT"></span></th>
						<th class="table_border" scope="col">차감<span id="MINUS_POINT_CNT"></span></th>
						<th class="table_border" scope="col">차이</th>
						<th class="table_border" scope="col">누적 포인트</th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="table_border color_blue" id="PLUS_POINT">0</td>
						<td class="table_border color_red" id="MINUS_POINT">0</td>
						<td class="table_border" id="TOTAL">0</td>
						<td class="table_border" id="ALL_SUM">0</td>
					</tr>
					
				</tbody>
			</table>
     
    </div>
    <div class="row featurette" style="min-height: 500px;">
    	<table class="table">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">시간</th>
					<th scope="col">등록관리자</th>
					<th scope="col">회원명</th>
					<th scope="col">전화번호</th>
					<th scope="col">기계번호</th>
					<th scope="col">구분</th>
					<th scope="col">포인트내역</th>
					<th scope="col">최종 포인트</th>
				</tr>
			</thead>
			<tbody id="dataBody">
				<tr id="dataRow">
					<th scope="row" id="ROW_POINT_NO"></th>
					<td id="ROW_CREATE_TIME"></td>
					<td id="ROW_ADMIN_ID"></td>
					<td id="ROW_USER_NM"></td>
					<td id="ROW_PHONE_NO"></td>
					<td id="ROW_MACHINE_NO"></td>
					<td id="ROW_POINT_TP"></td>
					<td id="ROW_POINT"></td>
					<td id="ROW_LAST_POINT"></td>
				</tr>
				
			</tbody>
		</table>
	</div>
    <!-- /END THE FEATURETTES -->
<div class="row featurette">
		<p id="dataF"></p>
	</div>
  </div><!-- /.container -->


  <!-- FOOTER -->
  <footer class="container">
    <p class="float-right"><a href="#">위로</a></p>
    <p>&copy; 2021 GamePos. &middot; <a href="#">정책</a> &middot; <a href="#">약관</a></p>
  </footer>
</main>

<div id="deleteNoteModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" data-dismiss="modal" aria-label="Close">&times;</span>
    <p id="deleteNote"></p>
  </div>

</div>

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <?=script_tag('JS/bootstrap.bundle.min.js') ?>
  <?=script_tag('JS/common.js') ?>

<script >
		
	var dataRow = $('#dataRow').detach().show().attr('id','');
	var currentPage = 0;
	var currentRowCnt = 30;
	var rtnParams = null;
	
	function clickSearchBtn(){
		currentPage = 0;
		var sendParam = {
			USER_NO : ''
			,ADMIN_ID : $('#ADMIN_ID').val()
			,MACHINE_NO : $('#MACHINE_NO').val()
			,ST_DATE : $('#ST_DATE').val()
			,ED_DATE : $('#ED_DATE').val()
			,SEARCH_KEY : $('#SEARCH_KEY').val()
			,PAGE : currentPage
			,ROW_CNT : currentRowCnt
		}
		$('#dataBody').empty();
				// 총합 초기화
		$('#PLUS_POINT_CNT').html("");
    	$('#MINUS_POINT_CNT').html("");
    	$('#PLUS_POINT').html("0");
        $('#MINUS_POINT').html("0");
        $('#TOTAL').html("0");
        $('#ALL_SUM').html("0");
		searchData(sendParam);
	}

			function searchData(sendParam) {
				$.ajax({
					type : "POST",
					dataType : "json",
					url : "/ApiWeb",
					data : sendParam,
					success : function(data) {

						//$('#dataF').html(JSON.stringify(data));

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
					            	$('#PLUS_POINT_CNT').html("("+data.datas.POINT_SUM[0].PLUS_POINT_CNT+")");
					            	$('#MINUS_POINT_CNT').html("("+data.datas.POINT_SUM[0].MINUS_POINT_CNT+")");
					            	$('#PLUS_POINT').html(addCommas(data.datas.POINT_SUM[0].PLUS_POINT));
						            $('#MINUS_POINT').html(addCommas(data.datas.POINT_SUM[0].MINUS_POINT));
						            $('#TOTAL').html(addCommas(data.datas.POINT_SUM[0].TOTAL));
						            $('#ALL_SUM').html(addCommas(data.datas.POINT_SUM[0].ALL_SUM));
					            }
							}
							// 데이타 출력
							var datas = data.datas;
							for (var i = 0; i < datas.POINT_LIST.length; i++) {
								var pointData = datas.POINT_LIST[i];
								var row = dataRow.clone();
								if(pointData.DELETE_YN == 'Y'){
									pointData.ROW_POINT_TP ='삭제';
									row.find('#ROW_POINT_TP').addClass('color_red');
									row.find('#ROW_POINT_TP').data(pointData);
									row.find('#ROW_POINT_TP').on("click",function(){
										var _tmp = $(this).data();
										$('#deleteNote').html("<br> 삭제시간 : " + _tmp.CREATE_TIME +"<br>-------------------------------------<br>"+_tmp.DELETE_NOTE);
										$('#deleteNoteModal').modal();
									});
							    }else if(pointData.ROW_POINT_TP =='1'){
									pointData.ROW_POINT_TP ='서비스';
								}else if(pointData.ROW_POINT_TP =='2'){
									pointData.ROW_POINT_TP ='보상';
								}else{
									pointData.ROW_POINT_TP ='일반';
								}
								
								row.find('#ROW_POINT_NO').text(pointData.POINT_NO);
								row.find('#ROW_CREATE_TIME').text(pointData.CREATE_TIME);
								row.find('#ROW_ADMIN_ID').text(pointData.ADMIN_ID);
								row.find('#ROW_USER_NM').text(pointData.USER_NM);
								row.find('#ROW_PHONE_NO').text(pointData.PHONE_NO);
								row.find('#ROW_MACHINE_NO').text(pointData.MACHINE_NO);
								row.find('#ROW_POINT_TP').text(pointData.ROW_POINT_TP);
								row.find('#ROW_POINT').text(addCommas(pointData.POINT));
								row.find('#ROW_LAST_POINT').text(addCommas(pointData.LAST_POINT));

								if(pointData.POINT < 0){
									row.find('#ROW_POINT').addClass('color_red');
								}else{
									row.find('#ROW_POINT').addClass('color_blue');
								}
								
								if(pointData.DELETE_YN == 'Y'){
									row.attr('style','background: #ffdfdf;');
								}
								
								$('#dataBody').append(row);
							}
						}
						if (data.message != '') {
							alert(data.message);
						}

					}
				});
			}


			$(window).scroll(function() {
				if ($(window).scrollTop() + $(window).height() == $(document).height()) {
					searchData(rtnParams);
				}
			}); 
</script>
</html>
