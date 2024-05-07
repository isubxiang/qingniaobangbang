var app = getApp();

Page({
    inputValue: "",
    data: {
        page: 1,
        refresh_top: !1,
        seller: [],
        typeid: ""
    },
    onLoad: function(t) {
        console.log(t.name), app.setNavigationBarColor(this), t.name && wx.setNavigationBarTitle({
            title: t.name
        }), this.setData({
            titlename: t.name,
            typeid: t.id,
            System: wx.getStorageSync("System")
        });
        var e = this;
        wx.getStorageSync("city");
        app.util.request({
            url: "app/Running/Url",
            cachetime: "0",
            success: function(t) {
                e.setData({
                    url: t.data
                });
            }
        }), this.seller(this.data.typeid);
    },
    seller: function(t) {
        console.log("typeid为", t);
        var a = this, e = util.formatTime(new Date()), o = util.formatTime(new Date()).replace(/\//g, "-").toString();
        console.log(e, o);
        wx.getStorageSync("city");
        var i = a.data.page, n = a.data.store_id || "", s = a.data.seller, l = null == this.data.store_id ? wx.getStorageSync("city") : "";
        console.log(n, l), app.util.request({
            url: "app/Running/CouponList",
            cachetime: "0",
            data: {
                type_id: t,
                store_id: n,
                page: i,
                pagesize: 10,
                cityname: l
            },
            success: function(t) {
                console.log(t.data), t.data.length < 10 ? a.setData({
                    refresh_top: !0
                }) : a.setData({
                    refresh_top: !1,
                    page: i + 1
                }), s = s.concat(t.data), s = function(t) {
                    for (var e = [], a = 0; a < t.length; a++) -1 == e.indexOf(t[a]) && e.push(t[a]);
                    return e;
                }(s), console.log(s);
                for (var e = 0; e < s.length; e++) s[e].end_time > o ? s[e].isgq = 2 : s[e].isgq = 1;
                a.setData({
                    seller: s
                });
            }
        });
    },
    onReady: function(t) {
        this.videoContext = wx.createVideoContext("myVideo");
    },
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        console.log("上拉触底"), 0 == this.data.refresh_top ? this.seller(this.data.typeid) : console.log("没有更多了");
    }
});