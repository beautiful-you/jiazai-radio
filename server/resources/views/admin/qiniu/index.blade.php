<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>七牛目录列表</title>
</head>
<body>
<h1>七牛目录列表</h1>
注意：因为接口限制，每次最多只能删除1000个文件。批量删除建议每次不超过10个目录。

<table>
    <tr>
        <th width="150px" style="text-align: center;">序号</th>
        <th width="150px" style="text-align: center;">选定</th>
        <th width="150px" style="text-align: center;">目录名</th>
        <th width="150px" style="text-align: center;">查看文件</th>
    </tr>
    <form action="deldirlist" method="get">
        @foreach ($dirs as $key=>$v)
        <tr>
            <td style="text-align: center;">{{$key+1}}</td>
            <td style="text-align: center;"><input name="dirlist{{$key}}" type="checkbox" value={{$v}}_ /></td>
            <td style="text-align: center;">{{$v}}</td>
            <td style="text-align: center;"><a  href="listfile?dir={{$v}}">查看</a></td>
        </tr>
        @endforeach
        <input type="submit" value="批量删除"  /><br>
    </form>
    {{--marker:{{$marker}}--}}
    <tr>
   @if ($marker != 'no')
            <td></td>
            <td> <a href="qiniu">首页</a></td>
            <td> <a href="javascript:history.go(-1);">上一页</a></td>
            <td><a href="qiniu?marker={{$marker}}">下一页</a></td>
        @else
    @endif
    </tr>
</table>
</body>
</html>