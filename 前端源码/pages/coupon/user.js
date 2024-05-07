var app = getApp(), util = require("../../utils/time.js");

Page({
    data: {
        tabs: [ "可用的", "失效的" ],
        activeIndex: 0,
        items1: [],
        items: [],
        startX: 0,
        startY: 0,
        showModal: !1
    },
    tabClick: function(t) {
        this.setData({
            activeIndex: t.currentTarget.id
        });
    },
    yczz: function() {
        this.setData({
            showModal: !1
        });
    },


    getPhoneNumber: function(t) {
        var e = this, a = wx.getStorageSync("users").id;
        console.log(t), console.log(t.detail.iv), console.log(t.detail.encryptedData), "getPhoneNumber:fail user deny" == t.detail.errMsg ? wx.showModal({
            title: "提示",
            showCancel: !1,
            content: "您未授权获取您的手机号",
            success: function(t) {}
        }) : app.util.request({
            url: "app/Running/Jiemi",
            cachetime: "0",
            data: {
                sessionKey: getApp().getSK,
                data: t.detail.encryptedData,
                iv: t.detail.iv
            },
            success: function(t) {
                console.log("解密后的数据", t), null != t.data.phoneNumber && (e.setData({
                    sjh: t.data.phoneNumber,
                    showModal: !1
                }), app.util.request({
                    url: "app/Running/SaveLqTel",
                    cachetime: "0",
                    data: {
                        user_id: a,
                        lq_tel: t.data.phoneNumber
                    },
                    success: function(t) {
                        console.log(t.data), 1 == t.data && wx.showToast({
                            title: "验证成功",
                            duration: 1e3
                        });
                    }
                }));
            }
        });
    },


    ljsy: function(t) {
        var e = t.currentTarget.dataset.yhqid, a = t.currentTarget.dataset.sjid, o = t.currentTarget.dataset.qid;
        console.log(t, e, a, o), wx.navigateTo({
            url: "../coupon/info?yhqid=" + e + "&sjid=" + a + "&qid=" + o
        });
    },


    reLoad: function() {
        var n = this, t = wx.getStorageSync("users").id, i = util.formatTime(new Date()).substring(0, 10).replace(/\//g, "-");
        console.log(t, i), app.util.request({
            url: "app/Running/MyCoupons",
            cachetime: "0",
            data: {
                user_id: t
            },
            success: function(t) {
                console.log(t.data);
                for (var e = t.data, a = 0; a < e.length; a++) "通用券" == e[a].name && (e[a].cost = parseInt(e[a].cost));
                var o = [], s = [];
                for (a = 0; a < e.length; a++) util.validTime1(i, e[a].end_time) && "1" != e[a].state && "3" != e[a].state ? "2" == e[a].state && o.push(e[a]) : (e[a].isTouchMove = !1, 
                s.push(e[a]));
                console.log(o, s), n.setData({
                    items1: o,
                    items: s
                });
            }
        });
    },


    onLoad: function(t) {

        var e = this, a = wx.getStorageSync("users").id;
        console.log(a);
        if (a == '' || a == undefined) {
          app.getUserInfo(function (t) {
            console.log('登录领取优惠券会员中心==app.getUserInfo==');
            console.log(t),
              wx.setStorageSync("users", t),
              e.setData({
                userInfo: t
              });
          });
        }

        var e = wx.getStorageSync("url");
        console.log(e), wx.setNavigationBarColor({
            frontColor: "#ffffff",
            backgroundColor: wx.getStorageSync("System").color
        }), this.setData({
            url: e,
            System: wx.getStorageSync("System")
        });
    },


    onReady: function() {},
    onShow: function() {
        this.reLoad();
        var t = wx.getStorageSync("users").id;
        console.log(t);
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {
        this.reLoad(), wx.stopPullDownRefresh();
    },
    onReachBottom: function() {},
    touchstart1: function(t) {
        this.data.items1.forEach(function(t, e) {
            t.isTouchMove && (t.isTouchMove = !1);
        }), this.setData({
            startX: t.changedTouches[0].clientX,
            startY: t.changedTouches[0].clientY,
            items1: this.data.items1
        });
    },
    touchmove1: function(t) {
        var e = this, a = t.currentTarget.dataset.index, o = e.data.startX, s = e.data.startY, n = t.changedTouches[0].clientX, i = t.changedTouches[0].clientY, c = e.angle({
            X: o,
            Y: s
        }, {
            X: n,
            Y: i
        });
        e.data.items1.forEach(function(t, e) {
            t.isTouchMove = !1, 30 < Math.abs(c) || e == a && (t.isTouchMove = !(o < n));
        }), e.setData({
            items1: e.data.items1
        });
    },
    touchstart: function(t) {
        this.data.items.forEach(function(t, e) {
            t.isTouchMove && (t.isTouchMove = !1);
        }), this.setData({
            startX: t.changedTouches[0].clientX,
            startY: t.changedTouches[0].clientY,
            items: this.data.items
        });
    },
    touchmove: function(t) {
        var e = this, a = t.currentTarget.dataset.index, o = e.data.startX, s = e.data.startY, n = t.changedTouches[0].clientX, i = t.changedTouches[0].clientY, c = e.angle({
            X: o,
            Y: s
        }, {
            X: n,
            Y: i
        });
        e.data.items.forEach(function(t, e) {
            t.isTouchMove = !1, 30 < Math.abs(c) || e == a && (t.isTouchMove = !(o < n));
        }), e.setData({
            items: e.data.items
        });
    },
    angle: function(t, e) {
        var a = e.X - t.X, o = e.Y - t.Y;
        return 360 * Math.atan(o / a) / (2 * Math.PI);
    },
    del: function(t) {
        var e = this, a = t.currentTarget.dataset.yhqid;
        console.log(t, a), wx.showModal({
            title: "提示",
            content: "删除后，此券将成为失效券",
            success: function(t) {
                t.confirm ? (console.log("用户点击确定"), app.util.request({
                    url: "app/Running/DelMyCoupons",
                    cachetime: "0",
                    data: {
                        id: a
                    },
                    success: function(t) {
                        console.log(t.data), 1 == t.data ? (wx.showToast({
                            title: "删除成功",
                            icon: "success",
                            duration: 1e3
                        }), setTimeout(function() {
                            e.reLoad();
                        }, 1e3)) : wx.showToast({
                            title: "请重试",
                            icon: "loading",
                            duration: 1e3
                        });
                    }
                })) : t.cancel && console.log("用户点击取消");
            }
        });
    },
    del2: function(t) {
        var e = this, a = t.currentTarget.dataset.yhqid;
        console.log(t, a), wx.showModal({
            title: "提示",
            content: "删除后，此券将从您的记录中删除",
            success: function(t) {
                t.confirm ? (console.log("用户点击确定"), app.util.request({
                    url: "app/Running/DelCoupon",
                    cachetime: "0",
                    data: {
                        coupon_id: a
                    },
                    success: function(t) {
                        console.log(t.data), 1 == t.data ? (wx.showToast({
                            title: "删除成功",
                            icon: "success",
                            duration: 1e3
                        }), setTimeout(function() {
                            e.reLoad();
                        }, 1e3)) : wx.showToast({
                            title: "请重试",
                            icon: "loading",
                            duration: 1e3
                        });
                    }
                })) : t.cancel && console.log("用户点击取消");
            }
        });
    }
});