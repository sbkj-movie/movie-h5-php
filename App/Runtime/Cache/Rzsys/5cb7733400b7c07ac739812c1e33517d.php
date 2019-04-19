<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台管理</title>
	<meta name="renderer" content="webkit">	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">	
	<meta name="apple-mobile-web-app-status-bar-style" content="black">	
	<meta name="apple-mobile-web-app-capable" content="yes">	
	<meta name="format-detection" content="telephone=no">	
	<!-- load css -->
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/layui/css/layui.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/common/global.css" media="all">
	<link rel="stylesheet" type="text/css" href="/Public/admin/css/adminstyle.css" media="all">
	
	
</head>
<body>
<div class="layui-layout layui-layout-admin" id="layui_layout">
	<!-- 顶部区域 -->
	<div class="layui-header header header-demo">
		<div class="layui-main">
		    <!-- logo区域 -->
			<div class="admin-logo-box">
				<a class="logo" href="<?php echo U('index/index');?>" title="logo">后台管理系统</a>
				<div class="larry-side-menu">
					<i class="fa fa-bars" aria-hidden="true"></i>
				</div>
			</div>
            <!-- 顶级菜单区域 -->
            <div class="layui-larry-menu">
                 <ul class="layui-nav clearfix">
                       <li class="layui-nav-item layui-this">
                 	   	   <a href="<?php echo U('index/index');?>"><i class="iconfont icon-wangzhanguanli"></i>后台管理系统</a>
                 	   </li>
                 	   <!--<li class="layui-nav-item">
                 	   	   <a href="/"><i class="iconfont icon-weixin3"></i>微信公众</a>
                 	   </li>
                 	   <li class="layui-nav-item">
                 	   	   <a href="/"><i class="iconfont icon-ht_expand"></i>扩展模块</a>
                 	   </li>-->
                 </ul>
            </div>
            <!-- 右侧导航 -->
            <ul class="layui-nav larry-header-item">
            		<li class="layui-nav-item">
            			账户名：<?php echo ($admin["name"]); ?>
            		</li>
            		<li class="layui-nav-item first">
						<a href="/">			
							<cite>默认站点</cite>
							<span class="layui-nav-more"></span>
						</a>
						<dl class="layui-nav-child">
							<dd>
								<a href="">站点1</a>
							</dd>
		
							
						</dl>
					</li>
					<li class="layui-nav-item">
						<a href="<?php echo U('login/logout');?>">
                        <i class="iconfont icon-exit"></i>
						退出</a>
					</li>
            </ul>
		</div>
	</div>
	<!-- 左侧侧边导航开始 -->
	<div class="layui-side layui-side-bg layui-larry-side" id="larry-side">
        <div class="layui-side-scroll" id="larry-nav-side" lay-filter="side">
		
		<!-- 左侧菜单 -->
		<ul class="layui-nav layui-nav-tree">
			
			<!-- 个人信息 -->
           <?php if(in_array('12',$str)): ?><li class="layui-nav-item">
				<a href="javascript:;">
					<i class="iconfont icon-jiaoseguanli" ></i>
					<span>会员管理</span>
					<em class="layui-nav-more"></em>
				</a>
				<dl class="layui-nav-child">
                    <dd>
                        <a href="javascript:;" data-url="<?php echo U('user/alist');?>">
                            <i class="iconfont icon-geren1" data-icon='icon-geren1'></i>
                            <span>会员管理</span>
                        </a>
                    </dd>
                   <dd>
					    		<a href="javascript:;"  data-url="<?php echo U('user/add');?>">
					    		   <i class="iconfont icon-jiaoseguanli4" data-icon='icon-jiaoseguanli4'></i>
					    		   <span>会员添加</span>
					    		</a>
					    	</dd>
                </dl>
			</li><?php endif; ?>
             	
                
              
           <?php if(in_array('15',$str)): ?><!-- 用户管理 -->
			<li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-jiaoseguanli2" ></i>
					   <span>轮播图管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					    <dl class="layui-nav-child">
					    	<dd>
					    		<a href="javascript:;" data-url="<?php echo U('banner/alist');?>">
					    		   <i class="iconfont icon-yonghu1" data-icon='icon-yonghu1'></i>
					    		   <span>轮播图列表</span>
					    		</a>
					    	</dd>
					    	<dd>
					    		<a href="javascript:;"  data-url="<?php echo U('banner/add');?>">
					    		   <i class="iconfont icon-jiaoseguanli4" data-icon='icon-jiaoseguanli4'></i>
					    		   <span>轮播图添加</span>
					    		</a>
					    	</dd>
                           
					    	
					    </dl>
			    </li><?php endif; ?>
               <?php if(in_array('20',$str)): ?><!-- 用户管理 -->
			<li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-lanmuguanli" ></i>
					   <span>充值管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					    <dl class="layui-nav-child">
					    	
					    	<dd>
					    		<a href="javascript:;"  data-url="<?php echo U('taocan/alist');?>">
					    		   <i class="iconfont icon-jiaoseguanli4" data-icon='icon-jiaoseguanli4'></i>
					    		   <span>充值套餐</span>
					    		</a>
					    	</dd>
                            <dd>
					    		<a href="javascript:;" data-url="<?php echo U('taocan/add');?>">
					    		   <i class="iconfont icon-yonghu1" data-icon='icon-yonghu1'></i>
					    		   <span>新增充值</span>
					    		</a>
					    	</dd>
					    </dl>
			    </li><?php endif; ?>
             <?php if(in_array('45',$str)): ?><!-- 用户管理 -->
			<li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-wenzhang2" ></i>
					   <span>分类管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					    <dl class="layui-nav-child">
					    	<dd>
					    		<a href="javascript:;" data-url="<?php echo U('fenlei/alist');?>">
					    		   <i class="iconfont icon-yonghu1" data-icon='icon-yonghu1'></i>
					    		   <span>分类列表</span>
					    		</a>
					    	</dd>
					    	<dd>
					    		<a href="javascript:;"  data-url="<?php echo U('fenlei/classadd');?>">
					    		   <i class="iconfont icon-jiaoseguanli4" data-icon='icon-jiaoseguanli4'></i>
					    		   <span>分类添加</span>
					    		</a>
					    	</dd>
					    </dl>
			    </li><?php endif; ?>
          
          <?php if(in_array('50',$str)): ?><li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-m-members" ></i>
					   <span>域名管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					<dl class="layui-nav-child">
                           <dd>
                           	   <a href="javascript:;" data-url="<?php echo U('Href/alist');?>">
					              <i class="iconfont icon-zhuti"  data-icon='icon-zhuti'></i>
					              <span>域名列表</span>
					           </a>
                           </dd>
                           <dd>
					    		<a href="javascript:;"  data-url="<?php echo U('href/add');?>">
					    		   <i class="iconfont icon-jiaoseguanli4" data-icon='icon-jiaoseguanli4'></i>
					    		   <span>域名添加</span>
					    		</a>
					    	</dd>
                    </dl>
                    
				</li><?php endif; ?>
           <?php if(in_array('33',$str)): ?><li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-shengchengbaogao" ></i>
					   <span>视频管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					   <dl class="layui-nav-child">
                           <dd>
                           	   <a href="javascript:;"  data-url="<?php echo U('video/alist');?>">
					              <i class="iconfont icon-zhuti"  data-icon='icon-zhuti'></i>
					              <span>视频列表</span>
					           </a>
                           </dd>
                           <dd>
					    		<a href="javascript:;"   data-url="<?php echo U('video/add');?>">
					    		   <i class="iconfont icon-database" data-icon='icon-database'></i>
					    		   <span>视频添加</span>
					    		</a>
					    	</dd>
                            
					    	<dd>
                           	   <a href="javascript:;"  data-url="/excel/user.php">
					              <i class="iconfont icon-zhuti"  data-icon='icon-zhuti'></i>
					              <span>视频导入</span>
					           </a>
                           </dd>
					   </dl>
				</li><?php endif; ?>
                <?php if(in_array('33',$str)): ?><li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-shengchengbaogao" ></i>
					   <span>漫画管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					   <dl class="layui-nav-child">
                           <dd>
                           	   <a href="javascript:;"  data-url="<?php echo U('cartoon/alist');?>">
					              <i class="iconfont icon-zhuti"  data-icon='icon-zhuti'></i>
					              <span>漫画列表</span>
					           </a>
                           </dd>
                           <dd>
					    		<a href="javascript:;"   data-url="<?php echo U('cartoon/add');?>">
					    		   <i class="iconfont icon-database" data-icon='icon-database'></i>
					    		   <span>漫画添加</span>
					    		</a>
					    	</dd>
                            <!-- <dd>
                           	   <a href="javascript:;"  data-url="<?php echo U('cartoon/zhangjie');?>">
					              <i class="iconfont icon-zhuti"  data-icon='icon-zhuti'></i>
					              <span>章节列表</span>
					           </a>
                           </dd>
                           <dd>
					    		<a href="javascript:;"   data-url="<?php echo U('cartoon/zjadd');?>">
					    		   <i class="iconfont icon-database" data-icon='icon-database'></i>
					    		   <span>章节添加</span>
					    		</a>
					    	</dd>-->
					    	
					   </dl>
				</li><?php endif; ?>
                 <?php if(in_array('43',$str)): ?><li class="layui-nav-item">
                        <a href="javascript:;" data-url="<?php echo U('comment/alist');?>">
                           <i class="iconfont icon-wenzhang1" ></i>
                           <span>评论管理</span>
                           
                        </a>
                           
                   </li><?php endif; ?>
               <?php if(in_array('50',$str)): ?><li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-m-members" ></i>
					   <span>财务管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					<dl class="layui-nav-child">
                   		    <dd>
					    		<a href="javascript:;" data-url="<?php echo U('Pay/hplist');?>">
					    		   <i class="iconfont icon-zhandianpeizhi" data-icon='icon-zhandianpeizhi'></i>
					    		   <span>支付设置</span>
					    		</a>
					    	</dd>
                           <dd>
                           	   <a href="javascript:;" data-url="<?php echo U('Pay/alist');?>">
					              <i class="iconfont icon-zhuti"  data-icon='icon-zhuti'></i>
					              <span>购买记录</span>
					           </a>
                           </dd>
                           <dd style="display:none">
					    		<a href="javascript:;"  data-url="<?php echo U('Pay/tixian');?>">
					    		   <i class="iconfont icon-jiaoseguanli4" data-icon='icon-jiaoseguanli4'></i>
					    		   <span>提现管理</span>
					    		</a>
					    	</dd>
                    </dl>
                    
				</li><?php endif; ?>
           <?php if(in_array('1',$str)): ?><!-- 系统设置 -->
			<li class="layui-nav-item">
					<a href="javascript:;">
					   <i class="iconfont icon-xitong" ></i>
					   <span>管理员管理</span>
					   <em class="layui-nav-more"></em>
					</a>
					    <dl class="layui-nav-child">
					    	<dd>
					    		<a href="javascript:;" data-url="<?php echo U('power/info');?>">
					    		   <i class="iconfont icon-zhandianpeizhi" data-icon='icon-zhandianpeizhi'></i>
					    		   <span>管理员列表</span>
					    		</a>
					    	</dd>
					    	<dd>
					    		<a href="javascript:;" data-url="<?php echo U('power/add');?>">
					    		   <i class="iconfont icon-zhandianguanli1" data-icon='icon-zhandianguanli1'></i>
					    		   <span>管理员添加</span>
					    		</a>
					    	</dd>
					    	<dd>
					    		<a href="javascript:;" data-url="<?php echo U('power/pwdedit');?>">
					    		   <i class="iconfont icon-zhandianguanli1" data-icon='icon-zhandianguanli1'></i>
					    		   <span>密码修改</span>
					    		</a>
					    	</dd>
					    </dl>
				</li><?php endif; ?>
           <?php if(in_array('28',$str)): ?><li class="layui-nav-item">
					<a href="javascript:;" >
					   <i class="iconfont icon-qingchuhuancun"  data-icon='icon-qingchuhuancun'></i>
					   <span>系统设置</span>
					 <em class="layui-nav-more"></em>
					</a>
                    <dl class="layui-nav-child">
                    
                   		  <dd>
					    		<a href="javascript:;" data-url="<?php echo U('shezhi/kefu');?>">
					    		   <i class="iconfont icon-iconfuzhi01" data-icon='icon-iconfuzhi01'></i>
					    		   <span>客服管理</span>
					    		</a>
					    	</dd>
                            <dd>
					    		<a href="javascript:;" data-url="<?php echo U('shezhi/banben');?>">
					    		   <i class="iconfont icon-iconfuzhi01" data-icon='icon-iconfuzhi01'></i>
					    		   <span>APP版本管理</span>
					    		</a>
					    	</dd>
                            <dd>
					    		<a href="javascript:;" data-url="<?php echo U('shezhi/yindao');?>">
					    		   <i class="iconfont icon-iconfuzhi01" data-icon='icon-iconfuzhi01'></i>
					    		   <span>引导文字</span>
					    		</a>
					    	</dd>
                    	   <dd>
					    		<a href="javascript:;" data-url="<?php echo U('shezhi/article');?>">
					    		   <i class="iconfont icon-iconfuzhi01" data-icon='icon-iconfuzhi01'></i>
					    		   <span>关于我们</span>
					    		</a>
					    	</dd>
                              <dd>
					    		<a href="javascript:;" data-url="<?php echo U('shezhi/tuiguang');?>">
					    		   <i class="iconfont icon-iconfuzhi01" data-icon='icon-iconfuzhi01'></i>
					    		   <span>推广说明</span>
					    		</a>
					    	</dd>
                            <dd>
					    		<a href="javascript:;" data-url="<?php echo U('shezhi/loglist');?>">
					    		   <i class="iconfont icon-iconfuzhi01" data-icon='icon-iconfuzhi01'></i>
					    		   <span>日志管理</span>
					    		</a>
					    	</dd>
                            <dd style="display:none">
					    		<a href="javascript:;" data-url="<?php echo U('shezhi/system');?>">
					    			<i class='iconfont icon-SQLServershujuku' data-icon='icon-SQLServershujuku'></i>
					    			<span>系统参数设置</span>
					    		</a>
					    	</dd>
					    	
                    </dl>
				</li><?php endif; ?>	
		</ul>
	    </div>
	</div>

	<!-- 左侧侧边导航结束 -->
	<!-- 右侧主体内容 -->
	<div class="layui-body" id="larry-body" style="bottom: 0;border-left: solid 2px #2299ee;">
		<div class="layui-tab layui-tab-card larry-tab-box" id="larry-tab" lay-filter="demo" lay-allowclose="true">
			<div class="go-left key-press pressKey" id="titleLeft" title="滚动至最右侧"><i class="larry-icon larry-weibiaoti6-copy"></i> </div>
			<ul class="layui-tab-title">
				<li class="layui-this" id="admin-home"><i class="iconfont icon-diannao1"></i><em>后台首页</em></li>
			</ul>
			<div class="go-right key-press pressKey" id="titleRight" title="滚动至最左侧"><i class="larry-icon larry-right"></i></div> 
			<ul class="layui-nav closeBox">
				  <li class="layui-nav-item">
				    <a href="javascript:;"><i class="iconfont icon-caozuo"></i> 页面操作</a>
				    <dl class="layui-nav-child">
					  <dd><a href="javascript:;" class="refresh refreshThis"><i class="layui-icon">&#x1002;</i> 刷新当前</a></dd>
				      <dd><a href="javascript:;" class="closePageOther"><i class="iconfont icon-prohibit"></i> 关闭其他</a></dd>
				      <dd><a href="javascript:;" class="closePageAll"><i class="iconfont icon-guanbi"></i> 关闭全部</a></dd>
				    </dl>
				  </li>
				</ul>
			<div class="layui-tab-content" style="min-height: 150px; ">
				<div class="layui-tab-item layui-show">
					<iframe class="larry-iframe" data-id='0' src="<?php echo U('index/main');?>" ></iframe>
				</div>
			</div>
		</div>
	</div>
	<!-- 底部区域 -->
	<div class="layui-footer layui-larry-foot" id="larry-footer">
		<div class="layui-mian">  
		    <p class="p-admin">
		    	<span>2017 &copy;</span>
		    	 版权所有
		    </p>
		</div>
	</div>
</div>
<!-- 加载js文件-->                                                                                                                                                                                           
	<script type="text/javascript" src="/Public/admin/common/layui/layui.js"></script> 
	<script type="text/javascript" src="/Public/admin/js/larry.js"></script>
	<script type="text/javascript" src="/Public/admin/js/index.js"></script>
<!-- 锁屏 -->
<div class="lock-screen" style="display: none;">
	<div id="locker" class="lock-wrapper">
		<div id="time"></div>
		<div class="lock-box center">
			<img src="images/userimg.jpg" alt="">
			<h1>admin</h1>
			<duv class="form-group col-lg-12">
				<input type="password" placeholder='锁屏状态，请输入密码解锁' id="lock_password" class="form-control lock-input" autofocus name="lock_password">
				<button id="unlock" class="btn btn-lock">解锁</button>
			</duv>
		</div>
	</div>
</div>

</body>
</html>