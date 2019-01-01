<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="layui.css">
    <script src="jquery2.1.4.min.js"></script>
</head>
<body>
<div class="layui-container">
    <div class="layui-row">
        <div class="layui-col-xs6">
            <h2>MYSQL调试器</h2>
            <small>2018年12月31日 v1.0.0</small>
        </div>
        <a href="" onclick="del()" class="layui-btn layui-btn-danger layui-fluid lay">清空日志</a>
        <button onclick="stopAndRefresh()" class="layui-btn layui-btn-warm layui-fluid" id="stop">暂停</button>
        <table class="layui-table" lay-size="sm" id="text">
            <!--数据填充区-->
        </table>
    </div>
</div>
<script>


    //true 循环 false 暂停
    //默认不开始调试
    var flag = false;

    //开始自动刷新
    refresh();

    //停止、刷新
    function stopAndRefresh() {
        if (flag === false) flag = true;
        else flag = false;
    }

    //刷新
    function refresh() {
        var refreshUrl = "mysql_parse_server.php?act=refresh";
        var str = '';
        $.get(refreshUrl, function (res) {
            if (res.code === 0) {
                res.data.forEach(function (value) {
                    if (value.length > 10) {
                        var pre = "<tr><td>";
                        var aft = "</td></tr>"
                        str += pre + value + aft;
                    }

                });
                var text=$("#text");
                var oldData =text.html() ;
                var newData = str;
                if (oldData != newData) {
                    text.html("");
                    text.append(str);
                }

            }
        }, "json")
    }

    //删除日志
    function del() {
        var delUrl = "mysql_parse_server.php?act=del";
        $.get(delUrl, function (res) {
        }, "json")
    }

    //自动刷新
    setInterval(function () {
        var stop = $('#stop');
        if (flag === true) {
            refresh();
            stop.html("调试自动运行中");
        } else {
            stop.html("已停止");
        }
    }, 1000)

</script>
</body>
</html>
