@extends('admin.sms.main')


@section('content')
<div>不能包含【】,不能是“云片网”</div>
<form>
    <input id="sign" type="text"> 
    <input id="submit" type="button" value="确认">  
</form> 
@endsection


@section('my-js')
<script type="text/javascript">
	$("#submit").click(function(){
		var sign = $("#sign").val();
		if(sign){
			if(sign != '云片网'){
		         $.ajax({
		             type: "post",
		             url: "/admin/system/sms/add/sign",
		             data: {
		             	sign:sign,
		             	_token: "{{csrf_token()}}"
		             },
		             dataType: "json",
		             success: function(res){
		             	console.log(res);
		             	if(res.errcode){
		             		alert(res.errmsg);
		             	}else{
		             		if(res.data.status == 1){
		             			window.location.href="/admin/system/sms/sign";
		             		}else{
		             			alert(res.data.msg);
		             		}
		             	}
		             }
		         });
			}else{
				alert('不能是云片网');
			}
		}else{
			alert('请填写签名');
		}
	});
</script>
@endsection