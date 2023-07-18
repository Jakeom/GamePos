<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>로그인샘플</title>
</head>
<body>
 
<?php if ($this->validation->error_string):?>
<?=$this->validation->error_string; ?>
<?php endif;?>
<p>회원로그인</p>
<?=form_open('login_sample/login')?>
<dl>
<dt>아이디</dt>
<dd><?=$this->validation->username_error?><input type="text" name="username" value="<?=$this->validation->username?>" /></dd>
<dt>패스워드</dt>
<dd><?=$this->validation->password_error?><input type="text" name="password" value="<?=$this->validation->password?>" /></dd>
</dl>
<input type="submit" value="로그인" />
<?=form_close()?>
 
</body>
</html>