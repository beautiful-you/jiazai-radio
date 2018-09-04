@extends('admin.sms.main')


@section('content')
<div>模板内容，必须以带符号【】的签名开头</div>
<form>
    <input id="tpl_name" type="text">
    <select id="select">
        <option value="">请选择</option>
        @foreach($signs as $sign)
            <option value="{{$sign->sign}}">{{$sign->sign}}</option>
        @endforeach
    </select>
    <textarea id="tpl_content"></textarea>   
    <input id="submit" type="button" value="提交">  
</form>  
@endsection


@section('my-js')
<script type="text/javascript">
    $("#select").change(function(){
        var select = $("#select").val();
        $("#tpl_content").val(select);
    });
</script>

<script type="text/javascript">
    $("#submit").click(function(){
        var tpl_name = $("#tpl_name").val();
        var tpl_content = $("#tpl_content").val();
        if(tpl_name && tpl_content){
             $.ajax({
                 type: "post",
                 url: "/admin/system/sms/add/tpl",
                 data: {
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
