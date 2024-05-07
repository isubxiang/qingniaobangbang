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
.tu-left-td{width:200px;}
.profit{text-align:center; color:#000; font-size:18px; font-weight:bold; background:#ECECEC;}
</style>

<div class="tu-main-top-btn">
    <ul>
        <li class="li1">跑腿信息</li>
        <li class="li2">跑腿管理</li>
        <li class="li2 li3">编辑</li>
    </ul>
</div>
<div class="main-tudou-sc-add ">
    <div class="tu-table-box">
        <form target="x-frame" action="<?php echo U('runningcate/edit',array('cate_id'=>$detail['cate_id']));?>" method="post">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                <tr>
                    <td  class="tu-left-td">所属频道：</td>
                    <td class="tu-right-td"><select class="tu-manage-select w200" name="data[channel_id]">
                            <?php if(is_array($channelmeans)): foreach($channelmeans as $key=>$item): ?><option value="<?php echo ($key); ?>" <?php if(($detail["channel_id"]) == $key): ?>selected="selected"<?php endif; ?> ><?php echo ($item); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">选择学校：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="school_id" name="data[school_id]" value="<?php echo (($detail["school_id"])?($detail["school_id"]):''); ?>"/>
                            <input class="tudou-sc-add-text-name w210 sj" type="text" name="Name" id="Name"  value="<?php echo ($school["Name"]); ?>"/>
                        </div>
                        <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="seleSj">选择学校</a>
                        <code>中途不要选择学校，请注意编辑</code>
                    </td>
                </tr> 
                
                
                <tr>
                    <td  class="tu-left-td">跑腿名称：</td>
                    <td class="tu-right-td"><input type="text" name="data[cate_name]" value="<?php echo (($detail["cate_name"])?($detail["cate_name"]):''); ?>" class="tudou-manageInput w200" />
                    </td>
                </tr>
                
                
                
                 <tr>
                        <td class="tu-left-td">分类图标：</td>
                        <td class="tu-right-td">
                        <div style="width: 300px;height: 100px; float: left;">
                            <input type="hidden" name="data[photo]" value="<?php echo ($detail["photo"]); ?>" id="data_photo"/>
                            <div id="fileToUpload" >分类图标</div>
                        </div>
                        <div style="width: 300px;height: 100px; float: left;">
                            <img id="photo_img" width="120" height="80"  src="<?php echo config_img($detail['photo']);?>" />
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
                            $("#data_photo").val(resporse.url);                             
                            $("#photo_img").attr('src',resporse.url).show();                         
                        });                                                
                        uploader.on('uploadError',function(file){                             
                            alert('上传出错');                         
                        });                     
                        </script>
                        <code>可传可不传，如果您小程序设置里面设置的是图片模式，这里就必须上传了，图片尺寸80*80px记住单张图片容量不要太大，推荐3KB</code>
                    </td>
                </tr>
                
                
                
                <tr>
                    <td  class="tu-left-td">简单介绍：</td>
                    <td class="tu-right-td"><input type="text" name="data[Detail]" value="<?php echo (($detail["Detail"])?($detail["Detail"]):''); ?>" class="tudou-manageInput w200" />
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">错误类型：</td>
                    <td class="tu-right-td"><input type="text" name="data[ErrandType]" value="<?php echo (($detail["ErrandType"])?($detail["ErrandType"]):'0'); ?>" class="tudou-manageInput w200" />
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">标记：</td>
                    <td class="tu-right-td"><input type="text" name="data[DefaultRemark]" value="<?php echo (($detail["DefaultRemark"])?($detail["DefaultRemark"]):''); ?>" class="tudou-manageInput w200" />
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">标签：</td>
                    <td class="tu-right-td"><input type="text" name="data[Tag]" value="<?php echo (($detail["Tag"])?($detail["Tag"]):''); ?>" class="tudou-manageInput w200" />
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">Url：</td>
                    <td class="tu-right-td"><input style="width:800px" type="text" name="data[Url]" value="<?php echo (($detail["Url"])?($detail["Url"]):''); ?>" class="tudou-manageInput"/>
                     <code style="color:#F00">分类格式列如：【/pages/errand/apply/index?type=23340&remark=请直接将取件短信粘贴此处。示例：【菜鸟驿站】取件码8888&start=去哪里取件&end=到哪里送货】 这里的type就是当前分类的ID，后面的remark为描述，start为开始地址，end为到货地址说明</code><br>
                    <code>多商家格式列如：【/pages/shop/_/index?type=1】  后面这个1数字就是外卖分类列表的ID分别是1-8的数字不要乱写</code><br>
                    <code>单商家格式列如：【/pages/shop/_/index?id=1】  后面这个1数字就是外卖商家的ID，请不要写错，否则打不开</code><br>
                    <code>外卖商家列表：【/pages/shop/_/list?id=1】  后面这个1数字就是外卖分类的ID，请不要写错，否则打不开</code><br>
                    <code>拼车：【/pages/shun/shun?id=1】 固定格式请勿修改</code><br>
                    <code>拼团：【/pages/collage/index?id=1 】固定格式请勿修改</code><br>
                    <code>信息：【/pages/thread/index?id=1】 固定格式请勿修改，如果设置tabBar请勿填写该链接</code><br>
                    <code>个人中心推广海报：【/pages/mine/canvas/canvas?id=1】 固定格式请勿修改</code><br>
                    <code>配送人员入驻：【/pages/mine/info/auth?id=1】 固定格式请勿修改</code><br>
                    <code>优惠券首页：【/pages/coupon/index?id=1】 固定格式请勿修改</code><br>
                    <code>优惠券详情：【/pages/coupon/info?id&yhqid=*&sjid=0=1】 固定格式请勿修改*用优惠券ID代替</code><br>
                    <code>外卖商家列表：【/pages/shop/_/list?id=1】 固定格式请勿修改</code><br>
                    <code>外卖商家列表带分类ID：【/pages/shop/_/list?id=1&cateId=*&name=*】 固定格式请勿修改cateId分类ID，name分类名称</code><br>
                    </td>
                </tr>
                
                <tr>
                    <td  class="tu-left-td">分类配送天数：</td>
                    <td class="tu-right-td"><input type="text" name="data[ErrandTimeRangeDays]" value="<?php echo (($detail["ErrandTimeRangeDays"])?($detail["ErrandTimeRangeDays"]):'1'); ?>" class="tudou-manageInput w200" />
                    <code>当前分类用户下单的时候可选择预定天数，默认1天，最多3天，请填写1-3的范围【注意：最多填写数字1-3范围区间】</code>
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">分类配送开始时间：</td>
                    <td class="tu-right-td"><input type="text" name="data[ErrandTimeRangeBegin]" value="<?php echo (($detail["ErrandTimeRangeBegin"])?($detail["ErrandTimeRangeBegin"]):'8:00'); ?>" class="tudou-manageInput w200" />
                    <code>当前分类配送开始时间，请注意时间格式，英文状态下的小写才行，默认：8:00【一定要注意格式不要写错】</code>
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">分类配送结束时间：</td>
                    <td class="tu-right-td"><input type="text" name="data[ErrandTimeRangeEnd]" value="<?php echo (($detail["ErrandTimeRangeEnd"])?($detail["ErrandTimeRangeEnd"]):'22:00'); ?>" class="tudou-manageInput w200" />
                    <code>当前分类配送结束时间，请注意时间格式，英文状态下的小写才行，默认：22:00【一定要注意格式不要写错】</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">是否显示垫付费用：</td>
                    <td class="tu-right-td">
                        <label><input type="radio" <?php if($detail['onMoneyTap'] == 0) echo "checked='checked'";?> name="data[onMoneyTap]" value="0" />不显示</label>
                        <label><input type="radio" <?php if($detail['onMoneyTap'] == 1) echo "checked='checked'";?> name="data[onMoneyTap]" value="1"  />显示</label>
                        &nbsp;&nbsp;<code>用户下单时候是否显示垫付费用</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">是否显示运费【类目】：</td>
                    <td class="tu-right-td">
                        <label><input type="radio" <?php if($detail['onExpressFeeLink'] == 0) echo "checked='checked'";?> name="data[onExpressFeeLink]" value="0" />不显示</label>
                        <label><input type="radio" <?php if($detail['onExpressFeeLink'] == 1) echo "checked='checked'";?> name="data[onExpressFeeLink]" value="1"  />显示</label>
                        &nbsp;&nbsp;<code>用户下单时候是否显示运费【类目】</code>
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">运费【类目】说明文字：</td>
                    <td class="tu-right-td"><input type="text" name="data[onExpressFeeLinkName]" value="<?php echo (($detail["onExpressFeeLinkName"])?($detail["onExpressFeeLinkName"]):''); ?>" class="tudou-manageInput w360"/>
                    &nbsp;&nbsp;<code>运费【类目】说明文字</code>
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td">运费【类目】连接文章ID：</td>
                    <td class="tu-right-td"><input type="text" name="data[onExpressFeeLinkId]" value="<?php echo (($detail["onExpressFeeLinkId"])?($detail["onExpressFeeLinkId"]):''); ?>" class="tudou-manageInput w80"/>
                    &nbsp;&nbsp;<code>运费【类目】连接文章ID，这里文章ID千万不要些错误，否则打开失败</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">是否显示上传文件：</td>
                    <td class="tu-right-td">
                        <label><input type="radio" <?php if($detail['onFile'] == 0) echo "checked='checked'";?> name="data[onFile]" value="0" />不显示</label>
                        <label><input type="radio" <?php if($detail['onFile'] == 1) echo "checked='checked'";?> name="data[onFile]" value="1"  />显示</label>
                        &nbsp;&nbsp;<code>用户下单时候是否显示上传文件按钮，已便于文件上传</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td  class="tu-left-td">提示说明：</td>
                    <td class="tu-right-td"><input type="text" name="data[Remark]" value="<?php echo (($detail["Remark"])?($detail["Remark"]):''); ?>" class="tudou-manageInput w360"/>
                    </td>
                </tr>
                
                
                <tr>
                    <td  class="tu-left-td">分类结算佣金：</td>
                    <td class="tu-right-td"><input type="text" name="data[rate]" value="<?php echo ($detail['rate']); ?>" class="tudou-manageInput w200"/>%
                    <code>填写整数数字，如果这里留空或者填写0则启用 跑腿》》基本设置》》跑腿设置》》结算佣金 里面的百分比</code>
                    <code>平台抽成佣金规则  分类设置佣金 > 学校独立设置佣金  > 系统全局设置佣金 比如：分类设置了1%那么就按照分类佣金，分类没有设置，则判断学校佣金，学校设置0%则启用系统全局佣金，如果系统全局佣金0%则不提成</code>
                    </td>
                </tr>
                
                <tr>
                    <td  class="tu-left-td">最低运费：</td>
                    <td class="tu-right-td"><input type="text" name="data[price]" value="<?php echo round($detail['price']/100,2);?>" class="tudou-manageInput w200"/>
                    <code>支持小数点，这里分类不设置按照基本设置为准</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">是否显示：</td>
                    <td class="tu-right-td">
                        <label><input type="radio" <?php if($detail['is_show'] == 0) echo "checked='checked'";?> name="data[is_show]" value="0"/>显示</label>
                        <label><input type="radio" <?php if($detail['is_show'] == 1) echo "checked='checked'";?> name="data[is_show]" value="1"/>隐藏</label>
                        &nbsp;&nbsp;<code>审核小程序期间有用，当前类目菜单是否在首页显示，审核小程序期间可影藏，审核用过后在开启显示即可</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">是否系统功能：</td>
                    <td class="tu-right-td">
                        <label><input type="radio" <?php if($detail['is_system'] == 0) echo "checked='checked'";?> name="data[is_system]" value="0"/>否</label>
                        <label><input type="radio" <?php if($detail['is_system'] == 1) echo "checked='checked'";?> name="data[is_system]" value="1"/>是</label>
                        &nbsp;&nbsp;<code>开启或者不开启是否系统功能，系统功能就是比如拼车拼团信息就属于系统功能，如果是系统功能就选择是，否则选择否</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td  class="tu-left-td">排序：</td>
                    <td class="tu-right-td"><input type="text" name="data[orderby]" value="<?php echo (($detail["orderby"])?($detail["orderby"]):'50'); ?>" class="tudou-manageInput w200" />
                        <code>数字越小越高</code>
                    </td>
                </tr>
            </table>
            <div class="sm-qr-tu"><input type="submit" value="确定编辑" class="sm-tudou-btn-input" /></div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>