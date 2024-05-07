var n = getApp(), o = (n.require("utils/util.js"), n.require("utils/api.js")), t = {};

Page({
    data: {
        model: t
    },
    onLoad: function(n) {
        this.loadData();
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    loadData: function() {
        var n = this;
        o.getAssginSetting(function(o) {
            t = o, n.setData({
                model: t
            });
        });
    },
    onAutoAssignChange: function(n) {
        t.AutoAssign = n.detail.value, this.setData({
            model: t
        });
    },
    onRemoveTap: function(n) {}
});