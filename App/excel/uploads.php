<?php 
session_start();
if(isset($_SESSION['think']['admin'])&&!empty($_SESSION['think']['admin'])){
	$user=$_SESSION['think']['admin'];
	if($user['jid']==1){
		$link = @mysqli_connect('127.0.0.1','xygzmx','Vpmy37xZnbZcpNZK','xy_gongzimingxi');
		//设置字符集
	   mysqli_set_charset($link,'utf8');
     	if($_POST['dopost']==1){
			//var_dump($_FILES);die();
			
			   if ($_FILES["excel"]["error"] > 0)
				  {
				  echo "Error: " . $_FILES["excel"]["error"] . "<br />";
				  }
				else
				  {
					  $name=date('YmdHis',time()).'.xlsx';
					  $path = 'upload/'.$name;
					move_uploaded_file($_FILES["excel"]["tmp_name"],$path);
						$gongzi=inserExcel($path);
			
						dopostall($gongzi);
				  }
		}
	  
	}else{
		echo "没有权限";
	}
}else{
	echo "请先登录";
}
function dopostall($arr){

	if(empty($arr)){
		echo "没有数据导入";
	}else{
		  $link = @mysqli_connect('127.0.0.1','xygzmx','Vpmy37xZnbZcpNZK','xy_gongzimingxi');
				//设置字符集
		   mysqli_set_charset($link,'utf8');
		   //导入数量
		   $s=0;//成功
		   $f=0;//失败
		   //var_dump($arr);die();
		foreach($arr as $val){
			$addtime=strtotime($val['45'].'-'.$val['46']);
			echo $addtime;
			foreach($val as $key=>$v){
				if(empty($v)){
					$v=0;
				}
				$val[$key]=$v;
			}
			$sql="insert  into `cm_gongzi`(`idcard`,`name`,`gangwei`,`xinji`,`jichu`,`weisheng`,`huling`,`jintie`,`jiangli`,`huifei`,`xiujia`,`boy`,`jinbu`,`wuye`,`shujia`,`zhideng`,`jixiao`,`fengxian`,`bucha`,`jiaotong`,`tongxun`,`koukuan`,`yingfu`,`yingshui`,`zhufang`,`yanglao`,`ylbx`,`baoxian`,`qiye`,`jishu`,`geren`,`shifa`,`benci`,`danwei`,`yiliao`,`shengyu`,`gongshang`,`shiye`,`gongjijin`,`zhanghu`,`qunuan`,`teshu`,`jingshen`,`mubiao`,`qita`,`year`,`month`,`addtime`) values ($val[0],'$val[1]',$val[2],$val[3],$val[4],$val[5],$val[6],$val[7],$val[8],$val[9],$val[10],$val[11],$val[12],$val[13],$val[14],$val[15],$val[16],$val[17],$val[18],$val[19],$val[20],$val[21],$val[22],$val[23],$val[24],$val[25],$val[26],$val[27],$val[28],$val[29],$val[30],$val[31],$val[32],$val[33],$val[34],$val[35],$val[36],$val[37],$val[38],$val[39],$val[40],$val[41],$val[42],$val[43],$val[44],$val[45],$val[46],$addtime)";
			 //echo $sql;
			$result = mysqli_query($link,$sql);
			if($result){
				$s++;
			}else{
				$f++;
			}
		}
		echo '成功导入'.$s."条数据，导入失败".$f.'条数据';
	}
	
}
function inserExcel($path)
    {
        include('PHPExcel.php'); 
		include('PHPExcel/IOFactory.php'); 
		include('PHPExcel/Reader/Excel5.php');
		
		//Loader::import('PHPExcel.Classes.PHPExcel');
        //Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        //Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel5');
        //获取表单上传文件
		
		 
		  
		  
       
       
            if(empty($path)) {
                return error('导入失败!');
            }
            
            //上传文件的地址
            $filename =   $path;   
            //判断版本，这里有的网上的版本没有进行判断，导致会报大概这样的错误：
            //Warning: ZipArchive::getFromName() [ziparchive.getfromname]: Invalid or unitialized ，这里加上这个判断就可以了
            $extension = strtolower( pathinfo($filename, PATHINFO_EXTENSION) );
           
            	$objPHPExcelReader = PHPExcel_IOFactory::load($filename);  
 
	$reader = $objPHPExcelReader->getWorksheetIterator();
	
	//循环读取sheet
	foreach($reader as $sheet) {
		//读取表内容
		$content = $sheet->getRowIterator();
		//逐行处理
		$res_arr = array();
		
		foreach($content as $key => $items) {
			
			 $rows = $items->getRowIndex();    			//行
			 $columns = $items->getCellIterator();		//列
			 $row_arr = array();
			 //确定从哪一行开始读取
			 if($rows < 3){
				 continue;
			 }
			 //逐列读取
			 foreach($columns as $head => $cell) {
				 //获取cell中数据
				 $data = $cell->getValue();
				
				 $row_arr[] = $data;
			 }
			 $res_arr[] = $row_arr;
		}
		
	}
	//var_dump($res_arr);die();
	return $res_arr;
    }
?>