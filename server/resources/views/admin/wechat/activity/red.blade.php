<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>


    </head>
    <body>
        <div>特殊的活动红包配置(<span style="color: red;">固定金额的红包:</span>最大值与最小值相等)</div><br/>
        <div style="color: red;">提示:<strong>只有一次操作机会</strong></div>
        <div style="color: red;">红包数量上限:三万</div>
        <br/>
        <form>
            <label>　　ID:　　</label>
            <span id="aid">{{$q}}</span><br/><br/>

            <label>活动名称:　</label>
            <input id="nick_name" type="text" value="{{$red->nick_name or ''}}">
            <br/><br/>

            <label>红包提供方:</label>
            <input id="send_name" type="text" value="{{$red->send_name or ''}}">
            <br/><br/>

            <label>红包提供方:</label>
            <input id="send_activict" type="text" value="{{$red->send_activict or ''}}">
            <br/><br/>

            <label>红包祝福语:</label>
            <input id="send_wishing" type="text" value="{{$red->send_wishing or ''}}">
            <br/><br/>

            <label>红包最小值:</label>
            <input id="send_min" type="number" value="{{$red->send_min or ''}}">
            <label>分</label><br/><br/>

            <label>红包最大值:</label>
            <input id="send_max" type="number" value="{{$red->send_max or ''}}">
            <label>分</label><br/><br/>

            <label>红包总金额:</label>
            <input id="send_total" type="number" value="{{$red->send_total or ''}}">
            <label>分</label><br/><br/>

            <label>红包数量:　</label>
            <input id="count" type="number" value="{{$red->count or ''}}">
            <br/><br/>

            <label>操作者:　　</label> 
            <input id="operator" type="text" value="{{$red->operator or ''}}">
            <br/><br/><br/>
            
            @if(count($red) == 0)
            <label>　　　　　　</label> 
            <input id="submit" type="button" value="提交"> 
            @endif
             
        </form>  
    </body>
</html>
<script type="text/javascript">
    $("#submit").click(function(){
        var aid           = $("#aid").html();
        var nick_name     = $("#nick_name").val();
        var send_name     = $("#send_name").val();
        var send_activict = $("#send_activict").val();
        var send_wishing  = $("#send_wishing").val();
        var send_min      = $("#send_min").val();
        var send_max      = $("#send_max").val();
        var send_total    = $("#send_total").val();
        var count         = $("#count").val();
        var operator      = $("#operator").val();

        if(aid && nick_name && send_name && send_activict && send_wishing && send_min && send_max && send_total && count && operator)
        {
            $(this).unbind("click");
            $.ajax({
                url:"/admin/act/red/add",
                type: "POST",
                dataType: "JSON",
                async: false,
                data : 
                {
                    aid : aid,
                    nick_name : nick_name,
                    send_name : send_name,
                    send_activict : send_activict,
                    send_wishing : send_wishing,
                    send_min : send_min,
                    send_max : send_max,
                    send_total : send_total,
                    count : count,
                    operator : operator,
                    _token : "{{csrf_token()}}"
                },
                cache : false,
                success:function(res){
                    console.log(res);
                    if(res.errcode){
                        alert(res.errmsg);
                        window.location.href="/admin/wechat/act/red?aid="+aid;
                    }else{
                        alert(res.data.msg);
                        window.location.href="/admin/wechat/activity";
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown)
                }
            });

        }else{
            alert('请填写相关信息');
        }
    });
</script>