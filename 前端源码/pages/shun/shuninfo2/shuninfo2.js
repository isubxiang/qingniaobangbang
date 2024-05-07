var app = getApp();

Page({
    data: {
        hydl: !1
    },
    updateUserInfo: function(e) {
        console.log(e), "getUserInfo:ok" == e.detail.errMsg && (this.setData({
            hydl: !1
        }), this.getuserinfo());
    },
    getuserinfo: function() {
        var o = this;
        wx.login({
            success: function(e) {
                console.log("这是登录所需要的code"), console.log(e.code);
                var t = e.code;
                wx.setStorageSync("code", t), wx.getSetting({
                    success: function(e) {
                        console.log(e), e.authSetting["scope.userInfo"] ? wx.getUserInfo({
                            success: function(e) {
                                wx.setStorageSync("user_info", e.userInfo);
                                var a = e.userInfo.nickName, s = e.userInfo.avatarUrl;
                                o.setData({
                                    user_name: a
                                }), console.log("用户名字"), console.log(e.userInfo.nickName), console.log("用户头像"), 
                                console.log(e.userInfo.avatarUrl), app.util.request({
                                    url: "app/Running/ThreadOpenid",
                                    cachetime: "0",
                                    data: {
                                        code: t,
                                        encryptedData: e.encryptedData,
                                        iv: e.iv,
                                        nickName: e.userInfo.nickName,
                                        avatarUrl:e.userInfo.avatarUrl,
                                    },
                                    success: function(e) {
                                        wx.setStorageSync("key", e.data.session_key);
                                        var t = s, o = a;
                                        wx.setStorageSync("openid", e.data.openid);
                                        var n = e.data.openid;

                                        var fuid = wx.getStorageSync("fuid");

                                        console.log("这是用户的openid"), console.log(n), 

                                    
                                        
                                        app.util.request({
                                            url: "app/Running/ThreadLogin",
                                            cachetime: "0",
                                            data: {
                                                openid: n,
                                                img: t,
                                                name: o,
											                        	user_id:e.data.user_id,
                                                fuid: fuid
                                            },
                                            success: function(e) {
                                                console.log("这是用户的登录信息"), console.log(e), 
                                                wx.setStorageSync("users", e.data), wx.setStorageSync("uniacid", e.data.uniacid);
                                            }
                                        });
                                    }
                                });
                            }
                        }) : (console.log("未授权过"), o.setData({
                            hydl: !0
                        }));
                    }
                });
            }
        });
    },
    onLoad: function(e) {
        var n = this;
        app.util.request({
            url: "app/Running/ThreadSystem",
            cachetime: "0",
            success: function(e) {
                console.log(e), n.setData({
                    system: e.data
                }), 
                
                app.setNavigationBarColor(this);
            }
        }), app.util.request({
            url: "app/Running/ThreadUrl",
            cachetime: "0",
            success: function(e) {
                wx.setStorageSync("url", e.data), n.setData({
                    url: e.data
                });
            }
        }), e.iszf ? (console.log("存在"), this.getuserinfo()) : console.log("不存在"), console.log(e);
        var t, o, a;
        t = new Date(), o = t.getMonth() + 1, a = t.getDate(), 1 <= o && o <= 9 && (o = "0" + o), 
        0 <= a && a <= 9 && (a = "0" + a), t.getFullYear(), t.getHours(), t.getMinutes(), 
        t.getSeconds();
        app.util.request({
            url: "app/Running/CarInfo",
            cachetime: "0",
            data: {
                id: e.id
            },
            success: function(e) {
                console.log(e);
                var t = e.data.pc, o = e.data.tag;
                t.time = app.ormatDate(t.time).slice(0, 16), t.start_time1 = t.start_time.slice(0,16), 
                t.start_time2 = t.start_time.slice(0,16), n.setData({
                    pc: t,
                    tag: o
                });
            }
        });
    },
    call_phone: function(e) {
        console.log(e), wx.makePhoneCall({
            phoneNumber: e.currentTarget.dataset.tel
        });
    },
    dizhi1: function(e) {
        var t = this, o = Number(t.data.pc.star_lat), n = Number(t.data.pc.star_lng);
        console.log(o), console.log(n), wx.openLocation({
            latitude: o,
            longitude: n,
            name: t.data.pc.link_name,
            address: t.data.pc.start_place
        });
    },
    dizhi2: function(e) {
        var t = this, o = Number(t.data.pc.end_lat), n = Number(t.data.pc.end_lng);
        console.log(o), console.log(n), wx.openLocation({
            latitude: o,
            longitude: n,
            name: t.data.pc.link_name,
            address: t.data.pc.end_place
        });
    },

    shouye: function(e) {
        console.log(e), wx.reLaunch({
            url: "../../errand/_/index",
            success: function(e) {},
            fail: function(e) {},
            complete: function(e) {}
        });
    },

    fabu: function(e) {
        wx.reLaunch({
            url: "../shun",
            success: function(e) {},
            fail: function(e) {},
            complete: function(e) {}
        });
    },
    phone: function(e) {
        var t = e.currentTarget.dataset.tel;
        wx.makePhoneCall({
            phoneNumber: t
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {
        console.log(this.data);
        wx.getStorageSync("users").id;
        return {
            title: this.data.yellow_info.company_name,
            path: "/pages/shun/shuninfo2/shuninfo2?id=" + this.data.pc.id + "&iszf=1",
            success: function(e) {},
            fail: function(e) {}
        };
    }
});