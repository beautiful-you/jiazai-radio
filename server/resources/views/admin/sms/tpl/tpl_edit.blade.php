@extends('admin.sms.main')


@section('content')
<div>模板内容，必须以带符号【】的签名开头</div>
<form>
    <input id="t_id" type="text" disabled="disabled" value="{{$tpl->id}}">
    <input id="tpl_id" type="text" disabled="disabled" value="{{$tpl->tpl_id}}">
    <input id="tpl_name" type="text" value="{{$tpl->tpl_name}}">
    <textarea id="tpl_content">{{$tpl->tpl_content}}</textarea>   
    <input id="submit" type="button" value="提交">  
</form>  
@endsection


@section('my-js')
<script type="text/javascript">
    $("#submit").click(function(){
        var id = $("#t_id").val();
        var tpl_id = $("#tpl_id").val();
        var tpl_name = $("#tpl_name").val();
        var tpl_content = $("#tpl_content").val();
        if(tpl_name && tpl_content){
             $.ajax({
                 type: "post",
                 url: "/admin/system/sms/edit/tpl",
                 data: {
                    id:id,
                    tpl_id:tpl_id,
                    tpl_name:tpl_name,
                    tpl_content:tpl_content,
                    _token: "{{csrf_token()}}"
                 },
                 dataType: "json",
                 success: function(res){
                    console.log(res);
                    if(res.errcode){
                        alert(res.errmsg);
                    }else{
                        if(res.data.status == 1){
                            window.location.href="/admin/system/sms/tpl";
                        }else{
                            alert(res.data.msg);
                        }
                    }
                 }
             });
        }else{
            alert('请填写相关数据');
        }
    });
</script>
@endsection
