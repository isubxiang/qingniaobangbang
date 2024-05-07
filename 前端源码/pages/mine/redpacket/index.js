var n = getApp(), o = (n.require("utils/util.js"), n.require("utils/api.js")), e = 0, t = -1, a = {
    Logs: []
};

Page({
    data: {
        lastPage: !1,
        model: a
    },
    onLoad: function(n) {
        this.loadData();
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        this.data.lastPage || this.loadData({
            pageIndex: ++e
        });
    },
    onShareAppMessage: function() {},


    loadData: function(i) {
        var s = this;
         o.userRedPacket(i, function(o) {
            s.setData({
                model: o
            });
        });
    },
    
});