var a = getApp(), o = (a.require("utils/util.js"), a.require("utils/api.js")), n = 0, t = {
    Logs: []
};

Page({
    data: {
        lastPage: !1,
        model: {}
    },
    onLoad: function(a) {
        n = 0, this.loadData();
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        this.data.lastPage || this.loadData({
            pageIndex: ++n
        });
    },
    onShareAppMessage: function() {},
    loadData: function(e) {
        var i = this;
        o.userAccount(e, function(o) {
            if (e && e.pageIndex) for (var s in o.Logs) t.Logs.push(o.Logs[s]); else t = o;
            var u = -1;
            o.Logs.length < a.pageSize && (u = t.Logs.length), i.setData({
                lastPage: u >= 0 && u <= (n + 1) * a.pageSize,
                model: t
            });
        });
    }
});