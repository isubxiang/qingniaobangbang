<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title><?php echo ($CONFIG["site"]["sitename"]); ?>管理后台</title>
        <meta name="description" content="<?php echo ($CONFIG["site"]["sitename"]); ?>管理后台"/>
        <meta name="keywords" content="<?php echo ($CONFIG["site"]["sitename"]); ?>管理后台"/>
        <link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="__PUBLIC__/js/jquery-ui.css" rel="stylesheet" type="text/css"/>
        
        
        <script> 
			var TU_PUBLIC = '__PUBLIC__'; 
			var TU_ROOT = '__ROOT__'; 
        </script>
        <script src="__PUBLIC__/js/jquery.js"></script>
        <script src="__PUBLIC__/js/jquery-ui.min.js"></script>
        <script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
        <script src="/Public/js/layer/layer.js"></script>
        <script src="__PUBLIC__/js/admin.js"></script>
        <script src="__PUBLIC__/js/echarts-all-3.js"></script>
        
        <link rel="stylesheet" type="text/css" href="/static/default/webuploader/webuploader.css">
		<script src="/static/default/webuploader/webuploader.min.js"></script>
    </head>
    
    
    </head>
<style type="text/css">
#ie9-warning{ background:#F00; height:38px; line-height:38px; padding:10px;
position:absolute;top:0;left:0;font-size:12px;color:#fff;width:97%;text-align:left; z-index:9999999;}
#ie6-warning a {text-decoration:none; color:#fff !important;}
</style>

<!--[if lte IE 9]>
<div id="ie9-warning">您正在使用 Internet Explorer 9以下的版本，请用谷歌浏览器访问后台、部分浏览器可以开启极速模式访问！不懂点击这里！" target="_blank">查看为什么？</a>
</div>
<script type="text/javascript">
function position_fixed(el, eltop, elleft){  
       // check if this is IE6  
       if(!window.XMLHttpRequest)  
              window.onscroll = function(){  
                     el.style.top = (document.documentElement.scrollTop + eltop)+"px";  
                     el.style.left = (document.documentElement.scrollLeft + elleft)+"px";  
       }  
       else el.style.position = "fixed";  
}
       position_fixed(document.getElementById("ie9-warning"),0, 0);
</script>
<![endif]-->


    <body>
         <iframe id="x-frame" name="x-frame" style="display:none;"></iframe>
   <div class="main">


<style>
.tu-19-ditu-bg{width:100%;overflow: hidden;background:#f1f1f1;padding:10px; margin:10px;}		
.tudou-19forum{margin:0 5px 5px 0;float:left;width:210px;background:#fff;overflow: hidden;}		
.tudou-19forum-one{margin:0 5px 5px 0;float:left;width:640px;background:#fff;overflow: hidden;}	
.tudou-19forum-one .tudou-19forum-div{margin:10px;}
.tudou-19forum-one .tudou-19forum-title{height:53px;position:relative;}
.tudou-19forum-one .tudou-19forum-icon{width:48px;height:48px;position:absolute;top:0;right:0px;background:url(comiis_ico.gif) no-repeat 0 top;border-radius:5px;}
.tudou-19forum-one .tudou-19forum-title h2{height:26px;overflow:hidden;}
.tudou-19forum-one .tudou-19forum-title h2 a{color:#F00;font:100 22px/24px "Microsoft Yahei","SimHei";text-decoration:none;}
.tudou-19forum-one .tudou-19forum-title em{color:#F00;display:block;line-height:24px;height:24px;overflow: hidden;font-style: normal;}
.tudou-19forum-one .tudou-19forum-list{color:#999;overflow:hidden;}
.tudou-19forum-one .tudou-19forum-list a{font-size:12px !important;}
.tudou-19forum-one .tudou-19forum-list h3{line-height:22px;width:100%;margin-right:3px;float:left;height:22px;font-size:14px;overflow:hidden;font-weight:400;    color: #666;}
.tudou-19forum .tudou-19forum-list h3 a{font-size:12px;color: #666;}
.tudou-19forum .tudou-19forum-div{margin:10px;}
.tudou-19forum .tudou-19forum-title{height:53px;position:relative;}
.tudou-19forum .tudou-19forum-icon{width:48px;height:48px;position:absolute;top:0;right:0px;background:url(comiis_ico.gif) no-repeat 0 top;border-radius:5px;}
.tudou-19forum .tudou-19forum-title h2{height:26px;overflow:hidden;}
.tudou-19forum .tudou-19forum-title h2 a{color:#666;;font:100 22px/24px "Microsoft Yahei","SimHei";text-decoration:none;}
.tudou-19forum .tudou-19forum-title em{color:#999;display:block;line-height:24px;height:24px;overflow: hidden;font-style: normal;}
.tudou-19forum .tudou-19forum-list{color:#999;overflow:hidden;}
.tudou-19forum .tudou-19forum-list h3{line-height:22px;width:100%;margin-right:3px;float:left;height:22px;font-size:14px;overflow:hidden;font-weight:400;    color: #666;}
.tudou-19forum .tudou-19forum-list h3 a{font-size:12px;color: #666;}
.tudou-19forum-style1{width:377px}
.tudou-19forum-style1 .tudou-19forum-div{width:166px;height:142px;float:left;display:inline;}
.tudou-19forum-style1 .tudou-19forum-rightad{width:186px;height:162px;float:right;display:inline;overflow:hidden;}
.tudou-19forum_-tyle2{height:333px;}
.tudou-19forum_-tyle2 .tudou-19forum-div{width:166px;height:142px;}
.tudou-19forum_-tyle2 .tudou-19forum-bottomad{width:186px;height:164px;overflow:hidden;padding-top:5px;}
.tudou-19forum-style3{width:377px;height:333px;}
.tudou-19forum-style3 .tudou-19forum-div{width:357px;height:142px;}
.tudou-19forum-style3 .tudou-19forum-bottomad{width:377px;height:164px;overflow:hidden;padding-top:5px;}
.tudou-19forum-style3 .tudou-19forum_topad{position:absolute;top:0;right:50px;width:150px;height:48px;overflow:hidden;}
.tudou-19forum-style3 .tudou-19forum-list h3{width:86px;margin-right:3px;}
.tudou-19forum_top{border-top:#fff 2px solid;zoom:1;}
.comiis_hover .tudou-19forum-icon{background-position:0 bottom;}
.comiis_hover{box-shadow:0 0 6px rgba(50,50,50,0.3);}
.tu-19-ditu-bg .tudou-19forum .comiis_ad{padding:6px 8px 8px;}
.comiis-19ditu980 .tu-19-ditu-bg {width:975px;}
.comiis-19ditu980 .tudou-19forum{width:190px;}
.comiis-19ditu980 .tudou-19forum .tudou-19forum-list h3{width:82px;}
.red{ color:#F00 !important}
.tu-main-top-btn ul span.barcode{color:#FFF; padding:5px 15px; margin-right:40px; margin-left:200px; float:right}
.tu-main-top-btn ul span.barcode a.inpt-button-tudou{}

.main-sc .attention{padding-left:15px;}
.tu-table-box td {padding:5px;}
.attention{margin-bottom:20px;display:block;}
.attention2{margin:0px 0px 10px 20px;display:block;overflow: auto;}
.attention2 .tu-inpt-text{width:200px !important;height:28px;}
.attention2 .inpt-button-tudou{width:120px !important;}
.w1080{ width:100%; overflow:hidden; display:block;}

.paging span{
    font-size: 12px;
    line-height: inherit;
    height: inherit;
    padding: 0 5px;
}
.paging a {
    font-size: 12px;
    line-height: inherit;
    height: inherit;
    padding: 0 5px;
}
.current{line-height: inherit;height: inherit;padding: 0 5px;}
.attention{line-height: inherit;height: inherit;}
.tu-dou-btn-small-gray {
    font-size: 12px;
    margin-left: 5px;
    display: inline-block;
    margin-top: 5px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    background: #c3c3c3;
    color: #fff;
    padding: 2px 8px;
    height: 22px;
    line-height: 22px;
}

.main-tu-js b.profit{color:#F00;font-size: 14px;}

</style>



<div class="tu-main-top-btn">
    <ul>
        <li class="li1">系统首页</li>
        <li class="li2">后台首页</li>
        <li class="li2 li3">待办事项</li>
    </ul>
</div>


	
        

<div class="main-tu-js main-sc">
 
    <p class="attention">
        <a class="tu-dou-btn-small">快捷功能</a>
        <a class="tu-dou-btn-small-waring tudoukuaijie" href="<?php echo U('clean/cache');?>">清理系统日志</a>
        <a mini="act" class="tu-dou-btn-small-waring tudoukuaijie" href="<?php echo U('index/action_delete_all');?>">批量清空后台操作日志</a>
        <a class="tu-dou-btn-small-waring tudoukuaijie" href="<?php echo U('runningcate/index');?>">管理跑腿分类设置</a>
        <a class="tu-dou-btn-small-waring tudoukuaijie" href="<?php echo U('running/school');?>">管理学校列表</a>
        <a class="tu-dou-btn-small-waring tudoukuaijie" href="<?php echo U('running/delivery');?>">管理配送员</a>
        <a class="tu-dou-btn-small-waring tudoukuaijie" href="<?php echo U('user/index');?>">管理会员</a>
    </p>
    
    <p class="attention">
    	<a class="tu-dou-btn-small">订单统计</a>
        <a class="tu-dou-btn-small" href="<?php echo U('running/index',array('Type'=>2));?>">总跑腿订单<b class="b"><?php echo ($counts['running_Type_2_all']); ?></b></a>
        <a class="tu-dou-btn-small" href="<?php echo U('running/index',array('Type'=>1));?>">总外卖订单<b class="b"><?php echo ($counts['running_Type_1_all']); ?></b></a>
        <?php if(is_array($getOrderStatus)): foreach($getOrderStatus as $key=>$item): ?><a class="tu-dou-btn-small-waring tudoukuaijie" href="<?php echo U('running/index',array('OrderStatus'=>$item['id']));?>"><?php echo ($item["name"]); ?> <b class="b"> <?php echo ($item["count"]); ?></b></a><?php endforeach; endif; ?>
    </p>
    
    <p class="attention">
        <a class="tu-dou-btn-small">资金记录</a>
        <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('paymentlogs/index');?>">总付款总额 <b class="profit">&yen; <?php echo round($money['ok']/100,2);?> 元</b></a>
        <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('paymentlogs/index');?>">总退款总额 <b class="profit">&yen; <?php echo round($money['tui']/100,2);?>元</b></a>
        <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('paymentlogs/index');?>">今日付款总额 <b class="profit">&yen; <?php echo round($money['day_ok']/100,2);?> 元</b></a>
        <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('paymentlogs/index');?>">今日退款总额 <b class="profit">&yen; <?php echo round($money['day_tui']/100,2);?> 元</b></a>
        <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('paymentlogs/index');?>">总佣金 <b class="profit"> &yen; <?php echo round($counts['profit']/100,2);?>元</b></a>
        <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('paymentlogs/index');?>">今日总佣金 <b class="profit">&yen; <?php echo round($counts['day_profit']/100,2);?>元</b></a>
    </p>
    
    <?php if($admin["type"] == 2): ?><p class="attention">
            <a class="tu-dou-btn-small">学校站长分佣记录</a>
            <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('usermoneylogs/index',array('school_id'=>$SCHOOL['school_id'],'type'=>5));?>">今日站长分成总额 
            <b class="profit">&yen; <?php echo round($counts['day_city_money']/100,2);?> 元</b></a>
            <a class="tu-dou-btn-small-gray tudoukuaijie" href="<?php echo U('usermoneylogs/index',array('school_id'=>$SCHOOL['school_id'],'type'=>5));?>">站长分成总额 
            <b class="profit">&yen; <?php echo round($counts['city_money']/100,2);?> 元</b></a>
        </p><?php endif; ?>
    
    

<div class="comiis_19ditu">
<ul class="tu-19-ditu-bg cl masonry" style="position:relative;">

    <?php if($admin["type"] == 2): ?><li class="tudou-19forum tudou-19forum_style0 masonry-brick w360">
            <div class="tudou-19forum_top tudou-19forum_id1">
                <div class="tudou-19forum-div">
                    <div class="tudou-19forum-title">
                        <span class="tudou-19forum-icon"></span>
                        <h2><a href="">分站系统概况</a></h2>
                        <em>欢迎：<?php echo ($admin["username"]); ?>（<?php echo ($SCHOOL["Name"]); ?>）</em>
                    </div>
                    <div class="tudou-19forum-list">
                        <h3><a href="#">1：上次登录地址：<?php echo ($ad["last_ip"]); ?></a></h3>
                        <h3><a href="#">2： 学校名称：<?php echo ($SCHOOL["Name"]); ?></a></h3>
                        <h3><a href="#">3： 学校区域：<?php echo ($SCHOOL["Region"]); ?></a></h3>
                        <h3><a href="<?php echo U('running/delivery');?>">4：已审核配送员<?php echo ($counts["delivery_audit_2"]); ?>人</a></h3>
                        <h3><a href="<?php echo U('running/delivery');?>">5：未审核配送员<?php echo ($counts["delivery_audit_1"]); ?>人</a></h3>
                    </div>
                </div>
            </div>
        </li><?php endif; ?>
    
    
    
    <?php if($admin["type"] == 1): ?><li class="tudou-19forum tudou-19forum_style0 masonry-brick">
            <div class="tudou-19forum_top tudou-19forum_id1">
                <div class="tudou-19forum-div">
                    <div class="tudou-19forum-title">
                        <span class="tudou-19forum-icon"></span>
                        <h2><a href="">系统概况</a></h2>
                        <em>欢迎：<?php echo ($admin["username"]); ?>（<?php echo ($ROLE["role_name"]); ?>）</em>
                    </div>
                    <div class="tudou-19forum-list">
                        <h3><a href="#">1：登录地址：<?php echo ($ad["last_ip"]); ?></a></h3>
                        <h3><a href="#">2：更新到<?php echo ($v); ?></a></h3>
                        <h3><a href="#">3：php版本：<?php echo phpversion();?></a></h3>
                    </div>
                </div>
            </div>
        </li>
        <li class="tudou-19forum tudou-19forum_style0 masonry-brick">
            <div class="tudou-19forum_top tudou-19forum_id1">
                <div class="tudou-19forum-div">
                    <div class="tudou-19forum-title">
                        <span class="tudou-19forum-icon"></span>
                        <h2><a href="<?php echo U('user/index');?>">会员数据</a></h2>
                        <em>总：<?php echo ($counts["users"]); ?>个会员</em>
                    </div>
                    <div class="tudou-19forum-list">
                        <h3><a href="<?php echo U('user/index');?>" class="dot">1：今日新增<a class="red"><?php echo ($counts["totay_user"]); ?></a>个会员</a></h3>
                        <h3><a href="<?php echo U('user/index');?>">2：已有<?php echo ($counts["user_moblie"]); ?>人验证手机号</a></h3>
                        <h3><a href="<?php echo U('user/index');?>">3：微信登录<?php echo ($counts["user_weixin"]); ?>人</a></h3>
                        <h3><a href="<?php echo U('running/delivery');?>">4：已审核配送员<?php echo ($counts["delivery_audit_2"]); ?>人</a></h3>
                        <h3><a href="<?php echo U('running/delivery');?>">5：未审核配送员<?php echo ($counts["delivery_audit_1"]); ?>人</a></h3>
                    </div>
                </div>
            </div>
        </li><?php endif; ?>
        
        
         <li class="tudou-19forum tudou-19forum_style0 masonry-brick">
            <div class="tudou-19forum_top tudou-19forum_id1">
                <div class="tudou-19forum-div">
                    <div class="tudou-19forum-title">
                        <span class="tudou-19forum-icon"></span>
                        <h2><a href="#">数据统计</a></h2>
                        <em>会员总资金<?php echo round($counts['money_and']/100,2);?>元</em>
                    </div>
                    <div class="tudou-19forum-list">
                        <h3><a href="<?php echo U('usermoneylogs/index');?>">1：会员总资金<?php echo round($counts['money_and']/100,2);?>元</a></h3>
                        <h3><a href="<?php echo U('usercash/index');?>">2：总提现<a class="red"><?php echo round($counts['money_cash_day']/100,2);?></a>元</h3>
                        <h3><a href="<?php echo U('usercash/index');?>">3：提现待审核<a class="red"><a class="red"><?php echo ($counts['money_cash_audit']); ?></a>人待审</a></h3>
                        <h3><a href="<?php echo U('running/index');?>">4：今日订总订单<?php echo round($counts['runing']/100,2);?>单</a></h3>
                    </div>
                </div>
            </div>
        </li>
       
       
       
       
       <?php if($admin["type"] == 1): ?><li class="tudou-19forum tudou-19forum_style0 masonry-brick w360">
            <div class="tudou-19forum_top tudou-19forum_id1 w360">
                <div class="tudou-19forum-div">
                    <div class="tudou-19forum-title">
                        <span class="tudou-19forum-icon"></span>
                        <h2><a>操作日志</a></h2>
                        <em>这里记录后台操作日志</em>
                    </div>
                    <div class="tudou-19forum-list">
                        <?php if(is_array($action)): foreach($action as $key=>$var): ?><h3><a href="<?php echo U('index/action_delete',array('log_id'=>$var['log_id']));?>" mini="act"><?php echo ($var["log_id"]); ?>：<?php echo ($var["intro"]); ?> [删除]</a></h3><?php endforeach; endif; ?>    
                        <?php echo ($page2); ?>       
                   </div>
                </div>
            </div>
        </li><?php endif; ?>
        
 
 
 	

	
		<script src="__PUBLIC__/js/highcharts/highcharts.js"></script>
        <script src="__PUBLIC__/js/highcharts/modules/exporting.js"></script>
        <li class="tudou-19forum-one tudou-19forum_style0 masonry-brick  w1080">
        <div class="tudou-19forum_top tudou-19forum_id1 w1080">
            <div class="tudou-19forum-div">
                <script>
                    $(function (){
                        $('#container2').highcharts({
                            title:{
                            text: '<?php echo ($SCHOOL["Name"]); ?> 会员时间段（<?php echo ($bg_date); ?> - <?php echo ($end_date); ?>）内趋势',x: - 20},
                            subtitle:{
                            text: "<?php echo ($CONFIG['site']['sitename']); ?>",x: - 20},
                            xAxis:{
                            categories:[<?php echo ($data["day"]); ?>]},
                            yAxis:{title:{text:'单位人'},
                                plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                            },
                            series: [{
                            name: '<?php echo ($SCHOOL["Name"]); ?> 当日注册人数',
                            data: [<?php echo ($data["num"]); ?>]
                            }]
                        });
                    });
                </script>
                <div id="container2"></div>
                </div>
            </div>
        </li>
   


		</ul>
	<div class="cl"></div>
	</div>
</div>

 

  		</div>
	</body>
</html>