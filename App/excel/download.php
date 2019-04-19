<?php  
   $file_name = 'gongzi.xls';
    $file_sub_path =$_SERVER['DOCUMENT_ROOT'].'/excel/';
    $file_path = $file_sub_path.$file_name;

    if (!file_exists($file_path)){  //判断文件是否存在
        echo "文件不存在";
        exit();
    }
    $fp = fopen($file_path,"r+") or die('打开文件错误');   //下载文件必须要将文件先打开。写入内存
    $file_size = filesize($file_path);
    Header("Content-type:application/octet-stream");
    //按照字节格式返回
    Header("Accept-Ranges:bytes");
    //返回文件大小
    Header("Accept-Length:".$file_size);
    //弹出客户端对话框，对应的文件名
    Header("Content-Disposition:attachment;filename=".$file_name);
    //防止服务器瞬间压力增大，分段读取
    $buffer = 1024;
    while(!feof($fp)){
        $file_data = fread($fp,$buffer);
        echo $file_data;
    }
    fclose($fp);