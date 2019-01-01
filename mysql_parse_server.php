<?php
/**
 * Created by PhpStorm.
 * User: Sunwu
 * Date: 2018/12/30
 * Time: 22:40
 * MYSQL调试工具
 * 配置说明
 * MYSQL配置文件my.ini中加入：
    [mysqld]
    general_log=ON
    general_log_file=C:\phpStudy\debug.log
 * 重启mysql 看看 mysql.log 文件有没有生成mysql.log 文件名如果在 mysql.ini 中已经使用 请换个名字
 * 2.配置你的 mysql_query.php 访问路径 我这里是  $url_path='http://127.0.0.1:8080/mysql_query.php';
 */


header('Content-type:text/json');
//配置
$config = [
    "LOG_FILE" => "C:\phpStudy\debug.log"//全路径
];

$filename = $config['LOG_FILE'];
if (file_exists($filename))
    $fp = fopen($filename, "r");
else {
    $res = response("日志文件不存在", -1);
    die($res);
}


$act = isset($_GET['act']) ? $_GET['act'] : '';
switch ($act) {
    case "del":
        {
            $fp = @fopen($filename, "w+"); //打开文件指针，创建文件
            if (!is_writable($filename)) {

                die(response(-1, "log文件:" . $filename . "不可写，请检查！"));
            } else {
                fwrite($fp, "\r\n");
            }
            @fclose($fp);  //关闭指针
            echo response(0, "清除成功");
        }
        break;
    default:
        {
            $res = [];
            while (!feof($fp)) {
                $tempstr = fgets($fp);
                //输出链接信息、执行信息
                if (preg_match("/Close*/", $tempstr) || preg_match("/Prepare*/", $tempstr)) continue;
                $res[] = $tempstr;
            }
            if (is_array($res))
                array_reverse($res);
            echo response(0, "获取数据成功", $res);
        }
        break;


}

function getFileName($fileName = "")
{
    $pos = strrpos($fileName, "\\");
    return substr($fileName, $pos + 1);
}

function response($code = 0, $errmsg = "成功", $data = [])
{
    //返回信息
    $returnArr = [
        "code" => $code,
        "msg" => $errmsg,
        "data" => $data
    ];
    return json_encode($returnArr);
}

?>
