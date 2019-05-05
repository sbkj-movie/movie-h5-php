<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>轮播图管理</title>
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
			
	
	</blockquote>
            
		    <div class="layui-tab-content larry-personal-body clearfix mylog-info-box">
		         <!-- 操作日志 -->
                <div class="layui-tab-item layui-field-box layui-show">
                     <table class="layui-table table-hover" lay-even="" lay-skin="nob">
                          <thead>
                              <tr>
                                  <th><input type="checkbox" id="selected-all"></th>
                                  <th>ID</th>
                                  <th>轮播图名称</th>
                                  <th>缩略图</th>
                                  <th>位置</th>
                                  <th>类型</th>
                                  <th>视频/漫画id</th>
                                  <th>添加时间</th>
                                  <th>操作</th>
                              </tr>
                          </thead>
                         <tbody>
                         <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                                 <td><input type="checkbox"></td>
                                 <td><?php echo ($data['ID']); ?></td>
                                 <td><?php echo ($data['BN_NAME']); ?></td>
                                 <td><img src="<?php echo ($data['BN_URL']); ?>" height='80px'></td>
                                 <td>
                                     <?php if($data['BN_POSTION']==1 ): ?>精选<?php endif; ?>
                                     <?php if($data['BN_POSTION']==2 ): ?>视频<?php endif; ?>
                                     <?php if($data['BN_POSTION']==3 ): ?>影片<?php endif; ?>
                                     <?php if($data['BN_POSTION']==3 ): ?>漫画<?php endif; ?>
                                 </td>
                                 <td>
                                     <?php if($data['BN_TYPE']!=0 ): echo ($type[$data['BN_TYPE']]); ?>
                                      <?php else: ?>
                                         自定义链接<?php endif; ?>
                                 </td>
                                 <td><?php echo ($data['BN_TYPE_ID']); ?></td>
                                 <td><?php echo ($data['GMT_CREATE']); ?></td>
                                 <td>
                                     <a href="javascript:void(0)" class="layui-btn layui-btn-small" onclick="return delall(<?php echo ($data['ID']); ?>,<?php echo ($pg); ?>)"><i class="iconfont icon-shanchu1"></i>删除</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                     <a href="<?php echo U('Banner/add');?>?id=<?php echo ($data['ID']); ?>&pg=<?php echo ($pg); ?>"  class="layui-btn layui-btn-small"><i class="iconfont icon-shanchu1"></i>编辑</a>
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
				 url: "<?php echo U('banner/del');?>",
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
function suball(){
	document.getElementById('sub').submit();
	
}	
</script>
</body>
</html>