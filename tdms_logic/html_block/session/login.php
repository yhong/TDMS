<script>
	function form_chk(){
		if(document.login.user_id.value.length == 0){
			alert('���̵� �Է����ּ���');
			return false;
		}else if(document.login.user_password.value.length == 0){
			alert('��й�ȣ�� �Է����ּ���');
			return false;
		}else{
			return true;
		}
	}
</script>

<form id="login" name="login" action="/Login" method="POST" onsubmit="return form_chk();">
	<fieldset>
		<legend>�α���</legend>
		<input type="text" id="loginID" name="user_id" title="���̵� �Է�" onmouseover="this.focus();" />

		<input type="password" id="loginPasswd" name="user_password" class="bg" title="��й�ȣ �Է�"  onmouseover="this.focus();"/>
		<input type="submit" title="�α���" value="�α���"/>
	</fieldset>
</form>