<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>Signin Template · Bootstrap</title>

    <!-- Bootstrap core CSS -->
    
    <?=link_tag('CSS/bootstrap.min.css')?>
    <?=link_tag('CSS/signin.css')?>
    
    </style>
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <form class="form-signin">
  <img class="mb-4" src="/IMAGE/icon-gamepos.svg" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">GamePos</h1>
  <label for="adminId" class="sr-only">아이디</label>
  <input type="text" id="adminId" class="form-control" placeholder="아이디" required autofocus>
  <label for="adminPw" class="sr-only">비밀번호</label>
  <input type="password" id="adminPw" class="form-control" placeholder="비밀번호" required>
  <div class="checkbox mb-3">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit">로그인</button>
  <p class="mt-5 mb-3 text-muted">&copy; 2021</p>
</form>
</body>
</html>
