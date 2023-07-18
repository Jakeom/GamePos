<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>GamePos</title>
		<link rel="shortcut icon" href="/IMAGE/ico.ico">
		<?=link_tag('CSS/bootstrap.min.css')?>
		<?=link_tag('CSS/signin.css') ?>

	</head>
	<body class="text-center">
		<form class="form-signin" method="post"  action="<?= site_url('/login') ?>">
			
			<img class="mb-4" src="/IMAGE/icon-gamepos.svg" alt="" width="72" height="72">
			<h1 class="h3 mb-3 font-weight-normal">GamePos</h1>
			
			<label for="adminId" class="sr-only">아이디</label>
			<input type="text" id="adminId" name="adminId" class="form-control" placeholder="아이디" value="<?php echo($emsg['adminId']); ?>" required autofocus>
			<label for="adminPw" class="sr-only">비밀번호</label>
			<input type="password" id="adminPw" name="adminPw"  class="form-control" placeholder="비밀번호" required>
			<div class="checkbox mb-3">

                <p>
                	<span style='color:red;font-size:20px;'><?php echo($emsg['msg']); ?></span>
				</p>
    
				
				<label>
					<input type="checkbox" id="idSaveCheck" value="remember-me">
					아이디 기억하기 </label>
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit">
				로그인
			</button>
			<p class="mt-5 mb-3 text-muted">
				&copy; 2021
			</p>
		</form>
	</body>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script>
		var key = getCookie("key");
		$("#adminId").val(key);

		if ($("#adminId").val() != "") {// 그 전에 ID를 저장해서 처음 페이지 로딩 시, 입력 칸에 저장된 ID가 표시된 상태라면,
			$("#idSaveCheck").attr("checked", true);
			// ID 저장하기를 체크 상태로 두기.
		}

		$("#idSaveCheck").change(function() {// 체크박스에 변화가 있다면,
			if ($("#idSaveCheck").is(":checked")) {// ID 저장하기 체크했을 때,
				setCookie("key", $("#adminId").val(), 7);
				// 7일 동안 쿠키 보관
			} else {// ID 저장하기 체크 해제 시,
				deleteCookie("key");
			}
		});

		$("#adminId").keyup(function() {// ID 입력 칸에 ID를 입력할 때,
			if ($("#idSaveCheck").is(":checked")) {// ID 저장하기를 체크한 상태라면,
				setCookie("key", $("#adminId").val(), 7);
				// 7일 동안 쿠키 보관
			}
		});
		function setCookie(cookieName, value, exdays) {
			var exdate = new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var cookieValue = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toGMTString());
			document.cookie = cookieName + "=" + cookieValue;
		}

		function deleteCookie(cookieName) {
			var expireDate = new Date();
			expireDate.setDate(expireDate.getDate() - 1);
			document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString();
		}

		function getCookie(cookieName) {
			cookieName = cookieName + '=';
			var cookieData = document.cookie;
			var start = cookieData.indexOf(cookieName);
			var cookieValue = '';
			if (start != -1) {
				start += cookieName.length;
				var end = cookieData.indexOf(';', start);
				if (end == -1)
					end = cookieData.length;
				cookieValue = cookieData.substring(start, end);
			}
			return unescape(cookieValue);
		}
	</script>
</html>
