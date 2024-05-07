var app = getApp();

Page({
    data: {},
    onLoad: function(o) {
        app.setNavigationBarColor(this), this.refresh(o.goods_id);
    },
    refresh: function(o) {
        var n = this;
        n.data;
        app.util.request({
           url: "app/Running/GoodsInfo",
            cachetime: "0",
            data: {
                goods_id: o
            },
            success: function(o) {
                for (var a in console.log("商品详情", o), o.data.group) o.data.group[a].num = Number(o.data.group[a].kt_num) - Number(o.data.group[a].yg_num), 
                "" != o.data.group[a].name && null != o.data.group[a].name && 6 <= o.data.group[a].name.length && (o.data.group[a].name = o.data.group[a].name.slice(0, 6) + "...");
                console.log(o.data.group);
                var t = o.data.goods;
                n.countdown(t.end_time), n.setData({
                    group: o.data.group,
                    goods_info: t
                });
            }
        });
    },
    collageInfo: function(o) {
        var a = this.data;
        wx.navigateTo({
            url: "group?id=" + o.currentTarget.dataset.id + "&user_id=" + o.currentTarget.dataset.userid + "&goods_id=" + a.goods_info.id
        });
    },
    countdown: function(o) {
        var a = this, t = (o || []) - Math.round(new Date().getTime() / 1e3) || [];
        t <= 0 ? (app.util.request({
            url: "app/Running/UpdateGroup",
            data: {
                store_id: a.data.id
            },
            success: function(o) {
                console.log(o);
            }
        }), a.setData({
            clock: !1
        })) : 0 < t && 0 != a.data.clock && (a.dateformat(t), setTimeout(function() {
            t -= 1e3, a.countdown(o);
        }, 1e3));
    },
    dateformat: function(o) {
        var a = Math.floor(o), t = Math.floor(a / 3600 / 24), n = Math.floor(a / 3600 % 24), e = Math.floor(a / 60 % 60), r = Math.floor(a % 60);
        t < 10 && (t = "0" + t), n < 10 && (n = "0" + n), r < 10 && (r = "0" + r), e < 10 && (e = "0" + e), 
        this.setData({
            day: t,
            hour: n,
            min: e,
            sec: r
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