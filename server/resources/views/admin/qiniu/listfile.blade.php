<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>七牛目录内文件列表</title>
</head>
@foreach ($files as $v)
    <p>{{$v['key']}}</p>
@endforeach
<body>
</body>