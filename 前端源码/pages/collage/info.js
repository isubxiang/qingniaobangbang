var app = getApp(), siteinfo = require("../../siteinfo.js");

Page({
    data: {
        share: !0,
        friendsImage: !0,
        clock: !0
    },
    location: function() {
        var t = this.data.StoreInfo.coordinates.split(","), e = this.data.StoreInfo;
        console.log(t), wx.openLocation({
            latitude: parseFloat(t[0]),
            longitude: parseFloat(t[1]),
            address: e.address,
            name: e.store_name
        });
    },

    back: function(t) {
        wx.reLaunch({
            url: "../errand/_/index",
        });
    },

    maketel: function(t) {
        var e = this.data.StoreInfo.tel;
        wx.makePhoneCall({
            phoneNumber: e
        });
    },
    onLoad: function(t) {
        var e = this;
        console.log(t), app.setNavigationBarColor(this), wx.setNavigationBarTitle({
            title: t.title
        }), app.util.request({
           url: "app/Running/ThreadUrl",
            cachetime: "0",
            success: function(t) {
                console.log("这是网址"), console.log(t), wx.setStorageSync("url", t.data), e.setData({
                    url: t.data
                });
            }
        });
        var o = decodeURIComponent(t.scene);
        null == t.scene ? e.setData({
            id: t.id
        }) : e.setData({
            id: o
        }), e.reload(), wx.getSystemInfo({
            success: function(t) {
                e.setData({
                    width: t.windowWidth,
                    height: 570,
                    v_wid: t.windowWidth - 40
                });
            }
        });
    },
    refresh: function(t) {
        var n = this;
        n.data;
        app.util.request({
            url: "app/Running/GoodsInfo",
            cachetime: "0",
            data: {
                goods_id: n.data.id
            },
            success: function(t) {
                for (var e in console.log("商品详情", t)) t.data.group[e].num = Number(t.data.group[e].kt_num) - Number(t.data.group[e].yg_num);


                console.log(t.data.group);
                var o = t.data.goods;
                for (var a in n.countdown(o.end_time), o.img = o.img.split(","), o.details_img = o.details_img.split(","), 
                o.end_times = o.end_time, o.xf_times = o.xf_time, o.start_time = n.ormatDate(o.start_time), 
                o.end_time = n.ormatDate(o.end_time), o.xf_time = n.ormatDate(o.xf_time), app.util.request({
                    url: "app/Running/StoreInfo",
                    cachetime: "0",
                    data: {
                        id: o.store_id
                    },
                    success: function(t) {
                        console.log("商家详情", t), n.setData({
                            StoreInfo: t.data.store[0]
                        });
                    }
                }), 
                
                wx.downloadFile({
                    url: o.logo,
                    success: function(t) {
                        console.log(o.logo), 
                        console.log(t.tempFilePath),
                         n.setData({
                            logo: t.tempFilePath
                        }), n.img0();
                    }
                }), 
                
                wx.downloadFile({
                    url: o.img[0],
                    success: function(t) {
                        console.log(o.img[0]), 
                        console.log(t.tempFilePath), 
                        n.setData({
                            logo1: t.tempFilePath
                        });
                    }
                }),
                
                 t.data.group) "" != t.data.group[a].name && null != t.data.group[a].name && 6 <= t.data.group[a].name.length && (t.data.group[a].name = t.data.group[a].name.slice(0, 6) + "..."), 
                t.data.group[a].user_id == wx.getStorageSync("users").id && (console.log("已经有开的团了"), 
                n.setData({
                    already_group: !0,
                    already: t.data.group[a]
                }));
                n.setData({
                    goods_info: o,
                    group: t.data.group
                });
            }
        });
    },
    img0: function(t) {
        var o = this, a = siteinfo.siteroot.slice(0, siteinfo.siteroot.length - 14);
        console.log(a), app.util.request({
            url: "app/Running/GoodsCode",
            cachetime: "0",
            data: {
                goods_id: o.data.id
            },
            success: function(t) {
                var e = t.data;
                o.setData({
                    goods_code: e
                }), wx.downloadFile({
                    url: e + "",
                    success: function(t) {
                        console.log(t.tempFilePath), o.setData({
                            shop_logo: t.tempFilePath
                        }), o.ctx();
                    }
                });
            }
        });
    },
    reload: function(t) {
        var e = this;
        wx.showLoading({
            title: "加载中",
            mask: !0
        });
        e.data.id;
        app.util.request({
            url: "app/Running/GroupType",
            cachetime: "0",
            success: function(t) {
                console.log("分类列表", t), e.setData({
                    nav_array: t.data
                });
            }
        });
    },
    ctx: function(t) {
        var e = this, o = e.data, a = (o.width, o.height, wx.createCanvasContext("ctx"));
        a.drawImage(o.shop_logo, 0, 0, 150, 150), a.save(), a.beginPath(), a.arc(75, 75, 35, 0, 2 * Math.PI), 
        a.clip(), a.drawImage(o.logo, 35, 35, 75, 75), a.restore(), a.draw(), setTimeout(function(t) {
            wx.canvasToTempFilePath({
                x: 0,
                y: 0,
                width: 150,
                height: 150,
                canvasId: "ctx",
                success: function(t) {
                    console.log(t.tempFilePath), wx.hideLoading(), e.setData({
                        logos: t.tempFilePath
                    });
                }
            });
        }, 500);
    },
    canvas: function(t) {
        var e = this;
        wx.navigateTo({
            url: "canvas?id=" + e.data.goods_info.id + "&url=" + e.data.url
        });
    },
    genImage: function(t) {
        var e = this, o = this.data.width, a = this.data.height;
        wx.canvasToTempFilePath({
            x: 0,
            y: 0,
            width: o,
            height: a,
            canvasId: "firstCanvas",
            success: function(t) {
                console.log(t.tempFilePath), wx.hideLoading(), e.setData({
                    genImage: t.tempFilePath,
                    friendsImage: !1
                });
            }
        });
    },
    close: function(t) {
        this.setData({
            friendsImage: !0,
            share: !0
        });
    },
    toTemp: function(t) {
        var e = this;
        wx.saveImageToPhotosAlbum({
            filePath: e.data.genImage,
            success: function(t) {
                console.log(t), wx.showToast({
                    title: "保存成功"
                }), e.setData({
                    friendsImage: !0,
                    share: !0
                });
            },
            fail: function(t) {},
            complete: function(t) {}
        });
    },
    share: function(t) {
        var e = this.data.share;
        e = 1 != e, this.setData({
            share: e
        });
    },
    more_group: function(t) {
        wx.navigateTo({
            url: "group_num?goods_id=" + this.data.id
        });
    },
    collageInfo: function(t) {
        var e = this.data;
        wx.navigateTo({
            url: "group?id=" + t.currentTarget.dataset.id + "&user_id=" + t.currentTarget.dataset.userid + "&goods_id=" + e.goods_info.id
        });
    },
    alone_pay: function(t) {
        var e = this.data;

        console.log("place_order?id=" + e.goods_info.id + "&type=1&kt_num=0&group_id=&end_time=" + e.goods_info.end_times + "&xf_time=" + e.goods_info.xf_times + "&name=" + e.goods_info.name + "&price=" + e.goods_info.dd_price,);

      wx.navigateTo({
            url: "place_order?id=" + e.goods_info.id + "&type=1&kt_num=0&group_id=&end_time=" + e.goods_info.end_times + "&xf_time=" + e.goods_info.xf_times + "&name=" + e.goods_info.name + "&price=" + e.goods_info.dd_price,
          success: function (res) {
            console.log('success');
          },
          error: function (res) {
            // 通过eventChannel向被打开页面传送数据
            console.log('error');
          }

            
        });
    },
    group_pay: function(t) {
        var e = this.data;
        1 == e.already_group ? wx.showModal({
            title: "温馨提示",
            content: "您已经开过团了，是否跳转至我发起的拼团",
            success: function(t) {
                t.confirm && wx.navigateTo({
                    url: "group?id=" + e.already.id + "&user_id=" + wx.getStorageSync("users").id + "&goods_id=" + e.goods_info.id
                });
            }
        }) : 0 == e.clock ? wx.showModal({
            title: "",
            content: "该商品拼团已结束"
        }) : wx.navigateTo({
            url: "place_order?id=" + e.goods_info.id + "&type=2&kt_num=" + e.goods_info.people + "&group_id=&end_time=" + e.goods_info.end_times + "&xf_time=" + e.goods_info.xf_times + "&name=" + e.goods_info.name + "&price=" + e.goods_info.pt_price
        });
    },
    onReady: function() {},
    onShow: function() {
        var e = this;
        app.getUserInfo(function(t) {
            console.log(t), e.setData({
                userInfo: t
            });
        }), e.refresh();
    },
    onHide: function() {},
    onUnload: function() {
        this.setData({
            clock: !1
        });
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    countdown: function(t) {
        var e = this, o = (t || []) - Math.round(new Date().getTime() / 1e3) || [];
        o <= 0 ? (app.util.request({
            url: "app/Running/UpdateGroup",
            data: {
                store_id: e.data.id
            },
            success: function(t) {
                console.log(t);
            }
        }), e.setData({
            clock: !1
        })) : 0 < o && 0 != e.data.clock && (e.dateformat(o), setTimeout(function() {
            o -= 1e3, e.countdown(t);
        }, 1e3));
    },
    dateformat: function(t) {
        var e = Math.floor(t), o = Math.floor(e / 3600 / 24), a = Math.floor(e / 3600 % 24), n = Math.floor(e / 60 % 60), i = Math.floor(e % 60);
        o < 10 && (o = "0" + o), a < 10 && (a = "0" + a), i < 10 && (i = "0" + i), n < 10 && (n = "0" + n), 
        this.setData({
            day: o,
            hour: a,
            min: n,
            sec: i
        });
    },
    ormatDate: function(t) {
        var e = new Date(1e3 * t);
        return e.getFullYear() + "-" + o(e.getMonth() + 1, 2) + "-" + o(e.getDate(), 2) + " " + o(e.getHours(), 2) + ":" + o(e.getMinutes(), 2) + ":" + o(e.getSeconds(), 2);
        function o(t, e) {
            for (var o = "" + t, a = o.length, n = "", i = e; i-- > a; ) n += "0";
            return n + o;
        }
    },
    onShareAppMessage: function(t) {
        return this.setData({
            share: !0
        }), {
            title: wx.getStorageSync("users").name + "邀请您一起来拼团",
           path: "/pages/collage/info?id=" + this.data.id +"?fuid =" + this.data.id,
            success: function(t) {
                console.log(t);
            },
            complete: function(t) {
                console.log("执行");
            }
        };
    }
});