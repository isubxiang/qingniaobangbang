var app = getApp();

Page({
    data: {
        release: [ {
            name: "人找车",
            id: 0
        }, {
            name: "车找人",
            id: 1
        }, {
            name: "车找货",
            id: 2
        }, {
            name: "货找车",
            id: 3
        } ],
        foot: !1
    },
    cancel: function(n) {
        var e = this;
        wx.showModal({
            title: "提示",
            content: "是否删除此条内容",
            showCancel: !0,
            cancelText: "取消",
            confirmText: "确定",
            success: function(a) {
                if (a.confirm) {
                    console.log("用户点击确定");
                    var t = n.currentTarget.dataset.id;
                    app.util.request({
                        url: "app/Running/DelCar",
                        cachetime: "0",
                        data: {
                            car_id: t
                        },
                        success: function(a) {
                            console.log(a), 1 == a.data && (e.setData({
                                yellow_list: []
                            }), e.refresh());
                        }
                    });
                } else a.cancel && console.log("用户点击取消");
            },
            fail: function(a) {},
            complete: function(a) {}
        });
    },

    //加载页面
    onLoad: function(a) {

      app.setNavigationBarColor(this);

    },



    refresh: function(a) {
        var n = this;
        t = new Date(), e = t.getMonth() + 1, o = t.getDate(), 1 <= e && e <= 9 && (e = "0" + e), 
        0 <= o && o <= 9 && (o = "0" + o), t.getFullYear(), t.getHours(), t.getMinutes(), 
        t.getSeconds();
        var t, e, o, c = wx.getStorageSync("users").id, s = wx.getStorageSync("settings");


        app.util.request({
            url: "app/Running/MyCar",
            cachetime: "0",
            data: {
                user_id: c ? c : s.SessionId,
                page: 1
            },
            success: function(a) {
                for (var t in console.log(a), a.data) a.data[t].time = app.ormatDate(a.data[t].time).slice(5, 16), 
                a.data[t].start_time1 = a.data[t].start_time.slice(5, 10), 
                a.data[t].start_time2 = a.data[t].start_time.slice(10, 17), 
                2 == a.data[t].is_open ? (a.data[t].class2 = "car3", a.data[t].class3 = "car4", 
                a.data[t].class4 = "car5") : (a.data[t].class2 = "", a.data[t].class3 = "", a.data[t].class4 = ""), 
                "人找车" == a.data[t].typename ? (a.data[t].class = "color1", a.data[t].class1 = "car1") : "车找人" == a.data[t].typename ? (a.data[t].class = "color2", 
                a.data[t].class1 = "car2") : "货找车" == a.data[t].typename ? (a.data[t].class = "color1", 
                a.data[t].class1 = "car1") : "车找货" == a.data[t].typename && (a.data[t].class = "color2", 
                a.data[t].class1 = "car2");
                n.setData({
                    pc: a.data
                });
            }
        });
    },
    shunfabu: function(a) {
        console.log(a);
        var t = a.currentTarget.id;
        this.setData({
            foot: !1
        }), wx.navigateTo({
            url: "../shun/shunfabu/shunfabu?id=" + t
        });
    },
    footbtn: function(a) {
        var t = this;
        0 == t.data.foot ? t.setData({
            foot: !0
        }) : t.setData({
            foot: !1
        });
    },
    carinfo: function(a) {
        console.log(a);
        var t = a.currentTarget.dataset.id;
        wx.navigateTo({
            url: "../shun/shuninfo2/shuninfo2?id=" + t,
            success: function(a) {},
            fail: function(a) {},
            complete: function(a) {}
        });
    },
    call_phone: function(a) {
        console.log(a), wx.makePhoneCall({
            phoneNumber: a.currentTarget.dataset.tel
        });
    },


    shouye: function(a) {
        wx.reLaunch({
           url: "../../errand/_/index",
            success: function(a) {},
            fail: function(a) {},
            complete: function(a) {}
        });
    },


    yellow: function(a) {
        wx.redirectTo({
            url: "../../shun/shun",
            success: function(a) {},
            fail: function(a) {},
            complete: function(a) {}
        });
    },
    settled: function(a) {
        wx.navigateTo({
            url: "../shun/shunfabu/shunfabu",
            success: function(a) {},
            fail: function(a) {},
            complete: function(a) {}
        });
    },
    onReady: function() {},
    onShow: function() {
        this.refresh();
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {
        this.refresh(), wx.stopPullDownRefresh();
    },
    onReachBottom: function() {}
});