var app = getApp();

Page({
    data: {
        star2: [ {
            img: "../image/star_none.png"
        }, {
            img: "../image/star_none.png"
        }, {
            img: "../image/star_none.png"
        }, {
            img: "../image/star_none.png"
        }, {
            img: "../image/star_none.png"
        } ],
        activeIndex: 0,
        sliderOffset: 0,
        sliderLeft: 35,
        tabs: [ "收藏的信息"],
        activeIndexe: 0,
        sliderOffsete: 0,
        sliderLefte: 0
    },
    navClick: function(a) {
        this.setData({
            sliderOffsete: a.currentTarget.offsetLeft,
            activeIndexe: a.currentTarget.id
        });
    },
    tabClick: function(a) {
        var o = this;
        console.log(a);
        var t = o.data.classification, e = a.currentTarget.id, n = t[e].id, i = t[e].name;
        console.log(t[e]), this.setData({
            activeIndex: e
        }), app.util.request({
            url: "app/Running/ThreadPostList",
            cachetime: "0",
            data: {
                type2_id: n
            },
            success: function(a) {
                console.log(a);
                var t = [];
                for (var e in a.data) a.data[e].type2_name = i, a.data[e].img = a.data[e].img.split(","), 
                null != a.data[e].store_name && t.concat(a.data[e]);
                console.log(t), o.setData({
                    classification_info: t
                });
            }
        });
    },


    onLoad: function(a) {
        var t = this;
        app.util.request({
            url: "app/Running/ThreadSystem",
            cachetime: "0",
            success: function(a) {
                console.log(a), 
                t.setData({
                    System: a.data
                });
            }
        }), 

        wx.setNavigationBarTitle({
            title: a.name
        });

        var e = wx.getStorageSync("url");
        t.setData({
            url: e
        }), t.reload();
    },

    reload: function(a) {
        var p = this, t = wx.getStorageSync("users").id;
        console.log(t), app.util.request({
            url: "app/Running/ThreadMyCollection",
            cachetime: "0",
            data: {
                user_id: t
            },
            success: function(a) {
                console.log(a);
                var t = [];
                for (var e in a.data) if (null != a.data[e].details) {
                    var o = p.ormatDate(a.data[e].time);
                    a.data[e].img = a.data[e].img.split(","), 4 <= a.data[e].img.length ? a.data[e].img1 = a.data[e].img.slice(0, 4) : a.data[e].img1 = a.data[e].img, 
                    a.data[e].time = o.slice(0, 16), t.push(a.data[e]);
                }
                console.log(t), p.setData({
                    classification_info: t
                });
            }
        })
    },


    ormatDate: function(a) {
        var t = new Date(1e3 * a);
        return t.getFullYear() + "-" + e(t.getMonth() + 1, 2) + "-" + e(t.getDate(), 2) + " " + e(t.getHours(), 2) + ":" + e(t.getMinutes(), 2) + ":" + e(t.getSeconds(), 2);
        function e(a, t) {
            for (var e = "" + a, o = e.length, n = "", i = t; i-- > o; ) n += "0";
            return n + e;
        }
    },
 
 
    see: function(a) {  
        var id = a.currentTarget.dataset.id;
        wx.navigateTo({
            url: "../detail?id=" + id,
        });
    },


    phone: function(a) {
        var t = a.currentTarget.dataset.id;
        wx.makePhoneCall({
            phoneNumber: t
        });
    },
    phone1: function(a) {
        console.log(a);
        var t = a.currentTarget.dataset.tel;
        wx.makePhoneCall({
            phoneNumber: t
        });
    },
    onReady: function() {},

    onShow: function() {
        wx.setNavigationBarColor({
            frontColor: "#ffffff",
           backgroundColor:"#06c1ae",
            animation: {
                duration: 0,
                timingFunc: "easeIn"
            }
        });
    },
    
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {
        this.reload(), wx.stopPullDownRefresh();
    },
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});