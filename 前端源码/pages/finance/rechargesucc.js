var t = require("../../utils/common-util.js"), 
e = require("../../utils/onfire.js"),
i = getApp(),
u = (i.require("utils/util.js"), i.require("utils/api.js"));

Page({
    data: {
        amount: "--",
        completeTimeStr: "--",
        tradeNo: "--"
    },
    onLoad: function(a) {
        var o = a.tradeno, r = a.amount, i = t.tools.formatDate(new Date(), "yyyy-MM-dd HH:mm:ss");
        this.setData({
            amount: r,
            completeTimeStr: i,
            tradeNo: o
        }), e.fire("rechargeEvent", {});
    },
    confirmSucc: function() {
        wx.navigateBack({
            delta: 2
        });
    }
});