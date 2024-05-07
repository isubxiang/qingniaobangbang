function getRandomColor() {
    for (var t = [], e = 0; e < 3; ++e) {
        var a = Math.floor(256 * Math.random()).toString(16);
        a = 1 == a.length ? "0" + a : a, t.push(a);
    }
    return "#" + t.join("");
}

var app = getApp(), util = require("../../utils/time.js");

Page({
    inputValue: "",
    data: {
        page: 1,
        refresh_top: !1,
        seller: [],
        typeid: "",
        infortype: [ {
            id: 0,
            type_name: "推荐"
        } ],
        activeIndex: 0,
        swiperCurrent: 0,
        indicatorDots: !1,
        autoplay: !0,
        interval: 5e3,
        duration: 1e3,
        slide: [ {
            img: "https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1513057315830&di=28c50097b1b069b2de68f70d625df8e2&imgtype=0&src=http%3A%2F%2Fimgsrc.baidu.com%2Fimgad%2Fpic%2Fitem%2Fa8014c086e061d95cb1b561170f40ad162d9cabe.jpg"
        }, {
            img: "https://ss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=570437944,358180613&fm=27&gp=0.jpg"
        } ]
    },
    jumps: function(t) {
        var e = this, a = (t.currentTarget.dataset.name, t.currentTarget.dataset.appid), n = t.currentTarget.dataset.src, r = t.currentTarget.dataset.id, o = t.currentTarget.dataset.sjtype;
        console.log(r);
        var i = t.currentTarget.dataset.type;
		
		
        if (1 == i) {
            if (console.log(n), "../distribution/jrhhr" == n) return !1;
            wx.navigateTo({
                url: n,
                success: function(t) {
                    e.setData({
                        averdr: !0
                    });
                },
                fail: function(t) {},
                complete: function(t) {}
            });
        } else 2 == i ? wx.navigateTo({
            url: "../car/car?vr=" + r + "&sjtype=" + o,
            success: function(t) {},
            fail: function(t) {},
            complete: function(t) {}
        }) : 3 == i && wx.navigateToMiniProgram({
            appId: a,
            path: "",
            extraData: {
                foo: "bar"
            },
            success: function(t) {
                e.setData({
                    averdr: !0
                });
            }
        });
    },
    onLoad: function(t) {
        console.log(t.name),  app.setNavigationBarColor(this), t.name ? wx.setNavigationBarTitle({
            title: t.name
        }) : wx.setNavigationBarTitle({
            title: "优惠券中心"
        }), this.setData({
            store_id: t.storeid,
            titlename: t.name,
            System: wx.getStorageSync("System")
        });
        var o = this;


        //获取学校
        var homeData = app.getStorage("homeData");
        var schoolId = homeData.schoolId;

        console.log("---schoolId---", schoolId);
 

        app.getUserInfo(function (t) {
          console.log('====app.getUserInfo===='),
            o.setData({
              userInfo: t,
              schoolId: schoolId,
              school_name: homeData.schoolName,
            });
        });
      
      
        var e = wx.getStorageSync("city");
        app.util.request({
            url: "app/Running/couponAd",
            cachetime: "0",
            data: {
                cityname: e
            },
            success: function(t) {
                console.log(t);
                var e = [];
                for (var a in t.data) 14 == t.data[a].type && e.push(t.data[a]);
                o.setData({
                    slide: e
                });
            }
        }), app.util.request({
            url: "app/Running/ZbCoupons",
            cachetime: "0",
            success: function(t) {
                console.log(t.data), o.setData({
                    ZbOrder: t.data
                });
            }
        }), app.util.request({
            url: "app/Running/CouponType",
            cachetime: "0",
            success: function(t) {
                var e = t.data;
                e.length <= 5 ? o.setData({
                    height: 165
                }) : 5 < e.length && o.setData({
                    height: 340
                });
                for (var a = [], n = 0, r = e.length; n < r; n += 10) a.push(e.slice(n, n + 10));
                console.log(a, e), o.setData({
                    nav: a,
                    navs: e
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadUrl",
            cachetime: "0",
            success: function(t) {
                o.setData({
                    url: t.data
                });
            }
        }), this.seller(this.data.typeid);
    },
    tabClick: function(t) {
        var e = this;
        if (console.log(t.currentTarget.id, t.currentTarget.dataset.index), 0 == t.currentTarget.dataset.index) var a = ""; else a = t.currentTarget.id;
        this.setData({
            page: 1,
            refresh_top: !1,
            seller: [],
            activeIndex: t.currentTarget.dataset.index,
            typeid: a
        }), setTimeout(function() {
            e.seller(a);
        }, 300);
    },
    selectednavbar: function(t) {
        console.log(t), wx.navigateTo({
            url: "type?id=" + t.currentTarget.dataset.id + "&name=" + t.currentTarget.dataset.name
        });
    },
    seller: function(t) {
        console.log("typeid为", t);
        var a = this, e = util.formatTime(new Date()), n = util.formatTime(new Date()).replace(/\//g, "-").toString();
        console.log(e, n);
        var r = wx.getStorageSync("city"), o = a.data.page, i = a.data.store_id || "", s = a.data.seller, c = null == this.data.store_id ? wx.getStorageSync("city") : "";
        console.log(r, o, i, c, s), app.util.request({
            url: "app/Running/CouponList2",
            cachetime: "0",
            data: {
                type_id: t,
                store_id: i,
                page: o,
                pagesize: 10,
                cityname: c,
                schoolId: a.data.schoolId
            },
            success: function(t) {
                console.log(t.data);
                for (var e = 0; e < t.data.length; e++) t.data[e].rate = parseInt(100 * (1 - Number(t.data[e].surplus) / Number(t.data[e].number)));
                t.data.length < 10 ? a.setData({
                    refresh_top: !0
                }) : a.setData({
                    refresh_top: !1,
                    page: o + 1
                }), s = s.concat(t.data), s = function(t) {
                    for (var e = [], a = 0; a < t.length; a++) -1 == e.indexOf(t[a]) && e.push(t[a]);
                    return e;
                }(s), console.log(s), a.setData({
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