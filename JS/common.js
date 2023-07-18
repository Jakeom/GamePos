function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

// 날짜 리스트 만들기
function getDateRangeData(param1, param2){  //param1은 시작일, param2는 종료일이다.
	var res_day = [];
 	var ss_day = new Date(param1);
   	var ee_day = new Date(param2);    	
  		while(ss_day.getTime() <= ee_day.getTime()){
  			var _mon_ = (ss_day.getMonth()+1);
  			_mon_ = _mon_ < 10 ? '0'+_mon_ : _mon_;
  			var _day_ = ss_day.getDate();
  			_day_ = _day_ < 10 ? '0'+_day_ : _day_;
   			res_day.push(ss_day.getFullYear() + '-' + _mon_ + '-' +  _day_);
   			ss_day.setDate(ss_day.getDate() + 1);
   	}
   	return res_day;
}

// 숫자 리스트 만들기
function getNumRangeData(param1, param2){  //param1은 시작, param2는 종료 숫자이다.
	var res_num = [];
	while(param1 < param2){
		res_num.push(param1);
		param1++;
	}
   	return res_num;
}

//  공통 통신 부분
function callApi(_url, _params, backFunction){
	
	$.ajax({
		type : "POST",
		dataType : "json",
		url : _url,
		data : _params,
		success : function(data) {

			if (data.code == '200') {
				if (backFunction) {
					backFunction(data);
				}
			} else if (data.code == '999') {
				alert('다시 로그인해주세요.');
				location.href = '/logout';
			}
			if (data.message != '') {
				alert(data.message);
			}

		}
	});
	
}
		
$.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
		
$('.datepicker').datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    buttonImage: 'https://skillsofthemodernage.com.au/img/resources/anim-icons/calendar/animat-calendar.gif',
    monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
    monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
    dayNames: ['일', '월', '화', '수', '목', '금', '토'],
    dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
    dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
    showMonthAfterYear: true,
    yearSuffix: '년'
});//datepicker end


