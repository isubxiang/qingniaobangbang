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

.tu-left-td{width: 200px;}
.sogn{ height:30px}
.profit{text-align:center; color:#000; font-size:18px; font-weight:bold; background:#ECECEC;}
.main-tudou-sc-add .tu-table-box .table2 tr td{text-align: center;}

</style>



<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">基本设置</li>
        <li class="li2 li3">小程序配置</li>
    </ul>
</div>

<p class="attention"><span>注意：</span>默认小程序配置，不懂联系qq120-585-022</p>
<form  target="x-frame" action="<?php echo U('setting/wxapp');?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;">
            
            	<tr>
                  <td class="tu-right-td profit" colspan="2">默认同城小程序配置</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">默认小程序appid：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[appid]" value="<?php echo ($CONFIG['wxapp']['appid']); ?>" class="tudou-manageInput w150"/>
                        <code>默认小程序appid</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">默认小程序appsecret：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[appsecret]" value="<?php echo ($CONFIG['wxapp']['appsecret']); ?>" class="tudou-manageInput w360"/>
                        <code>默认小程序appsecret</code>
                    </td>
                </tr>
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2">广告位ID设置，广告位ID在广告位列表获取，这里不是广告ID是广告位ID</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">拼车广告位ID：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[pinche_site_id]" value="<?php echo ($CONFIG['wxapp']['pinche_site_id']); ?>" class="tudou-manageInput w150"/>
                        <code>拼车广告位ID</code>
                    </td>
                </tr>
                 <tr>
                    <td class="tu-left-td">拼团广告位ID：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[collage_site_id]" value="<?php echo ($CONFIG['wxapp']['collage_site_id']); ?>" class="tudou-manageInput w150"/>
                        <code>拼团广告位ID</code>
                    </td>
                </tr>
               
                <tr>
                    <td class="tu-left-td">贴吧信息广告位ID：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[thread_site_id]" value="<?php echo ($CONFIG['wxapp']['thread_site_id']); ?>" class="tudou-manageInput w150"/>
                        <code>贴吧信息广告位ID</code>
                    </td>
                </tr>
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2">首页弹窗设置</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">是否开启首页弹窗：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_index_pop]" <?php if(($CONFIG["wxapp"]["is_index_pop"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_index_pop]" <?php if(($CONFIG["wxapp"]["is_index_pop"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>开启后小程序首页有弹窗</code>
                    </td>
			    </tr>
                
                <tr>
                    <td class="tu-left-td">首页导航图片模式：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_index_category_type]" <?php if(($CONFIG["wxapp"]["is_index_category_type"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_index_category_type]" <?php if(($CONFIG["wxapp"]["is_index_category_type"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>默认文字模式，开启图片模式后需要在跑腿分类那边设置分类图标否则图片不显示</code>
                    </td>
			    </tr>
                
                
                <tr>
                    <td class="tu-left-td">
                        首页弹窗文字：
                    </td>
                    <td class="tu-right-td">
                        <textarea name="data[is_index_pop_title]" cols="50" rows="5"><?php echo ($CONFIG["wxapp"]["is_index_pop_title"]); ?></textarea>
                        <code>小程序首页弹窗文字</code>
                    </td>
                </tr>
                
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2">小程序其他文字设置，比如会员中心，首页，认证界面等等</td>
                </tr>
                
               
                
               
                <tr>
                    <td class="tu-left-td">全局学校文字：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[titleName]" value="<?php echo ($CONFIG['wxapp']['titleName']); ?>" class="tudou-manageInput w260"/>
                        <code>全局的学校名字可自定义，默认是学校</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">全局同学文字：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tongxueName]" value="<?php echo ($CONFIG['wxapp']['tongxueName']); ?>" class="tudou-manageInput w260"/>
                        <code>全局的同学名字可自定义，默认是同学</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">全局配送员文字：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[peisongName]" value="<?php echo ($CONFIG['wxapp']['peisongName']); ?>" class="tudou-manageInput w260"/>
                        <code>全局的配送员名字可自定义，默认是跑腿同学</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">全局赏金文字：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[moneyName]" value="<?php echo ($CONFIG['wxapp']['moneyName']); ?>" class="tudou-manageInput w260"/>
                        <code>小程序所有赏金的文字，可修改为，佣金，跑腿费，等等，不超过3字，不要用赏金，否则会审核不过</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">首页导航提示语：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[index_nav_name]" value="<?php echo ($CONFIG['wxapp']['index_nav_name']); ?>" class="tudou-manageInput w260"/>
                        <code>首页导航提示语，默认文字：【想让同学帮你干点什么？】</code>
                    </td>
                </tr>
                
                
                
                
                <tr>
                    <td class="tu-left-td">跑腿认证界面身份证号名字：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[idCard_code_name]" value="<?php echo ($CONFIG['wxapp']['idCard_code_name']); ?>" class="tudou-manageInput w260"/>
                        <code>跑腿认证界面身份证号名字，默认文字：【身份证号】</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">跑腿认证界面身份证号placeholder：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[idCard_code_placeholder]" value="<?php echo ($CONFIG['wxapp']['idCard_code_placeholder']); ?>" class="tudou-manageInput w260"/>
                        <code>跑腿认证界面身份证号placeholder，默认文字：【你的身份证号码18位】</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">跑腿认证界面身份证号位数：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[idCard_code_num]" value="<?php echo ($CONFIG['wxapp']['idCard_code_num']); ?>" class="tudou-manageInput w80"/>
                        <code>跑腿认证界面身份证号位数，【默认限制数量18位】</code>
                    </td>
                </tr>
               
                
                <tr>
                    <td class="tu-left-td">跑腿认证界面上传收款二维码：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_auth_pay_code]" <?php if(($CONFIG["wxapp"]["is_auth_pay_code"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_auth_pay_code]" <?php if(($CONFIG["wxapp"]["is_auth_pay_code"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>开启后小程序跑腿认证上传收款二维码，【默认关闭】</code>
                    </td>
			    </tr>
                
                
                <tr>
                    <td class="tu-left-td">是否显示学号：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_studentCard_code]" <?php if(($CONFIG["wxapp"]["is_studentCard_code"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭显示</label>
                    <label><input type="radio" name="data[is_studentCard_code]" <?php if(($CONFIG["wxapp"]["is_studentCard_code"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启显示</label>
                    <code>开启后小程序跑腿认证是否显示学号，【默认关闭】</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">是否显示院系：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_studentCard_faculty]" <?php if(($CONFIG["wxapp"]["is_studentCard_faculty"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭显示</label>
                    <label><input type="radio" name="data[is_studentCard_faculty]" <?php if(($CONFIG["wxapp"]["is_studentCard_faculty"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启显示</label>
                    <code>开启后小程序跑腿认证是否显示院系，【默认关闭】</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">是否显示专业：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_studentCard_major]" <?php if(($CONFIG["wxapp"]["is_studentCard_major"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭显示</label>
                    <label><input type="radio" name="data[is_studentCard_major]" <?php if(($CONFIG["wxapp"]["is_studentCard_major"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启显示</label>
                    <code>开启后小程序跑腿认证是否显示专业，【默认关闭】</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">是否显示入校年月：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_studentCard_enrollmentDate]" <?php if(($CONFIG["wxapp"]["is_studentCard_enrollmentDate"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭显示</label>
                    <label><input type="radio" name="data[is_studentCard_enrollmentDate]" <?php if(($CONFIG["wxapp"]["is_studentCard_enrollmentDate"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启显示</label>
                    <code>开启后小程序跑腿认证是否显示入校年月，【默认关闭】</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">是否显示上传证件：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_logo]" <?php if(($CONFIG["wxapp"]["is_logo"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭显示</label>
                    <label><input type="radio" name="data[is_logo]" <?php if(($CONFIG["wxapp"]["is_logo"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启显示</label>
                    <code>开启后小程序跑腿认证是否显示上传证件，【默认开启】这里必选选择开启，无法关闭所以这里设置没用</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">上传证件显示名称：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[is_logo_name]" value="<?php echo ($CONFIG['wxapp']['is_logo_name']); ?>" class="tudou-manageInput w80"/>
                        <code>跑腿认证界面上传证件显示名称，【默认上传学生证】</code>
                    </td>
                </tr>
                
                
              
                <tr>
                  <td class="tu-right-td profit" colspan="2">【小程序会员中心模块开启关闭开关】</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">开启拼团菜单：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_group_open]" <?php if(($CONFIG["wxapp"]["is_group_open"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_group_open]" <?php if(($CONFIG["wxapp"]["is_group_open"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>开启后小程序会员中心出现我的拼团菜单</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">开启拼车菜单：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_pinche_open]" <?php if(($CONFIG["wxapp"]["is_pinche_open"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_pinche_open]" <?php if(($CONFIG["wxapp"]["is_pinche_open"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>开启后小程序会员中心出现我的拼车菜单</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">开启贴吧菜单：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_thread_open]" <?php if(($CONFIG["wxapp"]["is_thread_open"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_thread_open]" <?php if(($CONFIG["wxapp"]["is_thread_open"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>开启后小程序会员中心出现我的贴吧菜单</code>
                    </td>
			    </tr>
                
                <tr>
                    <td class="tu-left-td">开启优惠券菜单：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_coupon_open]" <?php if(($CONFIG["wxapp"]["is_coupon_open"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_coupon_open]" <?php if(($CONFIG["wxapp"]["is_coupon_open"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>开启后小程序会员中心出现我的优惠券菜单</code>
                    </td>
			    </tr>
                
                <tr>
                    <td class="tu-left-td">开启我的海报菜单：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_canvas_open]" <?php if(($CONFIG["wxapp"]["is_canvas_open"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_canvas_open]" <?php if(($CONFIG["wxapp"]["is_canvas_open"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>开启后小程序会员中心出现我的我的海报菜单</code>
                    </td>
			    </tr>
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2">【小程序信息方便的一些配置这里是贴吧】</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">信息开启自动定位：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_tzdz]" <?php if(($CONFIG["wxapp"]["is_tzdz"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_tzdz]" <?php if(($CONFIG["wxapp"]["is_tzdz"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>小程序里面发布信息是否开启自动定位</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td" style="color:#F00">是否开启信息审核：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_thread_fabu_audit]" <?php if(($CONFIG["wxapp"]["is_thread_fabu_audit"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>开启审核</label>
                    <label><input type="radio" name="data[is_thread_fabu_audit]" <?php if(($CONFIG["wxapp"]["is_thread_fabu_audit"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>关闭审核</label>
                    <code style="color:#F00">关闭审核后，用户发布的信息直接显示，但是不利于审核信息，建议不要开启，这里不选择默认需要后台管理员审核</code>
                    </td>
			    </tr>
                
                <tr>
                    <td class="tu-left-td">信息开启置顶苹果支付：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_pgzf]" <?php if(($CONFIG["wxapp"]["is_pgzf"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>默认</label>
                    <label><input type="radio" name="data[is_pgzf]" <?php if(($CONFIG["wxapp"]["is_pgzf"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>关闭</label>
                    <label><input type="radio" name="data[is_pgzf]" <?php if(($CONFIG["wxapp"]["is_pgzf"]) == "2"): ?>checked="checked"<?php endif; ?> value="2"/>开启</label>
                    <code>小程序信息里面不开启置顶收费2开启1不开启，是否开启苹果支付，审核期间请关闭置顶功能</code>
                    </td>
			    </tr>
                
                <tr>
                    <td class="tu-left-td">置顶费用：</td>
                    <td class="tu-right-td">
                    	<code>置顶一天</code>
                        <input type="text" name="data[top_type_1]" value="<?php echo ($CONFIG['wxapp']['top_type_1']); ?>" class="tudou-sc-add-text-name w80"/>元
                        <code>置顶一周</code>
                        <input type="text" name="data[top_type_2]" value="<?php echo ($CONFIG['wxapp']['top_type_2']); ?>" class="tudou-sc-add-text-name w80"/>元
                        <code>置顶一月</code>
                        <input type="text" name="data[top_type_3]" value="<?php echo ($CONFIG['wxapp']['top_type_3']); ?>" class="tudou-sc-add-text-name w80"/>元
                        <code>请从前面填写到后面，不填写前台不显示，只填写最后一个前面的已不显示</code>
                    </td>
                </tr> 
                <tr>
                    <td class="tu-left-td">显示是否拨打电话：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_bdtel]" <?php if(($CONFIG["wxapp"]["is_bdtel"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <label><input type="radio" name="data[is_bdtel]" <?php if(($CONFIG["wxapp"]["is_bdtel"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <code>贴吧分类信息开启电话图标显示是否拨打电话</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">图标显示图标样式：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_ff]" <?php if(($CONFIG["wxapp"]["is_ff"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>样式1</label>
                    <label><input type="radio" name="data[is_ff]" <?php if(($CONFIG["wxapp"]["is_ff"]) == "2"): ?>checked="checked"<?php endif; ?> value="2"/>样式2</label>
                    <code>贴吧分类信息开启电话图标显示图标样式</code>
                    </td>
			    </tr>
                <tr>
                    <td class="tu-left-td">贴吧的名称：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tzmc]" value="<?php echo ($CONFIG['wxapp']['tzmc']); ?>" class="tudou-sc-add-text-name w80"/>
                        <code>贴吧的名称</code>
                    </td>
                </tr> 
                
                
                
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2">【免费模板消息配置】1：如果您小程序跟公众号对接了开放平台选择公众号模板消息2：如果是2019年12月后新安装选择订阅消息3:2019年12月之前已经配置模板消息客户选择模板消息</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">消息通知模式：</td>
                    <td class="tu-right-td">
                        <select name="data[tpmlMsgType]"  class="tudou-sc-add-text-name sogn jq_type">
                            <option value="0" <?php if($CONFIG['wxapp']['tpmlMsgType'] == 0): ?>selected='selected'<?php endif; ?>>==模板消息==</option>
                            <option value="1" <?php if($CONFIG['wxapp']['tpmlMsgType'] == 1): ?>selected='selected'<?php endif; ?>>==订阅消息==</option>
                            <option value="2" <?php if($CONFIG['wxapp']['tpmlMsgType'] == 2): ?>selected='selected'<?php endif; ?>>==订阅消息+公众号模板消息==</option>
                        </select>
                        <code>2019年12月后请选择订阅消息【因为微信规则，一次只能推送一条】，如果您对接了开放平台，请选择公众号模板消息（如果是公众号模板消息请一定要小程序跟您公众号对接开放平台并在同一个主体下面）</code></td>
                </tr>
                
                
                
                <tr class="jq_type_0">
                    <td  class="tu-left-td">订单状态变动通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[formid1]" value="<?php echo ($CONFIG['wxapp']['formid1']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code style="color:red">搜索：订单状态变动通知 ID：AT0176  添加类型：订单号、订单状态、订单内容、备注、更新时间</code>
                    </td>
                </tr>
                <tr class="jq_type_0">
                    <td  class="tu-left-td">网络信息到达提醒 ：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[formid2]" value="<?php echo ($CONFIG['wxapp']['formid2']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code style="color:red">搜索：网络信息到达提醒 ID：AT0480  添加类型：信息编号、信息类型、用户昵称、信息内容、通知时间</code>
                    </td>
                </tr>
                
                <tr class="jq_type_0">
                    <td  class="tu-left-td">帖子评论成功通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tid3]" value="<?php echo ($CONFIG['wxapp']['tid3']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code>*AT0813,关键词(评论内容,评论人,评论时间)</code>
                    </td>
                </tr>
                <tr class="jq_type_0">
                    <td  class="tu-left-td">评论回复通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[hp_tid]" value="<?php echo ($CONFIG['wxapp']['hp_tid']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code>AT1235,关键词(备注,回复者,回复内容,评论时间,评论内容)</code>
                    </td>
                </tr>
                <tr class="jq_type_0">
                    <td  class="tu-left-td">帖子被赞通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[z_tid]" value="<?php echo ($CONFIG['wxapp']['z_tid']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code>*AT1250,关键词(点赞人,点赞时间,主题)</code>
                    </td>
                </tr>
                <tr class="jq_type_0">
                    <td  class="tu-left-td">帖子审核通过通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tg_tid]" value="<?php echo ($CONFIG['wxapp']['tg_tid']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code>*AT0168,关键词(审核结果,帖子主题,会员昵称,发布时间,备注)</code>
                    </td>
                </tr>
                
             
                <tr class="jq_type_1">
                    <td  class="tu-left-td">订单配送通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tid1]" value="<?php echo ($CONFIG['wxapp']['tid1']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code style="color:red">类目：线下超市/便利店 搜索：订单配送通知  添加类型：订单编号、配送人、配送员电话、商品信息、下单时间【配送员接单通知用户】</code>
                    </td>
                </tr>
                
                 <tr class="jq_type_1">
                    <td  class="tu-left-td">订单送达成功通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tid2]" value="<?php echo ($CONFIG['wxapp']['tid2']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code style="color:red">类目：快递、物流 搜索：订单完成通知  添加类型：订单号、订单状态、接单人、联系电话、送达时间【配送员完成通知用户】</code>
                    </td>
                </tr>
                 <tr class="jq_type_1">
                    <td  class="tu-left-td">订单完成通知：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tid3]" value="<?php echo ($CONFIG['wxapp']['tid3']); ?>" class="tudou-sc-add-text-name w360"/>
                        <code style="color:red">类目：快递、物流 搜索：订单完成通知  添加类型：订单号、订单状态、订单类型、温馨提示、完成时间【用户确认完成订单通知用户】</code>
                    </td>
                </tr>
               
                
               
                        
            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确认保存" class="sm-tudou-btn-input" /></div>
    </div>
</form>

 <script>
$(document).ready(function(){
    $(".jq_type").change(function(){
	  var tpmlMsgType = $(this).val();
      if(tpmlMsgType == '0'){
          $(".jq_type_0").show();
          $(".jq_type_1").hide();
		  $(".jq_type_2").hide();
      }else if(tpmlMsgType == '1'){
          $(".jq_type_0").hide();
          $(".jq_type_1").show();
		  $(".jq_type_2").show();
      }else if(tpmlMsgType == '2'){
          $(".jq_type_0").hide();
          $(".jq_type_1").show();
		  $(".jq_type_2").show();
      }
   });
   $(".jq_type").change();
});
</script> 
        
  		</div>
	</body>
</html>