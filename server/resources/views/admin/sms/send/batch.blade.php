@extends('admin.sms.main')


@section('content')
<div>群发短信</div>
<form>
    <input id="title" type="text">
    <select id="select">
        <option value="">请选择</option>
        @foreach($tpls as $tpl)
            <option value="{{$tpl->tpl_content}}">{{$tpl->tpl_name}}</option>
        @endforeach
    </select>
    <label id="prompt"></label> 
    <input id="uploadBtn" type="file" name="">
    <input id="submit" type="button" value="提交">  
</form>  
@endsection


@section('my-js')
<script type="text/javascript">
    $("#select").change(function(){
        var select = $("#select").val();
        $("#prompt").html(select);
    });
</script>

<script type="text/javascript">
    $("#submit").click(function(){
        var title = $("#title").val();
        var tpl_content = $("#select").val();
        if(title && tpl_content){

            if (typeof (FileReader) != "undefined") {

                var formdata = new FormData();
                console.log(formdata);
                var v_this = $("#uploadBtn");
                var fileObj = v_this.get(0).files;
                if(fileObj[0] != undefined){

                    formdata.append("smfile", fileObj[0]);
                    formdata.append("title", title);
                    formdata.append("tpl_content", tpl_content);
                    formdata.append("_token", "{{csrf_token()}}");
                    $.ajax({
                        url:"/admin/system/sms/batch/send",
                        type: "post",
                        dataType: "JSON",
                        async: false,
                        data : formdata,
                        cache : false,
                        contentType : false,
                        processData : false,
                        timeout : 5000,
                        success:function(res){
                            console.log(res);
                            if(res.errcode){
                                alert(res.errmsg);
                            }else{
                                alert(res.msg);
                                window.location.href="/admin/system/sms/batch";
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log(errorThrown)
                        }
                    });
                }else{
                    alert('请上传文件');
                }
            }
        }else{
            alert('请填写相关数据');
        }
    });
</script>
@endsection



