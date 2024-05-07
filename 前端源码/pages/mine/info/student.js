var n = getApp(), o = (n.require("utils/util.js"), n.require("utils/api.js"));

Page({
    data: {
        model: {}
    },
    onLoad: function(n) {
        var t = this;
        o.studentInfo(function(n) {
            t.setData({
                model: n
            });
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});