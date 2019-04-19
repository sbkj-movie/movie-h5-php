<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>章节添加</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">	
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/layui/css/layui.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/bootstrap/css/bootstrap.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/global.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/css/personal.css" media="all">
  <script type="text/javascript" charset="utf-8" src="/Public/admin/ueidtor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Public/admin/ueidtor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/Public/admin/ueidtor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>


</head>
<body>
<section class="layui-larry-box">
	<div class="larry-personal">
		<header class="larry-personal-tit">
			<span>添加章节</span>
		</header><!-- /header -->
		<div class="larry-personal-body clearfix">
			<form class="layui-form col-lg-5"  method="post" id="mainform" >
            <input type="hidden" name="id" id="upid" value="<?php echo ($data['ID']?$data['ID']:''); ?>">
            <input type="hidden" name="pg" id="pg" value="<?php echo ($data['pg']?$data['pg']:'1'); ?>">
            	<div class="layui-form-item">
                      <label class="layui-form-label">所属漫画</label>
                      <div class="layui-input-block">  
                      
                        <select name="modules" id="classid" lay-verify="required" lay-search="">
                        
                        	 <?php if(is_array($data["manhua"])): $i = 0; $__LIST__ = $data["manhua"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($i % 2 );++$i;?><option value="<?php echo ($da["ID"]); ?>"  <?php if(isset($data['CT_ID'])&&($data['CT_ID']==$da['ID'])): ?>selected<?php endif; ?>><?php echo ($da["CT_NAME"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                      </div>
                    </div>
           	    <div class="layui-form-item">
					<label class="layui-form-label">章节名称</label>
					<div class="layui-input-block">  
						<input type="text" name="name" id="name" required class="layui-input " lay-verify="name" value="<?php echo ($data['CTD_TITLE']?$data['CTD_TITLE']:''); ?>"  >
					</div>
				</div>
   
                
                
            		<div class="layui-form-item">
					<label class="layui-form-label">漫画类型</label>
                     <input type="hidden" id="typeall" value="<?php echo ($data['CT_TYPE']?$data['CT_TYPE']:'1'); ?>">
					<div class="layui-input-block">
						<input type="radio" name="type" value="1" lay-filter="type"  title="免费" <?php if(isset($data['CT_TYPE'])&&$data['CT_TYPE']==1 ): ?>checked="checked"<?php endif; ?> <?php if(!isset($data['CT_TYPE'])): ?>checked="checked"<?php endif; ?>><div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon"></i><span>免费</span></div>
						<input type="radio" name="type" value="2" lay-filter="type"  title="会员" <?php if(isset($data['CT_TYPE'])&&$data['CT_TYPE']==2): ?>checked="checked"<?php endif; ?>><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><span>会员</span></div>
					</div>
				</div>
               
  
                  <div class="layui-form-item">
                        <label class="layui-form-label">漫画封面图(527*375)</label>
                        <div class="layui-input-block">
                            <input type="text" name="spic" id="spic"   lay-verify="required"  class="layui-input" value="<?php echo ($data['CTD_PHOTO_URL']?$data['CTD_PHOTO_URL']:''); ?>">
                             
                        </div>
                       
                    </div>
                 
              
                <div class="layui-form-item">
					<label class="layui-form-label">漫画内容</label>
					<div style="margin-left: 120px;">
                        <div>  
                           
                            <div >
                            <a onClick="addlunbo()" style=" display:block; width:80px; color:#fff; height:30px; line-height:30px; text-align:center; background:#2299ee" href="javascript:void(0)">添加</a>
                            </div>
                        </div>
                        <div style="width:100%; height:1px; clear:both"></div>
                        <div id="lunbo">
                         <?php if(!isset($data['CTD_PHOTO_LIST'])): ?><div id="lunbo_0">  
                                    <div style="float:left">
                                    <input type="text" name="imgsrc[]"  lay-verify="required"  class="layui-input " value=""  style="width:250px; " >
                                    </div>
                                    <div style="float:right">
                                    <a onClick="dellunbo('lunbo_0')" style=" display:block; width:80px; color:#fff; height:30px; line-height:30px; text-align:center; background:#2299ee" href="javascript:void(0)">删除</a>
                                   </div>
                                 </div>
                                <div style="width:100%; height:1px; clear:both"></div><?php endif; ?>
                            <?php if(is_array($data["picall"])): $k = 0; $__LIST__ = $data["picall"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$da): $mod = ($k % 2 );++$k;?><div id="lunbos_<?php echo ($k); ?>">  
                                    <div style="float:left">
                                    <input type="text" name="imgsrc[]"  lay-verify="required"  class="layui-input " value="<?php echo ($da); ?>"  style="width:250px; " >
                                    </div>
                                    <div style="float:right">
                                    <a onClick="dellunbo('lunbos_<?php echo ($k); ?>')" style=" display:block; width:80px; color:#fff; height:30px; line-height:30px; text-align:center; background:#2299ee" href="javascript:void(0)">删除</a>
                                   </div>
                                 </div>
                                <div style="width:100%; height:1px; clear:both"></div><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                   </div>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="" id="demo1" lay-filter="demo1">立即提交</button>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>

<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script>
<script type="text/javascript">
 var fi=1;
function addlunbo(){
	
	var str=' <div id="lunbo_'+fi+'">'+  
                            '<div style="float:left">'+
                            '<input type="text" name="imgsrc[]"  lay-verify="required"  class="layui-input " value=""  style="width:250px; " >'+
                            '</div>'+
                            '<div style="float:right">'+
                           ' <a onClick="dellunbo(\'lunbo_'+fi+'\')" style=" display:block; width:80px; color:#fff; height:30px; line-height:30px; text-align:center; background:#2299ee" href="javascript:void(0)">删除</a>'+
                           ' </div>'+
                        ' </div>'+
						'<div style="width:100%; height:1px; clear:both"></div>';
	fi++;		
	$('#lunbo').append(str);
}
function dellunbo(fiid){
	$('#'+fiid).remove();
}
</script>
<script type="text/javascript">
 
	layui.use(['form','upload'],function(){
         var form = layui.form();
         //自定义验证规则 
		 form.verify({  
				name: function(value){  
				  if(value==''){  
					return '章节名称不能为空';  
				  }  
				}
				
		  });
		  var type=$('#typeall').val(); 
		  form.on('radio(type)', function (data) { 
		 		if(data.value==2){
					type=2;
				}else{
					type=1;
				}
		   });
		  
		   //监听提交  
		  form.on('submit(demo1)', function(data){ 
		  	var pid=[];
		    $.each($('input[name="imgsrc[]"]').serializeArray(), function(i, field){
				pid.push(field.value);
			});
		  
			 //$("#demo1").attr('disabled',true);
			// $("#demo1").css("background", "#ccc");
			$.ajax({
				 type: "post",
				 url: "<?php echo U('cartoon/zjdoadd');?>",
				 data: {name:$('#name').val(),classid:$('#classid').val(),type:type,pic:$('#spic').val(),'picall':pid,id:$('#upid').val(),pg:$('#pg').val()},
				 dataType: "json",
				 async:false,
				 success: function(data){
					 
					layer.msg(data.msg);
					if(data.error!=1){
						setTimeout(function() {
							window.location =data.href;
						}, 1200);
					}
						
					
				 }
							
			 });
				 return false;  
			
		  }); 
	});
</script>
<script type="text/javascript">
	layui.use(['form','upload'],function(){
         var form = layui.form();
         //自定义验证规则  
		layui.upload({ 
		  	 elem: '#pic',
             url: "<?php echo U('login/upload');?>" ,//上传接口
			 data:{name:'pic'},
			  before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
				layer.load(); //上传loading
			  },
             success: function(res){
				layer.closeAll('loading'); //关闭loading
                var img='<img src="/'+res.img+'"  width="120px">';
				img+='<input type="hidden" name="spic" id="spic" value="'+res.img+'">';
				$('#pical').html(img);
            } 
         });
		 
		 
	});
</script>
</body>
</html>