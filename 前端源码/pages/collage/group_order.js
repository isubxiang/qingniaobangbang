var app = getApp(), util = require("../../we7/resource/js/util.js");

Page({
    data: {
        color: "#34aaff",
        state: 1,
        order_list: [],
        show_no_data_tip: !1,
        hide: 1,
        qrcode: "",
        pagenum: 1,
        storelist: [],
        mygd: !1,
        jzgd: !0
    },
    onLoad: function(t) {
        wx.setNavigationBarTitle({
            title: "我的拼团"
        }), app.setNavigationBarColor(this);
        this.setData({
            url: wx.getStorageSync("url")
        });
        console.log(t), this.setData({
            state: t.state
        }), 4 != t.state ? this.reLoad() : this.order();
    },
    reLoad: function() {
        var r = this, o = this.data.state || 1, t = wx.getStorageSync("users").id, s = this.data.pagenum;

        //获取学校
        var homeData = wx.getStorageSync("homeData");
        var schoolId = homeData.schoolId;

        var settings = wx.getStorageSync("settings");
        var SessionId = settings.SessionId;

        console.log('学校ID=' + schoolId);
        console.log('SessionId=' + SessionId);

        console.log(s), app.util.request({
            url: "app/Running/MyGroupOrder",
            cachetime: "0",
            data: {
                state: o,
                user_id: t ? t : SessionId,
                page: s
            },
            success: function(t) {
                console.log("分页返回的列表数据", t);
                for (var a = 0; a < t.data.length; a++) t.data[a].status = o, t.data[a].xf_time = app.ormatDate(t.data[a].xf_time), 
                t.data[a].pay_time = app.ormatDate(t.data[a].pay_time);
                var e = r.data.storelist;
                e = function(t) {
                    for (var a = [], e = 0; e < t.length; e++) -1 == a.indexOf(t[e]) && a.push(t[e]);
                    return a;
                }(e = e.concat(t.data)), r.setData({
                    order_list: e,
                    storelist: e,
                    pagenum: s + 1
                }), console.log(e);
            }
        });
    },
    order: function() {
        var r = this, o = this.data.state || 1, t = wx.getStorageSync("users").id, s = this.data.pagenum;
        app.util.request({
            url: "app/Running/MyGroupOrder",
            cachetime: "0",
            data: {
                type: 1,
                user_id: t,
                page: s
            },
            success: function(t) {
                console.log("分页返回的列表数据", t);
                for (var a = 0; a < t.data.length; a++) t.data[a].status = o, 
                t.data[a].xf_time = app.ormatDate(t.data[a].xf_time), 
                t.data[a].pay_time = app.ormatDate(t.data[a].pay_time), 
                15 <= t.data[a].receive_address.length && "" != t.data[a].receive_address && (t.data[a].receive_address = t.data[a].receive_address.slice(0, 15) + "...");
                var e = r.data.storelist;
                e = function(t) {
                    for (var a = [], e = 0; e < t.length; e++) -1 == a.indexOf(t[e]) && a.push(t[e]);
                    return a;
                }(e = e.concat(t.data)), r.setData({
                    order_list: e,
                    storelist: e,
                    pagenum: s + 1
                }), console.log(e);
            }
        });
    },
    onReachBottom: function() {
        console.log("上拉加载", this.data.pagenum);
        var t = this;
        4 != t.data.state ? t.reLoad() : t.order();
    },
    order_info: function(t) {
        var a = t.currentTarget.dataset;
        console.log(a.info), wx.setStorageSync("order_info", a.info), wx.navigateTo({
            url: "order_info"
        });
    },
    orderQrcode: function(a) {
        var r = this, o = r.data.order_list, s = a.target.dataset.index;
        wx.showLoading({
            title: "正在加载",
            mask: !0
        }), r.data.order_list[s].offline_qrcode ? (r.setData({
            hide: 0,
            qrcode: r.data.order_list[s].offline_qrcode
        }), wx.hideLoading()) : e.request({
            url: t.order.get_qrcode,
            data: {
                order_no: o[s].order_no
            },
            success: function(t) {
                0 == t.code ? r.setData({
                    hide: 0,
                    qrcode: t.data.url
                }) : wx.showModal({
                    title: "提示",
                    content: t.msg
                });
            },
            complete: function() {
                wx.hideLoading();
            }
        });
    },
    hide: function(t) {
        this.setData({
            hide: 1
        });
    },
    hxqh: function(t) {
        var a = this, e = t.currentTarget.dataset.id, r = t.currentTarget.dataset.sjid;
        console.log(e, r), wx.showLoading({
            title: "加载中",
            mask: !0
        }), app.util.request({
            url: "app/Running/OrderCode",
            cachetime: "0",
            data: {
                order_id: e
            },
            success: function(t) {
                console.log(t.data), a.setData({
                    hx_code: t.data,
                    hide: 2
                });
            }
        });
    }
});