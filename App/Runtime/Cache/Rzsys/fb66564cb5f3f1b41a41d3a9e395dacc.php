<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>会员管理</title>
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
        	<form method="get" action="<?php echo U('User/alist');?>" id="sub">
             <div class="layui-input-inline">
		    	<input  name="uid" placeholder="请输入用户id" class="layui-input search_input" type="text" value="<?php echo ($getall['uid']?$getall['uid']:''); ?>">
		    </div>
		   <div class="layui-input-inline">
		    	<input  name="name" placeholder="请输入用户名" class="layui-input search_input" type="text" value="<?php echo ($getall['name']?$getall['name']:''); ?>" >
		    </div>
            <div class="layui-input-inline">
             			<label class="layui-form-label">日期</label>
		    		<select name="stype"  id='stype'  class="layui-select "  >
                     <option value="1" <?php if(isset($getall['stype'])&&($getall['stype']==1)): ?>selected<?php endif; ?> >注册日期</option>
                            <option value="2" <?php if(isset($getall['stype'])&&($getall['stype']==2)): ?>selected<?php endif; ?>>会员开通日期</option>
                           <option value="3" <?php if(isset($getall['stype'])&&($getall['stype']==3)): ?>selected<?php endif; ?>>会员到期日期</option>
						    </select>
                           
		    </div>
            <div class="layui-inline">
                 <input name="retime1" value="<?php echo ($getall['retime1']?$getall['retime1']:''); ?>"  autocomplete="off"  style=" margin-left:20px;height: 38px;line-height: 38px;line-height: 36px\9;border: 1px solid #e6e6e6;background-color: #fff;border-radius: 2px;" type="date">--
                 <input name="retime2" value="<?php echo ($getall['retime2']?$getall['retime2']:''); ?>"  autocomplete="off"  style=" height: 38px;line-height: 38px;line-height: 36px\9;border: 1px solid #e6e6e6;background-color: #fff;border-radius: 2px;" type="date">            
              </div>
             
		    <a class="layui-btn search_btn" onclick="suball()" >查询</a>
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
                                  <th>用户名</th>
                                  <th>真实姓名</th>
                                  <th>头像</th>
                                   <!--<th>电话</th>
                                   <th>银行卡号</th>
                                  <th>开户行</th>-->
                                  <!--<th>上级</th>
                                  <th>下级分销比例</th>-->
                                  <th>会员开通时间</th>
                                   <th>会员到期时间</th>
                                   
                                  <th>注册时间</th>
                                 
                                 <th width="300px;">操作</th>
                              </tr>
                          </thead>
                          <tbody>
                           <?php if(is_array($ad)): $i = 0; $__LIST__ = $ad;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                                            <td><input type="checkbox"></td>
                                            <td><?php echo ($data['ID']); ?></td>
                                            
                                            <td><?php echo ($data['USERNAME']); ?></td>
                                            <td><?php echo ($data['TRUENAME']); ?></td>
                                             <td><img src="<?php echo ($data['USERIMG']); ?>" height='80px'></td>
                                            <!-- <td><?php echo ($data['TELNO']); ?></td>
                                             <td><?php echo ($data['BC_NUMBER']); ?></td>
                                            <td><?php echo ($data['BC_OPEN_BANK']); ?></td>-->
                                            <!--<td><?php echo ($data['TOPUSER']); ?></td>
                                            <td><?php echo ($data['FACTION']); ?></td>-->
                                            <td><?php echo ($data['viptime']); ?></td>
                                             <td><?php echo ($data['GMT_ENDTIME']); ?></td>
                                              
                                             <td><?php echo ($data['GMT_CREATE']); ?></td>
                                             
                                             <td><a href="javascript:void(0)" class="layui-btn layui-btn-small" onclick="return delall(<?php echo ($data['ID']); ?>,<?php echo ($pg); ?>)"><i class="iconfont icon-shanchu1"></i>删除</a>
                                              	 &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo U('User/add');?>?id=<?php echo ($data['ID']); ?>&pg=<?php echo ($pg); ?>" class="layui-btn layui-btn-small"><i class="iconfont icon-shanchu1"></i>编辑</a>
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
				 url: "<?php echo U('user/del');?>",
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

function fenghao(id,pg,state){
	
		$.ajax({
				 type: "get",
				 url: "<?php echo U('user/fenghao');?>",
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