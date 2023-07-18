  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <?=script_tag('JS/bootstrap.bundle.min.js') ?>
  <?=script_tag('JS/common.js?20203-') ?>
  <script>
  	var mainTitle = $('#mainTitle').text();
  	var activieLi = null;
  	if(mainTitle == '회원관리'){
  		activieLi = $($('.navbar-nav li')[0]);
  	}else if(mainTitle == '정산'){
  		activieLi = $($('.navbar-nav li')[1]);
  	}else if(mainTitle == '기계정산'){
  		activieLi = $($('.navbar-nav li')[2]);
  	}else if(mainTitle == '일일정산'){
  		activieLi = $($('.navbar-nav li')[3]);
  	}else if(mainTitle == '키관리'){
  		activieLi = $($('.navbar-nav li')[4]);
  	}else if(mainTitle == '설정'){
  		activieLi = $($('.navbar-nav li')[5]);
  	}
  	if(activieLi != null){
  		activieLi.addClass('active');
  	}
  </script>