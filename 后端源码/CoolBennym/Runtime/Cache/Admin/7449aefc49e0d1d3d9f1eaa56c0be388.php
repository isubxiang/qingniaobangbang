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
.tu-left-td{ width: 200px;}
.profit{text-align:center; color:#000; font-weight:bold; background:#ECECEC;}
.sogn{ height:30px}
</style>

<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">常用功能</li>
        <li class="li2 li3">功能设置</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>这里配置的是网站常用API，以及其他的设置，以后不知道分来的尽量写在这里</p>
</div>       
<div class="main-tudou-sc-add">
    <div class="tu-table-box">
        <form  target="x-frame" action="<?php echo U('setting/config');?>" method="post">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
             	<tr>
                  <td class="tu-right-td profit" colspan="2"> 网站地图配置</td>
                </tr>
                <tr>
                    <td class="tu-left-td">网站地图选择：</td>
                    <td class="tu-right-td">
                          <select name="data[map]" class="tudou-sc-add-text-name sogn">
                              <option value="">请选择地图地图</option>
                              <option <?php if(($CONFIG["config"]["map"]) == "1"): ?>selected="selected"<?php endif; ?> value="1">百度地图</option>
                              <option <?php if(($CONFIG["config"]["map"]) == "2"): ?>selected="selected"<?php endif; ?> value="2">谷歌地图</option>
                          </select>
                        <code>新版必须选择地图，否则失效，一旦选择数据将不可逆更改，请第一次就选择好地图，否则中途更换地图后数据混乱后概不负责</code>
                    </td>
                </tr>  
                
                
                
                
                
                
                <tr>
                    <td  class="tu-left-td" >百度LBS地图API链接：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[baidu_map_api]" value="<?php echo ($CONFIG["config"]["baidu_map_api"]); ?>" class="tudou-sc-add-text-name  tudou-manageInput2" />
                        <code>如果您开启的是https，这里请一定要填写https开头，这里是填写整个百度地图链接，http或者https您自己填写，接口申请 <a href="http://lbsyun.baidu.com/" target="_blank">http://lbsyun.baidu.com/</a> 备用API：https://api.map.baidu.com/api?v=2.0&ak=7b92b3afff29988b6d4dbf9a00698ed8</code>
                    </td>
                </tr>
                
                <tr>
                    <td  class="tu-left-td" >谷歌LBS地图API链接：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[google_map_api]" value="<?php echo ($CONFIG["config"]["google_map_api"]); ?>" class="tudou-sc-add-text-name  tudou-manageInput2" />
                        <code>这里是填写整个谷歌地图链接，http或者https您自己填写</code>
                    </td>
                </tr> 
                
                
                <tr>
                	<td class="tu-right-td profit" colspan="2"> 快递接口设置</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">快递接口选择：</td>
                    <td class="tu-right-td">
                        <select name="data[express_api_type]" class="tudou-sc-add-text-name sogn">
                            <option value="">请选择快递接口</option>
                            <option <?php if(($CONFIG["config"]["express_api_type"]) == "1"): ?>selected="selected"<?php endif; ?> value="1">快递100免费接口</option>
                            <option <?php if(($CONFIG["config"]["express_api_type"]) == "2"): ?>selected="selected"<?php endif; ?> value="2">快递100企业接口</option>
                            <option <?php if(($CONFIG["config"]["express_api_type"]) == "3"): ?>selected="selected"<?php endif; ?> value="3">快递鸟接口</option>
                        </select>
					    <code>请先选择接口类型然后再填写快递100的api</code>
                    </td>
                </tr>   
              
                
                <tr>
                    <td class="tu-left-td" >快递100key/customer：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[express_api_key]" value="<?php echo ($CONFIG["config"]["express_api_key"]); ?>" class="tudou-sc-add-text-name"/>
                        <code>←这里填写KEY</code>
                        <input type="text" name="data[express_api_customer]" value="<?php echo ($CONFIG["config"]["express_api_customer"]); ?>" class="tudou-sc-add-text-name"/>
                        <code>←这里填写customer,免费版本接口无需填写customer  接口申请：<a href="https://www.kuaidi100.com/openapi/applypoll.shtml" target="_blank">https://www.kuaidi100.com/openapi/applypoll.shtml</a></code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td" >快递鸟物流查询api：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[niao_app_id]" value="<?php echo ($CONFIG["config"]["niao_app_id"]); ?>" class="tudou-sc-add-text-name"/>
                        <code>←这里填写appid</code>
                        <input type="text" name="data[niao_app_key]" value="<?php echo ($CONFIG["config"]["niao_app_key"]); ?>" class="tudou-sc-add-text-name"/>
                        <code>←这里填写appkey  接口申请：<a href="http://www.kdniao.com/UserCenter" target="_blank">http://www.kdniao.com/UserCenter</a>这里用于查询物流信息，已可单独申请已可以跟下面的信息一致</code>
                    </td>
                </tr>
                
                 <tr>
                    <td class="tu-left-td" >快递鸟电子面单api：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[EBusinessID]" value="<?php echo ($CONFIG["config"]["EBusinessID"]); ?>" class="tudou-sc-add-text-name"/>
                        <code>←这里填写EBusinessID</code>
                        <input type="text" name="data[AppKey]" value="<?php echo ($CONFIG["config"]["AppKey"]); ?>" class="tudou-sc-add-text-name"/>
                        <code>←这里填写AppKey  接口申请：<a href="http://kdniao.com/reg" target="_blank">请到快递鸟官网申请http://kdniao.com/reg</a>申请后请实名认证然后申请KEY后即可打印，测试KEY请选择下面的测试环境</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">快递鸟电子面单api打印模式：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[ReqURLType]" <?php if(($CONFIG["config"]["ReqURLType"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>测试环境</label>
                    <label><input type="radio" name="data[ReqURLType]" <?php if(($CONFIG["config"]["ReqURLType"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>正式环境</label>
                    <code>开启测试环境只能测试使用，不能正式打印，适合调试系统用，上线后请开通正式打印环境模式，默认正式环境</code>
                    </td>
                </tr>
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2"> 跑腿系统分销配置</td>
                </tr>
                
                <tr>
				<td class="tu-left-td">分销海报：</td>
				<td class="tu-right-td">
					<div style="width: 300px;height:150px; float: left;">
						<input type="hidden" name="data[poster]" value="<?php echo ($CONFIG["config"]["poster"]); ?>" id="data_poster"/>
						<div id="fileToUpload">
							上传分销海报280*260
						</div>
					</div>
					<div style="width:300px;height:150px;float:left;">
						<img id="poster_img" width="200" height="120" src="<?php echo config_img($CONFIG[config][poster]);?>"/>
					</div>
					<script>                                            
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>""));?>',                             
						pick: '#fileToUpload',                             
						resize: true,  
					});                                                 
					uploader.on('uploadSuccess',function(file,resporse){                             
						$("#data_poster").val(resporse.url);                             
						$("#poster_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on( 'uploadError', function(file){                             
						alert('上传出错');                         
					});                     
                    </script>
				</td>
               
                <tr>
                    <td  class="tu-left-td">个人中心海报文字1：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[text1]" value="<?php echo ($CONFIG["config"]["text1"]); ?>" class="tudou-sc-add-text-name w160"/>
                        <code>个人中心海报文字1，12个字以内</code>
                    </td>
                </tr> 
                <tr>
                    <td  class="tu-left-td">个人中心海报文字2：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[text2]" value="<?php echo ($CONFIG["config"]["text2"]); ?>" class="tudou-sc-add-text-name w160"/>
                        <code>个人中心海报文字2，8个字以内</code>
                    </td>
                </tr> 
                
                <tr>
                    <td class="tu-left-td">开启三级分销功能：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[is_profit]" <?php if(($CONFIG["config"]["is_profit"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启分销</label>
                    <label><input type="radio" name="data[is_profit]" <?php if(($CONFIG["config"]["is_profit"]) == "0"): ?>checked="checked"<?php endif; ?>  value="0"/>关闭分销</label>
                    <code>开启后下面的设置才生效</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">跑腿订单一级订单分成比例：</td>
                    <td class="tu-right-td"><input type="number" name="data[running_profit_rate1]" value='<?php echo ($CONFIG["config"]["running_profit_rate1"]); ?>' class="tudou-sc-add-text-name w80"/>% 
                    <code>跑腿订单一级会员分成比例，不分成填写0</code></td>
                </tr>
                <tr>
                    <td class="tu-left-td">跑腿订单二级订单分成比例：</td>
                    <td class="tu-right-td"><input type="number"  name="data[running_profit_rate2]" value='<?php echo ($CONFIG["config"]["running_profit_rate2"]); ?>' class="tudou-sc-add-text-name w80"/>% 
                    <code>跑腿订单二级会员分成比例，不分成填写0</code></td>
                </tr>

                <tr>
                    <td class="tu-left-td">跑腿订单三级订单分成比例：</td>
                    <td class="tu-right-td"><input type="number" name="data[running_profit_rate3]" value='<?php echo ($CONFIG["config"]["running_profit_rate3"]); ?>' class="tudou-sc-add-text-name w80"/>% 
                    <code>跑腿订单三级会员分成比例，不分成填写0</code></td>
                </tr>
                
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2"> 拼车设置</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">拼车发布价格：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[fabu_money]" value="<?php echo ($CONFIG["config"]["fabu_money"]); ?>" class="tudou-sc-add-text-name w80" />
                        <code>小程序中发布拼车需要支付多少钱才才能发布成功</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">拼车申明：</td>
                    <td class="tu-right-td">
                        <textarea name="data[explain]" cols="60" rows="5"><?php echo ($CONFIG["config"]["explain"]); ?></textarea>
                        <code>小程序中发布拼车时候点击申明弹窗出现的内容</code>
                    </td>
                </tr>
              
                <tr>
                    <td class="tu-left-td">拼车置顶价格：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[top]" value="<?php echo ($CONFIG["config"]["top"]); ?>" class="tudou-sc-add-text-name w80" />
                        <code>拼车会员中心置顶扣费，置顶拼车优先显示，必须填写置顶价格，支持小数点，不能为空</code>
                    </td>
                </tr>
                
                
               
                <tr>
                  <td class="tu-right-td profit" colspan="2"> iocnfont图标调用连接</td>
                </tr>
                
                 <tr>
                    <td  class="tu-left-td" >iocnfont链接：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[iocnfont]" value="<?php echo ($CONFIG['config']['iocnfont']); ?>" class="tudou-sc-add-text-name  tudou-manageInput2"/>
                        <code>
                        配置您在iconfont网站申请的图标连接，用来显示全站图标  //at.alicdn.com/t/font_295173_xnubd8xdu6czyqfr.css
                        <a target="_blank" href="http://www.iconfont.cn">点击申请</a>
                        iocnfont连接是什么？
                        </code>
                    </td>
                </tr>
                
                
                
                 <tr>
                    <td  class="tu-left-td" >iocnfont2链接：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[iocnfont2]" value="<?php echo (($CONFIG['config']['iocnfont2'])?($CONFIG['config']['iocnfont2']):""); ?>" class="tudou-sc-add-text-name  tudou-manageInput2" />
                        <code>这里你额外增加的css阿里云的iocnfont图标cnd连接放在这里，然后自己就可以在模板中调用了，这样您可以不管基础库的css</code>
                    </td>
                </tr>
                
                <tr>
                  <td class="tu-right-td profit" colspan="2"> 余额日志没月份的转换工具 <?php echo ($CONFIG["site"]["host"]); ?>/app/api/UserMoneyLogsMonth  余额日志月份不准确的时候点击下这里  </td>
                </tr>
                
                <tr>
                  <td class="tu-right-td profit" colspan="2"> 虚拟数据新增插件 <?php echo ($CONFIG["site"]["host"]); ?>/app/api/falseOeder  这个api请填写到宝塔面板计划任务那边</td>
                </tr>
                 <tr>
                  <td class="tu-right-td profit" colspan="2" style="color:#F00"> 
                      下面的|符号是英文小写状态的符号，切记，不懂的后面有说明  
                      <a mini="act" class="tu-dou-btn-small-waring" href="<?php echo U('app/api/falseOeder');?>">测试下</a>
                  </td>
                </tr>
                <tr>
                     <td class="tu-left-td">是否开启新增虚拟数据：</td>
                     <td class="tu-right-td">
                     <label><input type="radio" name="data[false_order_open]" <?php if(($CONFIG["config"]["false_order_open"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                     <label><input type="radio" name="data[false_order_open]" <?php if(($CONFIG["config"]["false_order_open"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                     <code>关闭后不执行</code>
                     </td>
			    </tr>
                <tr> 
                    <td class="tu-left-td">一次新增多少条：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[false_order_num]" value="<?php echo ($CONFIG["config"]["false_order_num"]); ?>" class="tudou-sc-add-text-name w80"/>
						<code>执行一次新增多少条数据</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">虚拟会员ID批量添加：</td>
                    <td class="tu-right-td">
                        <textarea name="data[false_order_user_id]" cols="60" rows="4"><?php echo ($CONFIG["config"]["false_order_user_id"]); ?></textarea>
                        <code> 请到会员列表复制会员ID用|间隔 【建议添加的会员有地址最好】比如：【1|2|3|4】 <a target="_blank" href="<?php echo U('user/index');?>">会员列表</a></code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">虚拟学校ID批量添加：</td>
                    <td class="tu-right-td">
                        <textarea name="data[false_order_school_id]" cols="60" rows="4"><?php echo ($CONFIG["config"]["false_order_school_id"]); ?></textarea>
                        <code> 请到学校列表复制会员ID用|间隔 比如：【1|2|3|4】  <a target="_blank" href="<?php echo U('running/school');?>">学校列表</a></code>
                    </td>
                </tr> 
                
                 <tr>
                    <td class="tu-left-td">虚拟配送员ID批量添加：</td>
                    <td class="tu-right-td">
                        <textarea name="data[false_order_delivery_id]" cols="60" rows="4"><?php echo ($CONFIG["config"]["false_order_delivery_id"]); ?></textarea>
                        <code> 请到配送员列表复制会员ID用|间隔 比如：【1|2|3|4】  <a target="_blank" href="<?php echo U('running/delivery');?>">配送员列表</a></code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">虚拟下单标题批量添加：</td>
                    <td class="tu-right-td">
                        <textarea name="data[false_order_title]" cols="80" rows="15"><?php echo ($CONFIG["config"]["false_order_title"]); ?></textarea>
                        <code> 下单的标题|间隔  比如：【我要下单|我要买东西】 </code>
                    </td>
                </tr> 
                 <tr> 
                    <td class="tu-left-td">虚拟下单跑腿费：</td>
                    <td class="tu-right-td">
                    	<code>虚拟下单跑腿费最低多少钱</code>
                        <input type="text" name="data[false_order_money_mix]" value="<?php echo ($CONFIG["config"]["false_order_money_mix"]); ?>" class="tudou-sc-add-text-name w80"/>
						<code>虚拟下单跑腿费最高多少钱</code>
                        <input type="text" name="data[false_order_money_big]" value="<?php echo ($CONFIG["config"]["false_order_money_big"]); ?>" class="tudou-sc-add-text-name w80"/>
                    </td>
                </tr>
                        
                 
               
            </table>
            <div class="sm-qr-tu"><input type="submit" value="确认添加" class="sm-tudou-btn-input" /></div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>