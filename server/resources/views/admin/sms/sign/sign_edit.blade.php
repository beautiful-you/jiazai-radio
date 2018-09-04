@extends('admin.sms.main')


@section('content')
<div>不能包含【】,不能是“云片网”</div>
<form>
	<input id="sign_id" type="text" disabled="disabled" value="{{$sign->id}}">
    <input id="sign" type="text" value="{{$sign->sign}}"> 
    <input id="submit" type="button" value="确认123456489">  
</form> 
@endsection


@section('my-js')
<script type="text/javascript">
	$("#submit").click(function(){
		var sign = $("#sign").val();
		var id = $("#sign_id").val();
		if(sign){
			if(sign != '云片网'){
		         $.ajax({
		             type: "post",
		             url: "/admin/system/sms/edit/sign",
		             data: {
		             	id:id,
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