<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>视频管理</title>
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
</head>
<body>
<section class="layui-larry-box">
	<div class="larry-personal" >
	    <div class="layui-tab">
            <blockquote class="layui-elem-quote news_search" >
		<div class="layui-inline">
        	<form method="get" action="<?php echo U('video/alist');?>" id="sub" >
            <div class="layui-input-inline">
		    	<input  name="name" placeholder="请输入名称" class="layui-input search_input" type="text" value="<?php echo ($getall['name']?$getall['name']:''); ?>">
		    </div>
		    <div class="layui-input-inline">
             			
                            <div class="layui-inline">
                              <label class="layui-form-label">时间</label>
                              <div class="layui-input-block">
                                <input name="date" id="date1" autocomplete="off" value="<?php echo ($getall['date']?$getall['date']:''); ?>" class="layui-input" type="date">
                              </div>
                            </div>
		    </div>
            <div class="layui-input-inline">
             			
                            <div class="layui-inline">
                              <label class="layui-form-label">---</label>
                              <div class="layui-input-block">
                                <input name="date1" id="date2" value="<?php echo ($getall['date1']?$getall['date1']:''); ?>" autocomplete="off" class="layui-input" type="date">
                              </div>
                            </div>
		    </div>
		    <a class="layui-btn search_btn" onclick="suball()">查询</a>
            </form>
		</div>
		
	</blockquote>
            
		    <div class="layui-tab-content larry-personal-body clearfix mylog-info-box">
		         <!-- 操作日志 -->
                <div class="layui-tab-item layui-field-box layui-show">
                     <table class="layui-table table-hover" lay-even="" lay-skin="nob">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" id="selected-all"></th>
                                  <th>ID</th>
                                  <th>视频名称</th>
                                   <th>播放时长</th>
                                   <!--<th style="">商品预约金额</th>-->
                                   <th>视频封面</th>
                                   
                                   <th>视频类型</th>
                                   <th>视频种类</th>
                                   <th>视频类别</th>
                                   <th>播放次数</th>
                       
                                    <th>状态</th>
                                  <th>添加时间</th>
                                  
                                  <th width="300px;" >操作</th>
                              </tr>
                          </thead>
                          <tbody>
                           <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                                            <td><input type="checkbox"></td>
                                            <td><?php echo ($data['ID']); ?></td>
                                            <td><?php echo ($data['MV_NAME']); ?></td>
                                          <td><?php echo ($data['MV_TIME']); ?></td>
                   
                                           <td><img src="<?php echo ($data['MV_PHOTO_URL']); ?>" height='80px'></td>
                                           
                                            <td><?php echo ($free[$data['MV_TYPE']]); ?></td>
                                            <td><?php echo ($data['MV_PHYLETIC']); ?></td>
                                            <!--<td><?php echo ($class[$data['MV_PHYLETIC']]); ?></td>-->
                                            <td><?php echo ($data['MV_CATEGORY']); ?></td>
                                            <td><?php echo ($data['MV_PLAY_COUNT']); ?></td>
                                            
                                            
                                            <td><?php echo ($state[$data['MV_STATUS']]); ?></td>
                                             <td><?php echo ($data['GMT_CREATE']); ?></td>
                                              
											<td><a href="javascript:void(0)" class="layui-btn layui-btn-small" onclick="return delall(<?php echo ($data['ID']); ?>,<?php echo ($pg); ?>)"><i class="iconfont icon-shanchu1"></i>删除</a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('video/add');?>?id=<?php echo ($data['ID']); ?>&pg=<?php echo ($pg); ?>" class="layui-btn layui-btn-small"><i class="iconfont icon-shanchu1"></i>编辑</a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="layui-btn layui-btn-small" onclick="return tuijian(<?php echo ($data['ID']); ?>,<?php echo ($pg); ?>,<?php echo ($data['MV_STATUS']); ?>)"  ><i class="iconfont icon-shanchu1"></i> <?php if($data['MV_STATUS']==1): ?>下架<?php else: ?>上架<?php endif; ?> </a>
                                            </td>
                                          </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                              
                          </tbody>
                     </table>
                      <div class="larry-table-page clearfix">
                         
				          <div id="page2" class="page"><?php echo ($page); ?></div>
			         </div>
			    </div>
			     <!-- 登录日志 -->
			  
		    </div>
		</div>
	</div>
</section>
<script type="text/javascript" src="/Public/admin/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script>

<script type="text/javascript">
layui.use('layer', function(){
var layer = layui.layer;
}); 
function delall(id,pg){
	if(confirm("确认要删除吗？")){
		$.ajax({
				 type: "get",
				 url: "<?php echo U('video/del');?>",
				 data: {id:id,pg:pg},
				 dataType: "json",
				 async:false,
				 success: function(data){
					layer.msg(data.msg);
						setTimeout(function() {
							window.location =data.href;
						}, 1200);
					
				 }
							
			 });
	}else{
		return false;
	}
}
function tuijian(id,pg,state){

		$.ajax({
				 type: "get",
				 url: "<?php echo U('video/tuijian');?>",
				 data: {id:id,pg:pg,state:state},
				 dataType: "json",
				 async:false,
				 success: function(data){
					layer.msg(data.msg);
						setTimeout(function() {
							window.location =data.href;
						}, 1200);
					
				 }
							
			 });
	
}	
function suball(){
	document.getElementById('sub').submit();
	
}
</script>
</body>
</html>