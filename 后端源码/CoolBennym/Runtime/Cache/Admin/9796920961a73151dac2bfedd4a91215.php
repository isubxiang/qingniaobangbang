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
.inpt-button-tudou{height: 30px;line-height:30px;background:rgb(0, 153, 204); width:70px;text-align: center;}
.weixin{ background:#44b549!important;}
</style>

<div class="tu-main-top-btn">
    <ul>
        <li class="li1">会员管理</li>
        <li class="li2">会员提现</li>
        <li class="li2 li3">提现管理</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
	<p class="attention"><span>注意：</span>新版提现管理可以查询，会员总提现【已审】：<?php echo ($user_cash); ?>元！会员提现佣金【已审】：<?php echo ($user_cash_commission); ?>元，需要微信提现</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('usercash/index');?>">
                    <div class="seleHidden" id="seleHidden">
                    	<div class="seleK">
                    	 <label>
                                <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                                <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text " />
                                <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                            </label>
                         <label>
                         <span>状态：</span>
                         <select class="select w120" name="st">
                             <option <?php if(($st) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                             <option <?php if(($st) == "0"): ?>selected="selected"<?php endif; ?>  value="0">未审核</option>
                             <option <?php if(($st) == "1"): ?>selected="selected"<?php endif; ?>  value="1">已审核</option>
                             <option <?php if(($st) == "2"): ?>selected="selected"<?php endif; ?>  value="2">已拒绝</option>
                          </select>
                          
                          </label>
                         <span>提现方式：</span>
                         <select class="select w120" name="code">
                             <option <?php if(($code) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                             <option <?php if(($code) == "weixin"): ?>selected="selected"<?php endif; ?>  value="weixin">微信</option>
                             <option <?php if(($code) == "bank"): ?>selected="selected"<?php endif; ?>  value="bank">银行卡</option>
                             <option <?php if(($code) == "alipay"): ?>selected="selected"<?php endif; ?>  value="alipay">支付宝</option>
                          </select>
                          </label>
                          
                         <label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                            </label>
                         <label> 
                          
                        <label>
                            <span>账户</span>
                            <input type="text" name="account" placeholder=" 提现账户" value="<?php echo ($account); ?>" class="tu-inpt-text" />
                            <span>ID</span>
                            <input type="text" name="cash_id" placeholder=" 提现订单ID" value="<?php echo ($cash_id); ?>" class="tu-inpt-text" />
                            <input type="submit" value="搜索"  class="inpt-button-tudou" />
                        </label>
                    </div> 
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
         
    <form target="x-frame" method="post">
        <div class="tu-table-box">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td class="w50"><input type="checkbox" class="checkAll" rel="cash_id" /></td>
                    <td class="w50">ID</td>
                    <td>学校ID</td>
                    <td>会员昵称</td>
                    <td>会员ID</td>
                    <td>账户信息</td>
                    <td>真实姓名</td>
                    <td>提现金额</td>
                    <td>提现手续费</td>
                    <td>提现方式</td>
                    <td>申请日期</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_cash_id" type="checkbox" name="cash_id[]" value="<?php echo ($var["cash_id"]); ?>" <?php if ($var['status'] != 0){echo 'disabled="disabled"';}?> /></td>
                        <td><?php echo ($var["cash_id"]); ?></td>
                        <td><?php echo ($var["school_id"]); ?></td>
                        <td><?php echo ($var["account"]); ?></td>
                        <td><?php echo ($var["user_id"]); ?></td>
                        <td><p><?php echo ($var["info"]); ?></p></td>
                        <td>
                           <?php if($var['code'] == weixin): echo ($var["re_user_name"]); endif; ?>
                           <?php if($var['code'] == alipay): ?>支付宝账户：<?php echo ($var["alipay_account"]); ?>/<?php echo ($var["alipay_real_name"]); endif; ?>
                        </td>
                        <td>&yen;<?php echo ($var['money'] / 100); ?> 元</td>
                        <td><?php if(!empty($var['commission'])): ?>&yen; <?php echo round($var['commission']/100,2);?>元<?php else: ?>无<?php endif; ?></td>
                        <td>
                        	<?php if($var['code'] == weixin): ?>微信<?php endif; ?>
                        	<?php if($var['code'] == bank): ?>银行卡<?php endif; ?>
                            <?php if($var['code'] == alipay): ?>支付宝<?php endif; ?>
                        </td>
                        <td><?php echo (date("Y-m-d H:i:s",$var["addtime"])); ?></td>
                        <td>
                            <?php if($var["status"] == 0): ?>未审核
                            <?php elseif($var["status"] == 1): ?>
                                <font color="green">已通过</font>
                            <?php else: ?>
                                <font color="red">已拒绝</font><?php endif; ?>
                       </td>
                    <td>
                        <?php if($var["status"] == 0): if($var['code'] == weixin): echo BA('usercash/weixin_audit',array("cash_id"=>$var["cash_id"], "status" => 1),'微信转账','act','tu-dou-btn weixin'); endif; ?>
                            
                            <?php if($var['code'] == alipay): echo BA('usercash/alipay_audit',array("cash_id"=>$var["cash_id"], "status" => 1),'支付宝转账','act','tu-dou-btn weixin'); endif; ?>
                            
                            <?php if($var['code'] == bank): echo BA('usercash/bank_audit',array("cash_id"=>$var["cash_id"], "status" => 1),'通过','act','tu-dou-btn'); endif; ?>
                            <?php echo BA('usercash/audit',array("cash_id"=>$var["cash_id"], "status" => 1),'强制审核','act','tu-dou-btn');?>
                            <a class="tu-dou-btn jujue"  href="javascript:void(0);" rel="<?php echo ($var["cash_id"]); ?>">拒绝</a>
                        <?php else: ?>
                            已完成<?php endif; ?>
                        <?php echo BA('usermoneylogs/index',array("user_id"=>$var["user_id"]),'余额日志','','tu-dou-btn-small-waring');?>
                    </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <script src="__PUBLIC__/js/layer/layer.js"></script>
        <script>
            $(document).ready(function(){
                layer.config({
                    extend: 'extend/layer.ext.js'
                });
                $(".jujue").click(function () {
                    var cash_id = $(this).attr('rel');
                    var url = "<?php echo U('usercash/jujue');?>";
                    layer.prompt({formType: 2, value: '', title: '请输入退款理由，并确认'}, function (value){
                        if (value != "" && value != null) {
                            $.post(url,{cash_id: cash_id, status: 2,value:value},function(data){
                                if(data.status == 'success'){
                                    layer.msg(data.msg,{icon: 1});
                                    setTimeout(function(){
                                        window.location.reload(true);
                                    },1000)
                                }else{
                                    layer.msg(data.msg, {icon: 2});
                                }
                            }, 'json')
                        } else {
                            layer.msg('请填写拒绝理由', {icon: 2});
                        }       
                    });
                })
            })
        </script>
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
        </div>
    </form>
</div>
</div>
  		</div>
	</body>
</html>