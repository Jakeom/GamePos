<!doctype html>
<html lang="en">
	
	<?= $this->include('common/head') ?>

  <body>
   <?= $this->include('common/menu') ?>

<main role="main">

  <div class="container marketing">
	<!-- <h4 class="mt-4" id="mainTitle">회사등록</h4>
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
	</form> -->
	
	<h4 class="mt-4" id="mainTitle">회원등록</h4>
	<div class="row">
		<form id="adminForm">
			<div class="form-group row">
				
				<div class="col-3">
					<label for="ADMIN_ID">회사코드</label>
					<select class="form-control" id="COMPANY_NO" name="COMPANY_NO" >
						<option>선택</option>
						<option value="0">다저스게임장</option>
					</select>
				</div>
				<div class="col-3">
					<label for="ADMIN_ID">관리자 번호</label>
					<input type="number" class="form-control" id="ADMIN_NO" name="ADMIN_NO"  aria-describedby="number" placeholder="수정시 입력">
				</div>
				<div class="col-3">
					<label for="WEB_ID_YN">웹관리자 YN</label>
					<select class="form-control" id="WEB_ID_YN" name="WEB_ID_YN" >
						<option>선택</option>
						<option value="Y">웹관리자</option>
						<option value="N">App유저</option>
					</select>
				</div>
				<div class="col-3">
					<label for="WEB_ID_YN">아이디 활성화</label>
					<select class="form-control" id="ADMIN_ABLE_YN" name="ADMIN_ABLE_YN" >
						<option>선택</option>
						<option value="N">활성화</option>
						<option value="Y">사용중</option>
					</select>
				</div>

			 </div>
			 <div class="form-group row">
				<div class="col-4">
					<label for="ADMIN_ID">관리자 아이디</label>
					<input type="text" class="form-control" id="ADMIN_ID" name="ADMIN_ID" maxlength="20"  aria-describedby="number" placeholder="관리자 아이디">
				</div>
				<div class="col-3">
					<label for="ADMIN_PASSWORD">비밀번호</label>
					<input type="password" class="form-control" id="ADMIN_PASSWORD" maxlength="20" name="ADMIN_PASSWORD"  placeholder="비밀번호">
				</div>
				<div class="col-3">
					<label for="USE_ABLE_TIME">사용가능 일</label>
					<input type="date" class="form-control" id="USE_ABLE_TIME" name="USE_ABLE_TIME">
				</div>
				
				<div class="col-2 ">
					<div class="col text-center">
						<button type="button" class="btn btn-primary btn-lg" onclick="save();return false;">저장</button>
					</div>
				</div>
			 </div>	
			
		</form>
	</div>
	<div class="line"></div>
	<h4 class="mt-4" id="mainTitle">회원검색</h4>
	<div class="row mb-3">
	    <div class="col-md-10 themed-grid-col">
	      <div class="pb-3">
	      </div>
	      <div class="row">
	        <div class="col-md-4 themed-grid-col">
	        	<div class="form-group">
				    <label for="ST_DATE">업체</label>
				    	<select id="_COMPANY_NO"   class="form-control ">
				    		<option>선택</option>
				    		<option value="0">다저스게임장</option>
				    	</select>
				 </div>
					
	        </div>
	        <div class="col-md-3 themed-grid-col">
						<div class="form-group">
							<label for="ADMIN_ID">관리자 아이디</label>
							<input type="text" class="form-control" id="_ADMIN_ID" name="_ADMIN_ID"  aria-describedby="number" placeholder="관리자 아이디">
						</div>
					</div>
	      </div>
	      
	    </div>
    	<div class="col-md-2 themed-grid-col"><a href="#" class="btn btn-md  btn-outline-dark my-2 my-sm-0" style="margin-top: 20px !important;" onclick="searchAdmin();return false;">검색</a></div>
  </div>
	
	<div class="row featurette" style="min-height: 500px;">
    	<table class="table">
			<thead>
				<tr>
					<th scope="col">관리자번호</th>
					<th scope="col">업체정보</th>
					<th scope="col">관리자ID</th>
					<th scope="col">활성구분</th>
					<th scope="col">사용가능날짜</th>
					<th scope="col">웹관리자여부</th>
					<th scope="col">토큰시간</th>
					<th scope="col">등록일</th>
				</tr>
			</thead>
			<tbody id="dataBody">
				<tr id="dataRow" class="dataRow">
					<td id="r1"></td>
					<td id="r2"></td>
					<td id="r3"></td>
					<td id="r4"></td>
					<td id="r5"></td>
					<td id="r6"></td>
					<td id="r7"></td>
					<td id="r8"></td>
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
	
	function save(){
		
		if($('#COMPANY_NO').val() == ''||$('#ADMIN_ID').val() == ''||$('#USE_ABLE_TIME').val() == ''||$('#WEB_ID_YN').val() == ''||$('#ADMIN_ABLE_YN').val() == ''){
			alert("입력값을 확인해주세요");
			return;
		}
		var r = confirm("저장 하시겠습니까?");
		if (r == true) {
			//var sendParam = $( '#adminForm' ).serializeObject();
			var sendParam = {
				COMPANY_NO : $('#COMPANY_NO').val()
				,ADMIN_ID : $('#ADMIN_ID').val()
				,ADMIN_PASSWORD : $('#ADMIN_PASSWORD').val()
				,USE_ABLE_TIME : $('#USE_ABLE_TIME').val()
				,WEB_ID_YN : $('#WEB_ID_YN').val()
				,ADMIN_NO : $('#ADMIN_NO').val()
				,ADMIN_ABLE_YN : $('#ADMIN_ABLE_YN').val()
			}
			
		  callApi("/apiWeb/insertAdmin",sendParam,function (data){
			  	if (data.code == '200') {
			  		location.reload();		
			  	}
				
			});
		}
	}
	var dataRow = $('#dataRow').detach().show().attr('id','');
	
	function searchAdmin(){
		$('#dataBody').empty();
		callApi("/ApiWeb/selectAdmin",{COMPANY_NO : $('#_COMPANY_NO').val(),ADMIN_ID : $('#_ADMIN_ID').val()},function (data){
			// 데이타 출력
					var datas = data.datas;
					for (var i = 0; i < datas.ADMIN_LIST.length; i++) {
						var adminData = datas.ADMIN_LIST[i];
						var row = dataRow.clone();
						row.find('#r1').text(adminData.ADMIN_NO);
						row.find('#r2').text(adminData.COMPANY_NM);
						row.find('#r3').text(adminData.ADMIN_ID);
						row.find('#r4').text(adminData.ADMIN_ABLE_YN);
						row.find('#r5').text(adminData.USE_ABLE_TIME);
						row.find('#r6').text(adminData.WEB_ID_YN);
						row.find('#r7').text(adminData.GTOKEN_TIME);
						row.find('#r8').text(adminData.CREATE_TIME);
						row.data(adminData);
						$('#dataBody').append(row);
					}
					
					$('.dataRow').on("click",function(){
						var _data = $(this).data();
						
						var r = confirm("등록폼에 데이터를 넣겠습니까?");
						if (r == true) {
							$('#COMPANY_NO').val(_data.COMPANY_NO);
							$('#ADMIN_ID').val(_data.ADMIN_ID);
							$('#ADMIN_PASSWORD').val('');
							$('#ADMIN_ABLE_YN').val(_data.ADMIN_ABLE_YN);
							$('#USE_ABLE_TIME').val(_data.USE_ABLE_TIME.substr(0,10));
							$('#WEB_ID_YN').val(_data.WEB_ID_YN);
							$('#ADMIN_NO').val(_data.ADMIN_NO);
							window.scrollTo({top:0, left:0, behavior:'auto'});
						}
					});
		});
	}
	
	searchAdmin();

	
</script>
</html>
