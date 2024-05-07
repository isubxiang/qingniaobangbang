var e = require("../../utils/service/api-service.js"), 
a = require("../../utils/service/pay-service.js"), 
t = require("../../utils/common-util.js"), 
r = getApp(),
u = (r.require("utils/util.js"), r.require("utils/api.js"));



Page({
    data: {
        recharge: "",
        rechargeText: "确定充值",
        tradeNo: "",
        agentDesc: ""
    },
    onLoad: function(e) {
        var t = r.isWx() ? "正在使用微信充值" : "正在使用QQ钱包充值";

        console.log('=onLoad===');
        console.log(r.isWx());

        e.recharge ? this.setData({
            recharge: e.recharge,
            agentDesc: t
        }) : this.setData({
            agentDesc: t
        }), a.payFn = "getPayResult", a.onBillSucc = this.onBillSucc, a.onBillFail = this.onBillFail;
    },
    onBillFail: function() {
        this.setData({
            tradeNo: ""
        }), this.rechargebtnMsg("确定充值");
    },
    onBillSucc: function() {
        var e = this;
        e.rechargebtnMsg("确定充值");
        var a = "./rechargesucc?tradeno=" + e.data.tradeNo + "&amount=" + e.data.recharge;
        wx.navigateTo({
            url: a
        });
    },
    onShow: function() {
        a.openPayResult();
    },
    rechargebtnMsg: function(e) {
        this.setData({
            rechargeText: e
        });
    },
    resetInfo: function(e) {
        this.rechargebtnMsg("确定充值"), e ? t.dialog.alertMsg(e) : t.dialog.alertMsg("生成充值订单失败");
    },
    onFormSubmit: function(r) {
        var i = r.detail.value.recharge;
        if ("确定充值" == this.data.rechargeText) if ("" != i) if (t.tools.validNumber(i) && 0 != Number.parseFloat(i)) {
            this.setData({
                recharge: i
            }), this.rechargebtnMsg("正在充值...."), t.dialog.showLoading({
                title: "正在调用支付..."
            });
            var s = this;

            u.rechargeOrder({
              money: t.tools.accMul(i, 100)
            },
            function(e) {

                console.log(e.code);

                if (0 == e.code) {
                    console.log('0 =========== e.code');
                    var t = e.result;
                    t.tradeNo ? (s.setData({
                        tradeNo: t.tradeNo
                    }), a.wxPay(t)) : s.resetInfo();
                } else s.resetInfo(e.message);
            }, function(e) {
                s.resetInfo(e);
            });
        } else t.dialog.alertMsg("充值金额不合法"); else t.dialog.alertMsg("请输入充值金额");
    },
    back: function() {
        wx.navigateBack({
            delta: 1
        });
    }
});