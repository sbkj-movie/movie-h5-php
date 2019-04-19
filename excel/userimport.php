<?php 
session_start();
if(isset($_SESSION['adminid'])&&!empty($_SESSION['adminid'])){
	$user=$_SESSION['adminid'];
		$link = @mysqli_connect('localhost','0571shipin','KQ4D1NymCHCq1MNa','0571_shipin');
		
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
	echo "请先登录";
}
function dopostall($arr){

	if(empty($arr)){
		echo "没有数据导入";
	}else{
		 $link = @mysqli_connect('localhost','0571shipin','KQ4D1NymCHCq1MNa','0571_shipin');
				//设置字符集
		   mysqli_set_charset($link,'utf8');
		   //导入数量
		   $s=0;//成功
		   $f=0;//失败
		   $resion='';
		foreach($arr as $val){
			$addtime=time();
			foreach($val as $key=>$v){
				if(empty($v)){
					$v=0;
				}
				$val[$key]=$v;
			}
			$result='';
			if(!empty($val['0'])){
				$sql="INSERT  INTO `mv_movies`(`MV_NAME`,`MV_PHOTO_URL`,`MV_HURL`,`MV_WURL`,`MV_SHURL`,`MV_WHURL`,`MV_TIME`,`MV_PLAY_COUNT`,`MV_TYPE`,`MV_PHYLETIC`,`MV_CONTENT`,`MV_CATEGORY`,`GMT_CREATE`) 
				       VALUES ('$val[0]','$val[1]','$val[2]','$val[3]','$val[4]','$val[5]','$val[6]','$val[7]','$val[8]','$val[9]','$val[10]','$val[11]','$val[12]')";
				$result = mysqli_query($link,$sql);
			}else{
				$resion='视频名称为空！';
			}
			//判断用户是否存在
			
			if(!empty($result)){
				$s++;
			}else{
				$f++;
			}
		}
		echo '成功导入'.$s."条数据，导入失败".$f.'条数据,'.'失败原因:'.$resion;
	}
	
}
function select($result){
		 if($result && mysqli_num_rows($result)>0){
				//有数据
				while($row = mysqli_fetch_assoc($result)){
					$rows[] = $row;
				}
				//返回二维数组
				return $rows;
			}else{
				//没有数据
				return false;
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