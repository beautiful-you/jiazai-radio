<html>
<head>
<title>句库统计</title>
    <meta charset="utf-8">
</head>
<body>
<style>
    body{background-color:#fff;}

    .title {
        font-size:36px;
    }

    .date {
        font-size:20px;
        color:#0000ff;
    }

    .number {}

</style>
<div class="title">句库统计</div>

<p>截止至{{$nowdate}}，句库已经上线 {{$upday}}天，有{{$zaoju+$shiju}} 条内容。 其中{{$zaoju}}  条拾句，{{$shiju}} 条原创造句。</p>
<p>发表内容最多的人是:</p> &nbsp;&nbsp;{{$nrzd}} 。 <br>
<p>拾句最多的人是:</p>&nbsp;&nbsp; {{$sjzd}}。<br>
<p>造句最多的人是:</p>&nbsp;&nbsp; {{$zjzd}}。 <br>
<p>总计被点赞最多的句子是:</p>&nbsp;&nbsp; {{$zjdzzd->nickname}} 创作的 {{$zjdzzd->content}}。<br>
<p>本月被点赞最多的句子是:</p>&nbsp;&nbsp; {{$bydzzd->nickname}} 创作的 {{$bydzzd->content}}。<br>
<p>本月被点赞次数前十名分别是：</p>
<ol>
    <li>{{$bydzzdqs[0]->content}} &nbsp;&nbsp;{{$bydzzdqs[0]->is_original}} &nbsp;&nbsp;作者:{{$bydzzdqs[0]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[0]->top}}<br>
    </li>
    <li>{{$bydzzdqs[1]->content}} &nbsp;&nbsp;{{$bydzzdqs[1]->is_original}}&nbsp;&nbsp;作者: {{$bydzzdqs[1]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[1]->top}}<br>
    </li>
    <li>{{$bydzzdqs[2]->content}} &nbsp;&nbsp;{{$bydzzdqs[2]->is_original}}&nbsp;&nbsp; 作者:{{$bydzzdqs[2]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[2]->top}}<br>
    </li>
    <li>{{$bydzzdqs[3]->content}} &nbsp;&nbsp;{{$bydzzdqs[3]->is_original}} &nbsp;&nbsp;作者:{{$bydzzdqs[3]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[3]->top}}<br>
    </li>
    <li>{{$bydzzdqs[4]->content}} &nbsp;&nbsp;{{$bydzzdqs[4]->is_original}} &nbsp;&nbsp;作者:{{$bydzzdqs[4]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[4]->top}}<br>
        </li>
    <li>{{$bydzzdqs[5]->content}} &nbsp;&nbsp;{{$bydzzdqs[5]->is_original}}&nbsp;&nbsp; 作者:{{$bydzzdqs[5]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[5]->top}}<br>
    </li>
    <li>{{$bydzzdqs[6]->content}} &nbsp;&nbsp;{{$bydzzdqs[6]->is_original}} &nbsp;&nbsp;作者:{{$bydzzdqs[6]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[6]->top}}<br>
    </li>
    <li>{{$bydzzdqs[7]->content}}&nbsp;&nbsp; {{$bydzzdqs[7]->is_original}} &nbsp;&nbsp;作者:{{$bydzzdqs[7]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[7]->top}}<br>
    </li>
    <li>{{$bydzzdqs[8]->content}} &nbsp;&nbsp;{{$bydzzdqs[8]->is_original}} &nbsp;&nbsp;作者:{{$bydzzdqs[8]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[8]->top}}<br>
    </li>
    <li>{{$bydzzdqs[9]->content}} &nbsp;&nbsp;{{$bydzzdqs[9]->is_original}} &nbsp;&nbsp;作者:{{$bydzzdqs[9]->nickname}}&nbsp;&nbsp;点赞：{{$bydzzdqs[9]->top}}<br>
    </li>
</ol>
<p>大家最爱用的词（出现频率最高）:</p><br>
{{--{{$fenci[0]->content}}&nbsp;&nbsp;{{$fenci[1]->content}}&nbsp;&nbsp;{{$fenci[2]->content}}&nbsp;&nbsp;{{$fenci[3]->content}}&nbsp;&nbsp;{{$fenci[4]->content}}&nbsp;&nbsp;
{{$fenci[5]->content}}&nbsp;&nbsp;{{$fenci[6]->content}}&nbsp;&nbsp;{{$fenci[7]->content}}&nbsp;&nbsp;{{$fenci[8]->content}}&nbsp;&nbsp;{{$fenci[9]->content}}<br>--}}
自己  / 一个 / 人生/  我们 /没有/  世界 /生活 / 努力/  青春

大家最爱在 {{$addcount->time}}之间发表创作
</body>
</html>