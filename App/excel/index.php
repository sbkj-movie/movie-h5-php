<?php 
session_start();
if(isset($_SESSION['think']['admin'])&&!empty($_SESSION['think']['admin'])){
	$user=$_SESSION['think']['admin'];
	if($user['jid']==1){
		$link = @mysqli_connect('127.0.0.1','xygzmx','Vpmy37xZnbZcpNZK','xy_gongzimingxi');
		//设置字符集
	   mysqli_set_charset($link,'utf8');
      $title=['身份证号','姓名','岗位工资','薪级工资','基础性绩效工资','女职卫生费','护龄津贴','卫生津贴','奖励工资','扣工会会费','扣休假工资','一孩化补助','津补贴','物业补贴','防暑降温费','职等工资','绩效工资合计','风险基金','工资补差','交通补贴','通讯补贴','扣款','应付工资','应付应税工资','代扣住房公积金','代扣养老保险','代扣医疗保险','代扣失业保险','代扣企业年金个人部分','扣税基数','代扣工资中个人所得税','实发工资','本次实发','单位养老','单位医疗','单位生育','单位工伤','单位失业','单位公积金','企业年金单位缴纳入个人账户部分','取暖费','特殊岗位津贴','精神文明奖','目标奖','其他','年份','月份'];
	   
	   exportExcel($title,'','工资表');
	}else{
		echo "没有权限";
	}
}else{
	echo "请先登录";
}

function exportExcel($title=array(), $data=array(), $fileName='', $savePath='./', $isDown=true){  
    include('PHPExcel.php');  
    $obj = new PHPExcel();  
  
    //横向单元格标识  
    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');  
      
    $obj->getActiveSheet(0)->setTitle('sheet名称');   //设置sheet名称  
    $_row = 1;   //设置纵向单元格标识  
    if($title){  
        $_cnt = count($title);  
        $obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.$cellName[$_cnt-1].$_row);   //合并单元格  
        $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '数据导出：'.date('Y-m-d H:i:s'));  //设置合并后的单元格内容  
        $_row++;  
        $i = 0;  
        foreach($title AS $v){   //设置列标题  
            $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);  
            $i++;  
        }  
        $_row++;  
    }  
  
    //填写数据  
    if($data){  
        $i = 0;  
        foreach($data AS $_v){  
            $j = 0;  
            foreach($_v AS $_cell){  
                $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);  
                $j++;  
            }  
            $i++;  
        }  
    }  
      
    //文件名处理  
    if(!$fileName){  
        $fileName = uniqid(time(),true);  
    }  
  
    $objWrite = PHPExcel_IOFactory::createWriter($obj, 'Excel2007');  
  
    if($isDown){   //网页下载  
        header('pragma:public');  
        header("Content-Disposition:attachment;filename=$fileName.xls");  
        $objWrite->save('php://output');exit;  
    }  
  
    $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码  
    $_savePath = $savePath.$_fileName.'.xlsx';  
     $objWrite->save($_savePath);  
  
     return $savePath.$fileName.'.xlsx';  
}  
?>