function open_window(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable)
{
				toolbar_str = toolbar ? 'yes' : 'no';
				menubar_str = menubar ? 'yes' : 'no';
				statusbar_str = statusbar ? 'yes' : 'no';
				scrollbar_str = scrollbar ? 'yes' : 'no';
				resizable_str = resizable ? 'yes' : 'no';
				window.open(url, name, 'left='+left+',top='+top+',width='+width+',height='+height+',toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
}

function SearchWindow(winname, ref) 
{
		var window_left = (screen.width-700)/2;
		var window_top = (screen.height-540)/2;   
		open_window(winname, ref, window_left, window_top, 840, 600, 0, 0, 0, 1, 0)
}

//안내문 창을 띄우는 부분
//////////////////////////

//<!-- 새창띄우기 방지-->

function getCookie(Name) {
	var search = Name + "="
	if (document.cookie.length > 0) { // if there are any cookies
		offset = document.cookie.indexOf(search)
		if (offset != -1) { // if cookie exists
			offset += search.length
		
			// set index of beginning of value
			end = document.cookie.indexOf(";", offset)
			
			// set index of end of cookie value
			if (end == -1) end = document.cookie.length

			return unescape(document.cookie.substring(offset, end))
		}
	}
}

//<!--새창띄우기 방지 끝-->
function opwin()
{
	if (getCookie("error") != "done")
	{
	//	open_window('Alert','error/error.html', 30, 50, 500, 400, 0, 0, 0, 0, 0); // 서버점검
	//	open_window('Alert','error/error1.html', 30, 50, 500, 400, 0, 0, 0, 0, 0);  // 병적,보훈시스템 에러
	//	open_window('Alert','error/error2.html', 235, 0, 500, 380, 0, 0, 0, 0, 0);  // 시스템 점검으로 인한 서비스중단
	//	open_window('Alert','error/bohun_error.html', 235, 0, 500, 380, 0, 0, 0, 0, 0); // 보훈시스템 에러
	//	open_window('Alert','error/error3.html', 235, 0, 500, 380, 0, 0, 0, 0, 0);  // mysql 에러
	//	open_window('Alert','error/error4.html', 235, 0, 500, 380, 0, 0, 0, 0, 0);  // 시스템 점검
	//	open_window('Alert','error/notice.html', 235, 0, 300, 320, 0, 0, 0, 0, 0);  // 일반공지사항
	}
}
//////////////////////////

function home()
{
	top.parent.location.href="./index.htm";
}


function na_restore_img_src(name, nsdoc)
{
  var img = eval((navigator.appName.indexOf('Netscape', 0) != -1) ? nsdoc+'.'+name : 'document.all.'+name);
  if (name == '')
    return;
  if (img && img.altsrc) {
    img.src    = img.altsrc;
    img.altsrc = null;
  } 
}

function na_preload_img()
{ 
  var img_list = na_preload_img.arguments;
  if (document.preloadlist == null) 
    document.preloadlist = new Array();
  var top = document.preloadlist.length;
  for (var i=0; i < img_list.length; i++) {
    document.preloadlist[top+i]     = new Image;
    document.preloadlist[top+i].src = img_list[i+1];
  } 
}

function na_change_img_src(name, nsdoc, rpath, preload)
{ 
  var img = eval((navigator.appName.indexOf('Netscape', 0) != -1) ? nsdoc+'.'+name : 'document.all.'+name);
  if (name == '')
    return;
  if (img) {
    img.altsrc = img.src;
    img.src    = rpath;
  } 
}

function logincheck(form){
	if(form.id.value.length == 0){
		alert('계정을 입력해주세요!');
	}else{
		myloginform.submit();
	}

}