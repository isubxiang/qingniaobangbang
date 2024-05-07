var t = require("../../../../utils/service/api-service.js"), 
e = require("../../../../utils/common-util.js"), 
a = require("../../../../utils/onfire.js"), 
n = require("../../../../utils/service/pay-service.js"), 
i = "0", 
c = 0, 
s = getApp(),
h = (s.require("utils/util.js"), s.require("utils/api.js"));

Page({
    data: {
        rankList: [],
        reqInit: !1,
        selectItem: "",
        payType: "wx",
        checkOutPayText: "确认购买",
        rechargeHide: !0,
        fund: 0,
        fundStr: "0.00",
        payAgent: ""
    },
    fundClick: function() {
        var t = e.tools.accMul(i, 100);
        this.data.fund >= t && this.setData({
            payType: "fund"
        });
    },
    wxPayClick: function() {
        this.setData({
            payType: "wx"
        });
    },
    rechargeClick: function() {
        wx.navigateTo({
            url: "../../../finance/recharge"
        });
    },
    chooseItem: function(t) {
        var e = t.currentTarget.dataset.id;
        i = t.currentTarget.dataset.fee, c = t.currentTarget.dataset.exam, this.setData({
            selectItem: e
        });
    },
    onShow: function() {
        n.openPayResult();
    },
    onLoad: function(t) {
        i = "0", c = 0;
        var e = {
            id: "",
            type: ""
        }, o = t.id, u = t.type;
        void 0 !== o && void 0 !== u && (e.id = o, e.type = u), this.setData({
            payAgent: s.payAgent()
        }), this.loadList(e);
        var r = this;
        n.payFn = "getExamPayResult", n.onBillSucc = r.onBillSucc, n.onBillFail = r.onBillFail, 
        a.on("rechargeEvent", function(t) {
            r.loadUserInfo();
        }), this.loadUserInfo();
    },
    onUnload: function() {
        a.un("rechargeEvent");
    },
    loadUserInfo: function() {
        var a = this;
        t.loadUserInfo(function(t) {
            var n = t.result, c = e.tools.accMul(i, 100), s = n.fund >= c;
            a.setData({
                fund: n.fund,
                fundStr: n.fundStr,
                rechargeHide: s,
                payType: s ? "fund" : "wx"
            });
        }, function(t) {
            e.dialog.alert({
                title: "",
                content: t.message,
                success: function(t) {
                    a.loadUserInfo();
                }
            });
        });
    },
    loadList: function(a) {
        var n = this;
        t.examRankList(a, function(t) {
            var a = t.result, s = "";
            if (a.length >= 1) {
                s = a[0].id, i = a[0].totalFeeStr, c = a[0].exam;
                var o = e.tools.accMul(a[0].totalFeeStr, 100), u = n.data.fund >= o;
                n.setData({
                    rechargeHide: u,
                    payType: u ? "fund" : "wx"
                });
            }
            n.setData({
                rankList: a,
                reqInit: !0,
                selectItem: s
            });
        }, function(t) {
            e.dialog.alert({
                title: "",
                content: t.message,
                success: function(t) {
                    n.loadList();
                }
            });
        });
    },
    checkbtnMsg: function(t) {
        this.setData({
            checkOutPayText: t
        });
    },
    onFormSubmit: function() {
        s.checkMultiClick(this, "");
    },
    onSubmit: function(t) {
        var a = this;
        "确认购买" == a.data.checkOutPayText && e.dialog.confirm({
            title: "",
            content: "确认购买？",
            success: function(t) {
                t.confirm && (a.checkbtnMsg("正在提交...."), a.submitMem());
            }
        });
    },
    onBillFail: function() {},
    onBillSucc: function() {
        a.fire("exammemup", {}), e.dialog.alert({
            title: "",
            content: "购买成功",
            success: function(t) {
                wx.navigateBack({
                    delta: 1
                });
            }
        });
    },
    submitMem: function() {
        var a = this, i = {
            goodsId: this.data.selectItem,
            examType: c,
            payType: this.data.payType
        };
        t.submitExamMem(i, function(t) {
            if (a.checkbtnMsg("确认购买"), 0 == t.code) a.onBillSucc(); else if (2 == t.code) {
                var i = t.result;
                n.wxPay(i);
            } else e.dialog.alertMsg(t.message);
        }, function(t) {
            a.checkbtnMsg("确认购买"), e.dialog.alertMsg("提交发生异常，请重试");
        });
    }
});