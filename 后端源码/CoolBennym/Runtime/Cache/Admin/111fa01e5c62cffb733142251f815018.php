<?php if (!defined('THINK_PATH')) exit();?><div class="listBox clfx">
    <div class="menuManage">
        <form  target="x-frame" action="<?php echo U('user/money',array('user_id'=>$user_id));?>" method="post">
            <div class="main-tudou-sc-add">
                <div class="tu-table-box">
                    <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                        <tr>
                            <td class="tu-left-td">余额：</td>
                            <td class="tu-right-td">
                                <input name="money" type="text" class="tudou-sc-add-text-name w150" />
                                <code>减少余额输入负数</code>
                            </td>
                        </tr>
                        <tr >
                            <td class="tu-left-td">原由：</td>
                            <td class="tu-right-td">
                                <textarea name="intro" cols="50" rows="6"></textarea>
                            </td>
                        </tr>

                    </table>
                </div>
                <div class="sm-qr-tu"><input type="submit" value="确定保存" class="sm-tudou-btn-input" /></div>
            </div>
        </form>
    </div>
</div>