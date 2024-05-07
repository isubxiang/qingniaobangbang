var t = getApp();

t.require("utils/util.js"), t.require("utils/api.js");

Page({
    data: {
        button: null,
        title: null,
        remark: null
    },
    onLoad: function(t) {
        var n = this, e = t.title ? decodeURIComponent(t.title) : n.data.title;
        this.setData({
            button: t.button && t.button ? decodeURIComponent(t.button) : n.data.button,
            title: e,
            remark: t.remark ? decodeURIComponent(t.remark) : n.data.remark
        }), wx.setNavigationBarTitle({
            title: e
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