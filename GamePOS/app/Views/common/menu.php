 <header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#" onClick="return false;">GamePos( <?php echo $_SESSION['user']['COMPANY_NM'] ?> )</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item ">
          <a class="nav-link" href="/user">회원관리 </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/cal">정산</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/machinecal" >기계정산</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/daycal" ><span class="">일일정산</span></a>
        </li>
        <li class="nav-item">
           <a class="nav-link" href="/key" ><span class="">키관리</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/setting" ><span class="">설정</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://dl.dropboxusercontent.com/s/xzjo2499noltxgi/GamePos.apk" download><span class="">앱다운로드</span></a>
        </li>
        
        <?php if($_SESSION['user']['ADMIN_ID'] == 'ADMIN') { ?>
        <li class="nav-item">
          <a class="nav-link" href="/admin" ><span class="">관리자</span></a>
        </li>
        <?php } ?>
      </ul>
      <form class="form-inline mt-2 mt-md-0">
        <a href="/logout" class="btn btn-sm btn-primary my-2 my-sm-0" >로그아웃</a>
      </form>
    </div>
  </nav>
</header>