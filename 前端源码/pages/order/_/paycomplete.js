var n = getApp().require("utils/onfire.js"), e = null;

Page({
    data: {
        second: 5
    },
    onLoad: function(o) {
        var t = this, r = 5;
        e = setInterval(function() {
            if (--r <= 0) return clearInterval(e), n.fire("refreshHomeOrders"), void wx.navigateTo({
                url: "info?createorder=true&id=" + o.id
            });
            t.setData({
                second: r
            });
        }, 1e3);
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});