<?php
/**
 * 
 * 用户登录
 * @author Administrator
 *
 */
class UploadController {
//缩放图片
function image_resize($f, $t, $tw, $th){
	// 按指定大小生成缩略图，而且不变形，缩略图函数
		$temp = array(1=>'gif', 2=>'jpeg', 3=>'png', 4=>'jpg');
		list($fw, $fh, $tmp) = getimagesize($f);
		if(!$temp[$tmp]){
			return false;
		}
		$tmp = $temp[$tmp];
		$infunc = "imagecreatefrom$tmp";
		$outfunc = "image$tmp";
		$fimg = $infunc($f);
		// 使缩略后的图片不变形，并且限制在 缩略图宽高范围内
		if($fw/$tw > $fh/$th){
			$th = $tw*($fh/$fw);
		}else{
			$tw = $th*($fw/$fh);
		}
		$timg = imagecreatetruecolor($tw, $th);
		
		imagecopyresampled($timg,$fimg, 0,0, 0,0, $tw,$th, $fw,$fh);
		if($outfunc($timg, $t)){
			return true;
		}else{
			return false;
		}
	}
function imagecropper($source_path,$target_path,$target_width,$target_height)
{
    $source_info   = getimagesize($source_path);
    $source_width  = $source_info[0];
    $source_height = $source_info[1];
    $source_mime   = $source_info['mime'];
    $source_ratio  = $source_height / $source_width;
    $target_ratio  = $target_height / $target_width;
 
    // 源图过高
    if ($source_ratio > $target_ratio)
    {
        $cropped_width  = $source_width;
        $cropped_height = $source_width * $target_ratio;
        $source_x = 0;
        $source_y = ($source_height - $cropped_height) / 2;
    }
    // 源图过宽
    elseif ($source_ratio < $target_ratio)
    {
        $cropped_width  = $source_height / $target_ratio;
        $cropped_height = $source_height;
        $source_x = ($source_width - $cropped_width) / 2;
        $source_y = 0;
    }
    // 源图适中
    else
    {
        $cropped_width  = $source_width;
        $cropped_height = $source_height;
        $source_x = 0;
        $source_y = 0;
    }

    switch ($source_mime)
    {
        case 'image/gif':
            $source_image = imagecreatefromgif($source_path);
            break;
 
        case 'image/jpeg':
            $source_image = imagecreatefromjpeg($source_path);
            break;
 
        case 'image/png':
            $source_image = imagecreatefrompng($source_path);
            break;
 
        default:
            return false;
            break;
    }
	$target_image  = imagecreatetruecolor($target_width, $target_height);
    $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
    // 裁剪
    imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
    // 缩放
    imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
 
    header('Content-Type: image/jpeg');
    imagejpeg($target_image,$target_path);
   
}
	//上传头像
	 public function actionIndex()
    {   
		
		$uid=Yii::app()->user->id;
		$time = time();
        if (!$_FILES['Filedata']) {
			die ( 'Image data not detected!' );
		}
		if ($_FILES['Filedata']['error'] > 0) {
			switch ($_FILES ['Filedata'] ['error']) {
				case 1 :
					$error_log = 'The file is bigger than this PHP installation allows';
					break;
				case 2 :
					$error_log = 'The file is bigger than this form allows';
					break;
				case 3 :
					$error_log = 'Only part of the file was uploaded';
					break;
				case 4 :
					$error_log = 'No file was uploaded';
					break;
				default :
					break;
			}
			die ( 'upload error:' . $error_log );
		} else {
			$img_data = $_FILES['Filedata']['tmp_name'];
			$size = getimagesize($img_data);
			$file_type = $size['mime'];
			if (!in_array($file_type, array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'))) {
				$error_log = 'only allow jpg,png,gif';
				die ( 'upload error:' . $error_log );
			}
			switch($file_type) {
				case 'image/jpg' :
				case 'image/jpeg' :
				case 'image/pjpeg' :
					$extension = 'jpg';
					break;
				case 'image/png' :
					$extension = 'png';
					break;
				case 'image/gif' :
					$extension = 'gif';
					break;
			}	
		}
		if (!is_file($img_data)) {
			die ( 'Image upload error!' );
		}
		
		//图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
		$save_path = $_SERVER['DOCUMENT_ROOT'];
		$save_path.=Yii::app()->request->baseUrl;
		$uinqid = $uid.'_'.$time;
		$sourcename = $save_path . "/uploads/user_avatar".'/' . $uinqid . '_source.' . $extension;
		$result = move_uploaded_file($img_data,$sourcename);
		
		
		//echo $sourcename;die();
		if ( ! $result || ! is_file($sourcename) ) {
			die ( 'Image upload error!' );
		}
		//压缩原图
		
		$file_name_s=$save_path."/uploads/user_avatar".'/' . $uinqid . '_source.' . $extension;
		$this->image_resize($file_name_s,$file_name_s,$size[0],$size[1]);
		$file_name_small=$save_path."/uploads/user_avatar".'/' . $uinqid . '.' . $extension;
		$file_name_big = $save_path."/uploads/user_avatar".'/' . $uinqid . '_big.' . $extension;
		$file_name_sq = $save_path."/uploads/user_avatar".'/' . $uinqid . '_sq.' . $extension;
		//大图
		$this->image_resize($file_name_s,$file_name_big,400, 400);
		
		//小图
		$this->image_resize($file_name_s,$file_name_small,80,80);
		
		//方图
		$this->imagecropper($file_name_s,$file_name_sq,210,210);
		
		//@file_put_contents($file_name_small,$src);
		//生成你要的文件路径和名字结束
		//写到数据库
		$pic_path = "/uploads/user_avatar".'/' . $uinqid . '_source.' . $extension;
		$sql = "SELECT * FROM tm_vipuser WHERE uid=$uid  ";  
		$users=Yii::app()->db->createCommand($sql)->queryRow(); 
		if($users['headpic']){
			$result = $save_path.$users['headpic'];
			 
			$pic_id_new=$this->bigavatar($result);
			 
			$pic_id_source=$this->bigavatar($result,'_source');
			//删除旧头像
			@unlink($result);
			@unlink($pic_id_new);
			@unlink($pic_id_source);
		}
		//echo $result;die();
		
		//Vipuser::model()->updateAll(array('uid'=>$uid),array('headpic'=>'aaa'),'');
		//$post=Vipuser::model()->findByPk($uid);
		//print_r($post);die();
		//$post->delete(); 
		//$post->headpic='aaaa';
		//$post->save(); 
		$pic_path= "/uploads/user_avatar".'/' . $uinqid . '_source.' . $extension;
		
		Yii::app()->db->createCommand()->update('tm_vipuser',array('headpic' =>$pic_path), 'uid=:id', array(':id' => $uid)); 
		
		Yii::app()->db->createCommand()->update('tm_card',array('headpic' =>$pic_path), 'uid=:id', array(':id' => $uid)); 
		
		//echo "11"; 
		
		
    }
	//更改图片名称名
	function bigavatar($headpic,$type="_big"){
		$pic_id_new = '';
		if(empty($headpic)){
			return $pic_id_new;
		}
		$pic_id_1=explode(".",$headpic);
		$pic_id_new=$pic_id_1[0].$type.".".$pic_id_1[1];
		return $pic_id_new;
	}
	//上传图片
	 public function actionuppic()
    {	
		$uid=Yii::app()->user->id;
		$time = time().(microtime()*10000000);
        if (!$_FILES['Filedata']) {
			die ( 'Image data not detected!' );
		}
		if ($_FILES['Filedata']['error'] > 0) {
			switch ($_FILES ['Filedata'] ['error']) {
				case 1 :
					$error_log = 'The file is bigger than this PHP installation allows';
					break;
				case 2 :
					$error_log = 'The file is bigger than this form allows';
					break;
				case 3 :
					$error_log = 'Only part of the file was uploaded';
					break;
				case 4 :
					$error_log = 'No file was uploaded';
					break;
				default :
					break;
			}
			die ( 'upload error:' . $error_log );
		} else {
			$img_data = $_FILES['Filedata']['tmp_name'];
			$size = getimagesize($img_data);
			$file_type = $size['mime'];
			if (!in_array($file_type, array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'))) {
				$error_log = 'only allow jpg,png,gif';
				die ( 'upload error:' . $error_log );
			}
			switch($file_type) {
				case 'image/jpg' :
				case 'image/jpeg' :
				case 'image/pjpeg' :
					$extension = 'jpg';
					break;
				case 'image/png' :
					$extension = 'png';
					break;
				case 'image/gif' :
					$extension = 'gif';
					break;
			}	
		}
		if (!is_file($img_data)) {
			die ( 'Image upload error!' );
		}
		
		//图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
		$save_path = $_SERVER['DOCUMENT_ROOT'];
		$save_path.=Yii::app()->request->baseUrl;
		$uinqid = $uid.$time;
        $targetFolder='user_avatar'; 
		if(Yii::app()->request->getParam('target')){
			$targetFolder = Yii::app()->request->getParam('target'); // Relative to the root
		}
		if(Yii::app()->request->getParam('inner')){
			$targetFolder .= '/'.Yii::app()->request->getParam('inner'); // Relative to the root
		}
		$path=$save_path . '/uploads/'.$targetFolder;
		if(!is_dir($path)){
				mkdir($path,0777);
		}
		$sourcename = $path.'/' . $uinqid . '_source.' . $extension;
		
		$result = move_uploaded_file($img_data,$sourcename);
		
		$file_name_s=$path.'/' . $uinqid . '_source.' . $extension;
		$this->image_resize($file_name_s,$file_name_s,$size[0],$size[1]);
		$file_name_small=$path.'/' . $uinqid . '.' . $extension;
		$file_name_big = $path.'/' . $uinqid . '_big.' . $extension;
		$file_name_sq = $path.'/' . $uinqid . '_sq.' . $extension;
		//小图
		$this->image_resize($file_name_s,$file_name_small,80,80);
		//方图
		$this->imagecropper($file_name_s,$file_name_sq,210,210);
		require_once('WaterMaskclass.php');
		
		$obj = new WaterMask($sourcename); //实例化对象 
		
		$obj->output(); 
		
		//echo $result;die();
		if ( ! $result || ! is_file($sourcename) ) {
			die ( 'Image upload error!' );
		}
		
		//大图
		$this->image_resize($file_name_s,$file_name_big,250, 250);
		
		//@file_put_contents($file_name_small,$src);
		//生成你要的文件路径和名字结束
		//写到数据库
		$pic_path = '/uploads/'.$targetFolder.'/' . $uinqid . '_source.' . $extension;
		echo $pic_path;die();
		
		
    }
	//上传logo图片
	 public function actionUpnowpic()
    {	
		
		$time = time().(microtime()*10000000);
        if (!$_FILES['Filedata']) {
			die ( 'Image data not detected!' );
		}
		if ($_FILES['Filedata']['error'] > 0) {
			switch ($_FILES ['Filedata'] ['error']) {
				case 1 :
					$error_log = 'The file is bigger than this PHP installation allows';
					break;
				case 2 :
					$error_log = 'The file is bigger than this form allows';
					break;
				case 3 :
					$error_log = 'Only part of the file was uploaded';
					break;
				case 4 :
					$error_log = 'No file was uploaded';
					break;
				default :
					break;
			}
			die ( 'upload error:' . $error_log );
		} else {
			$img_data = $_FILES['Filedata']['tmp_name'];
			$size = getimagesize($img_data);
			$file_type = $size['mime'];
			if (!in_array($file_type, array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'))) {
				$error_log = 'only allow jpg,png,gif';
				die ( 'upload error:' . $error_log );
			}
			switch($file_type) {
				case 'image/jpg' :
				case 'image/jpeg' :
				case 'image/pjpeg' :
					$extension = 'jpg';
					break;
				case 'image/png' :
					$extension = 'png';
					break;
				case 'image/gif' :
					$extension = 'gif';
					break;
			}	
		}
		if (!is_file($img_data)) {
			die ( 'Image upload error!' );
		}
		
		//图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
		$save_path = $_SERVER['DOCUMENT_ROOT'];
		$save_path.=Yii::app()->request->baseUrl;
		$uinqid = $time;
        $targetFolder='user_avatar'; 
		if(Yii::app()->request->getParam('target')){
			$targetFolder = Yii::app()->request->getParam('target'); // Relative to the root
		}
		if(Yii::app()->request->getParam('inner')){
			$targetFolder .= '/'.Yii::app()->request->getParam('inner'); // Relative to the root
		}
		$path=$save_path . '/uploads/'.$targetFolder;
		if(!is_dir($path)){
				mkdir($path,0777);
		}
		$sourcename = $path.'/' . $uinqid . '_source.' . $extension;
		$result = move_uploaded_file($img_data,$sourcename);
		
		
		if ( ! $result || ! is_file($sourcename) ) {
			die ( 'Image upload error!' );
		}
		
		$file_name_s=$path.'/' . $uinqid . '_source.' . $extension;
		$this->image_resize($file_name_s,$file_name_s,$size[0],$size[1]);
		$file_name_small=$path.'/' . $uinqid . '.' . $extension;
		$file_name_big = $path.'/' . $uinqid . '_big.' . $extension;
		$file_name_sq = $path.'/' . $uinqid . '_sq.' . $extension;
		//大图
		$this->image_resize($file_name_s,$file_name_big,400, 400);
		//小图
		$this->image_resize($file_name_s,$file_name_small,80,80);
		//方图
		$this->imagecropper($file_name_s,$file_name_sq,210,210);
		//@file_put_contents($file_name_small,$src);
		//生成你要的文件路径和名字结束
		//写到数据库
		$pic_path = '/uploads/'.$targetFolder.'/' . $uinqid . '_sq.' . $extension;
		echo $pic_path;die();
		
    }
	
	//上传活动封面图
	 public function actionUpevent()
    {	
		
		$time = time().(microtime()*10000000);
        if (!$_FILES['Filedata']) {
			die ( 'Image data not detected!' );
		}
		if ($_FILES['Filedata']['error'] > 0) {
			switch ($_FILES ['Filedata'] ['error']) {
				case 1 :
					$error_log = 'The file is bigger than this PHP installation allows';
					break;
				case 2 :
					$error_log = 'The file is bigger than this form allows';
					break;
				case 3 :
					$error_log = 'Only part of the file was uploaded';
					break;
				case 4 :
					$error_log = 'No file was uploaded';
					break;
				default :
					break;
			}
			die ( 'upload error:' . $error_log );
		} else {
			$img_data = $_FILES['Filedata']['tmp_name'];
			$size = getimagesize($img_data);
			$file_type = $size['mime'];
			if (!in_array($file_type, array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'))) {
				$error_log = 'only allow jpg,png,gif';
				die ( 'upload error:' . $error_log );
			}
			switch($file_type) {
				case 'image/jpg' :
				case 'image/jpeg' :
				case 'image/pjpeg' :
					$extension = 'jpg';
					break;
				case 'image/png' :
					$extension = 'png';
					break;
				case 'image/gif' :
					$extension = 'gif';
					break;
			}	
		}
		if (!is_file($img_data)) {
			die ( 'Image upload error!' );
		}
		
		//图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
		$save_path = $_SERVER['DOCUMENT_ROOT'];
		$save_path.=Yii::app()->request->baseUrl;
		$uinqid = $time;
        $targetFolder='event'; 
		if(Yii::app()->request->getParam('target')){
			$targetFolder = Yii::app()->request->getParam('target'); // Relative to the root
		}
		if(Yii::app()->request->getParam('inner')){
			$targetFolder .= '/'.Yii::app()->request->getParam('inner'); // Relative to the root
		}
		$path=$save_path . '/uploads/'.$targetFolder;
		if(!is_dir($path)){
				mkdir($path,0777);
		}
		$sourcename = $path.'/' . $uinqid . '_source.' . $extension;
		$result = move_uploaded_file($img_data,$sourcename);
		
		
		if ( ! $result || ! is_file($sourcename) ) {
			die ( 'Image upload error!' );
		}
		$file_name_s=$path.'/' . $uinqid . '_source.' . $extension;
		$this->image_resize($file_name_s,$file_name_s,$size[0],$size[1]);
		$file_name_small=$path.'/' . $uinqid . '.' . $extension;
		$file_name_big = $path.'/' . $uinqid . '_big.' . $extension;
		$file_name_sq = $path.'/' . $uinqid . '_sq.' . $extension;
		//大图
		$this->imagecropper($file_name_s,$file_name_big,700, 331);
		//小图
		$this->imagecropper($file_name_s,$file_name_small,120,75);
		//方图
		$this->imagecropper($file_name_s,$file_name_sq,300,300);
		//@file_put_contents($file_name_small,$src);
		//生成你要的文件路径和名字结束
		//写到数据库
		$pic_path = '/uploads/'.$targetFolder.'/' . $uinqid . '_big.' . $extension;
		echo $pic_path;die();
		
		
    }
	
	//上传文件
	 public function actionUpfile()
    {	
		
		$time = time();
        if (!$_FILES['Filedata']) {
			die ( 'Image data not detected!' );
		}
		if ($_FILES['Filedata']['error'] > 0) {
			switch ($_FILES ['Filedata'] ['error']) {
				case 1 :
					$error_log = 'The file is bigger than this PHP installation allows';
					break;
				case 2 :
					$error_log = 'The file is bigger than this form allows';
					break;
				case 3 :
					$error_log = 'Only part of the file was uploaded';
					break;
				case 4 :
					$error_log = 'No file was uploaded';
					break;
				default :
					break;
			}
			die ( 'upload error:' . $error_log );
		} else {
			$img_data = $_FILES['Filedata']['tmp_name'];
			$name=$_FILES['Filedata']['name'];
			//$name=iconv("UTF-8","gb2312", $name);
			if (!is_file($img_data)) {
				die ( 'Image upload error!' );
			}
			
			$extension=substr(strrchr($name, '.'), 1);
			if (!$extension) {
				die ( '上传文件格式不正确!' );
			}
		
			//$extension='psd';
			//图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
			$save_path = $_SERVER['DOCUMENT_ROOT'];
			$save_path.=Yii::app()->request->baseUrl;
			$targetFolder='profile'; 
			
			if(Yii::app()->request->getParam('target')){
				$targetFolder .= '/'.Yii::app()->request->getParam('target'); // Relative to the root
			}
			
			$path=$save_path . '/uploads/'.$targetFolder;
			if(!is_dir($path)){
				mkdir($path,0777);
			}
			$sourcename = $path.'/' . $time.'.'.$extension;
			$result = move_uploaded_file($img_data,$sourcename);
			//$name=iconv("gb2312","utf-8", $name);
			$furl='/uploads/'.$targetFolder.'/'.$time.'.'.$extension;
			echo $furl;die();
		}
		
    }
	
	
	
}

