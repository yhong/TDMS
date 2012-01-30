<script>
	function form_chk(){
		if(document.login.user_id.value.length == 0){
			alert('아이디를 입력해주세요');
			return false;
		}else if(document.login.user_password.value.length == 0){
			alert('비밀번호를 입력해주세요');
			return false;
		}else{
			return true;
		}
	}
</script>

<form id="login" name="login" action="/Login" method="POST" onsubmit="return form_chk();">
	<fieldset>
		<legend>로그인</legend>
		<input type="text" id="loginID" name="user_id" title="아이디 입력" onmouseover="this.focus();" />

		<input type="password" id="loginPasswd" name="user_password" class="bg" title="비밀번호 입력"  onmouseover="this.focus();"/>
		<input type="submit" title="로그인" value="로그인"/>
	</fieldset>
</form>