var app = getApp();

Page({
    data: {
        tabs: [ "全部", "审核中", "已通过", "已拒绝" ],
        activeIndex: 0,
        sliderOffset: 0,
        sliderLeft: 15,
        iszd: !1,
        refresh_top: !1,
        postlist: [],
        page: 1,
        countries: [ "本地", "全国" ],
        countryIndex: 0,
        xzdq: !1
    },


  getUserinfo: function () {
    var s = this;
    wx.login({
      success: function (t) {
        var e = t.code;
        wx.setStorageSync("code", e), wx.getSetting({
          success: function (t) {
            console.log(t), t.authSetting["scope.userInfo"] ? wx.getUserInfo({
              success: function (t) {
                wx.setStorageSync("user_info", t.userInfo);
                var i = t.userInfo.nickName, n = t.userInfo.avatarUrl;
                app.util.request({
                  url: "app/Running/ThreadOpenid",
                  cachetime: "0",
                  data: {
                    code: e,
                    encryptedData: t.encryptedData,
                    iv: t.iv,
                    nickName: t.userInfo.nickName,
                    avatarUrl: t.userInfo.avatarUrl,
                  },
                  success: function (t) {
                    wx.setStorageSync("key", t.data.session_key);
                    var e = n, a = i;
                    wx.setStorageSync("openid", t.data.openid);
                    var o = t.data.openid;
                    app.util.request({
                      url: "app/Running/ThreadLogin",
                      cachetime: "0",
                      data: {
                        openid: o,
                        img: e,
                        name: a,
                        user_id: t.data.user_id,
                      },
                      success: function (t) {
                        wx.setStorageSync("users", t.data), wx.setStorageSync("uniacid", t.data.uniacid),
                          s.setData({
                            user_id: t.data.id,
                            user_name: a
                          });
                      }
                    });
                  }
                });
              }
            }) : (console.log("未授权过"), s.setData({
              hydl: !0
            }));
          }
        });
      }
    });
  },


    bindCountryChange: function(t) {
        var e = this.data.stick;
        console.log("picker country 发生选择改变，携带值为", t.detail.value, e);
        this.setData({
            countryIndex: t.detail.value,
            iszd: !0
        });
    },
    qxxzdq: function() {
        this.setData({
            xzdq: !1
        });
    },
    qxzd: function() {
        this.setData({
            iszd: !1
        });
    },


    dkxf: function(t) {
        console.log(t.currentTarget.dataset.id, this.data.System), "0" == this.data.System.is_qgb ? this.setData({
            xzdq: !0,
            xfid: t.currentTarget.dataset.id
        }) : this.setData({
            iszd: !0,
            xfid: t.currentTarget.dataset.id
        });
    },



    shuaxin: function(t) {
        if (this.data.isios && "2" == this.data.System.is_pgzf) wx.showModal({
            title: "暂不支持",
            content: "十分抱歉，由于相关规范，您暂时无法进行支付",
            showCancel: !1,
            confirmText: "好的",
            confirmColor: "#666"
        }); else {
            var a = this, o = t.currentTarget.dataset.id, n = wx.getStorageSync("openid"), s = wx.getStorageSync("users").id;
            console.log(o, t.currentTarget.dataset.typeid, n), app.util.request({
                url: "app/Running/ThreadSxMoney",
                cachetime: "0",
                data: {
                    type_id: t.currentTarget.dataset.typeid,
                    id: o
                },
                success: function(t) {
                    console.log(t);
                    var e = Number(t.data.sx_money);
                    console.log(e), wx.showModal({
                        title: "提示",
                        content: "刷新此帖子需付费" + e + "元",
                        confirmText: "确定刷新",
                        success: function(t) {
                            if (t.confirm) if (console.log("用户点击确定"), e <= 0) console.log("免费刷新"), app.util.request({
                                url: "app/Running/ThreadSxTz",
                                cachetime: "0",
                                data: {
                                    id: o
                                },
                                success: function(t) {
                                    console.log(t), 1 == t.data && (wx.showToast({
                                        title: "刷新帖子成功"
                                    }), setTimeout(function() {
                                        wx.reLaunch({
                                            url: "../errand/_/index",
                                        });
                                    }, 1e3));
                                }
                            }); else {
                                if (console.log("付费刷新"), a.data.isios && "2" == a.data.System.is_pgzf) return void wx.showModal({
                                    title: "暂不支持",
                                    content: "十分抱歉，由于相关规范，您暂时无法进行支付",
                                    showCancel: !1,
                                    confirmText: "好的",
                                    confirmColor: "#666"
                                });
                                app.util.request({
                                    url: "app/Running/ThreadPay",
                                    cachetime: "0",
                                    data: {
                                        openid: n,
                                        money: e
                                    },
                                    success: function(t) {
                                        wx.requestPayment({
                                            timeStamp: t.data.timeStamp,
                                            nonceStr: t.data.nonceStr,
                                            package: t.data.package,
                                            signType: t.data.signType,
                                            paySign: t.data.paySign,
                                            success: function(t) {
                                                wx.showModal({
                                                    title: "提示",
                                                    content: "支付成功",
                                                    showCancel: !1
                                                });
                                            },
                                            complete: function(t) {
                                                console.log(t), "requestPayment:fail cancel" == t.errMsg && wx.showToast({
                                                    title: "取消支付",
                                                    icon: "loading",
                                                    duration: 1e3
                                                }), "requestPayment:ok" == t.errMsg && (app.util.request({
                                                    url: "app/Running/ThreadSxTz",
                                                    cachetime: "0",
                                                    data: {
                                                        id: o
                                                    },
                                                    success: function(t) {
                                                        console.log(t);
                                                    }
                                                }), app.util.request({
                                                    url: "app/Running/ThreadSaveTzPayLog",
                                                    cachetime: "0",
                                                    data: {
                                                        tz_id: o,
                                                        money: e,
                                                        money5: e
                                                    },
                                                    success: function(t) {}
                                                }), app.util.request({
                                                    url: "app/Running/ThreadFx",
                                                    cachetime: "0",
                                                    data: {
                                                        user_id: s,
                                                        money: e
                                                    },
                                                    success: function(t) {
                                                        console.log(t);
                                                    }
                                                }), setTimeout(function() {
                                                    wx.reLaunch({
                                                        url: "../errand/_/index",
                                                    });
                                                }, 1e3));
                                            }
                                        });
                                    }
                                });
                            } else t.cancel && console.log("用户点击取消");
                        }
                    });
                }
            });
        }
    },


    selected: function(t) {
        var e = this, 
        a = this.data.countryIndex, 
        o = t.currentTarget.id, 
        n = wx.getStorageSync("openid"), 
        s = wx.getStorageSync("users").id, 
        i = e.data.stick, c = 0 == a ? i[o].money : i[o].money2, 
        r = i[o].type, l = this.data.xfid, 
        u = 0 == e.data.countryIndex ? "本地" : "全国版", 
        d = wx.getStorageSync("city");
        
        if (console.log("city", u, d), e.setData({
            iszd: !1,
            xzdq: !1
        }), console.log(c, r, l), Number(c) <= 0) app.util.request({
            url: "app/Running/ThreadTzXf",
            cachetime: "0",
            data: {
                id: l,
                type: r,
                cityname: u,
                cityname2: d
            },
            success: function(t) {
                console.log(t), wx.showToast({
                    title: "操作成功",
                    mask: !0,
                    duration: 1e3
                }), setTimeout(function() {
                    wx.navigateBack({});
                }, 1e3);
            }
        }); else {
            if (e.data.isios && "2" == e.data.System.is_pgzf) return void wx.showModal({
                title: "暂不支持",
                content: "十分抱歉，由于相关规范，您暂时无法进行支付",
                showCancel: !1,
                confirmText: "好的",
                confirmColor: "#666"
            });
            app.util.request({
                url: "app/Running/ThreadPay",
                cachetime: "0",
                data: {
                    openid: n,
                    money: c
                },
                success: function(t) {
                    wx.requestPayment({
                        timeStamp: t.data.timeStamp,
                        nonceStr: t.data.nonceStr,
                        package: t.data.package,
                        signType: t.data.signType,
                        paySign: t.data.paySign,
                        success: function(t) {
                            wx.showModal({
                                title: "提示",
                                content: "支付成功",
                                showCancel: !1
                            });
                        },
                        complete: function(t) {
                            console.log(t), "requestPayment:fail cancel" == t.errMsg && wx.showToast({
                                title: "取消支付",
                                icon: "loading",
                                duration: 1e3
                            }), "requestPayment:ok" == t.errMsg && (app.util.request({
                                url: "app/Running/ThreadTzXf",
                                cachetime: "0",
                                data: {
                                    id: l,
                                    type: r,
                                    cityname: u,
                                    cityname2: d
                                },
                                success: function(t) {
                                    console.log(t);
                                }
                            }), app.util.request({
                                url: "app/Running/ThreadSaveTzPayLog",
                                cachetime: "0",
                                data: {
                                    tz_id: l,
                                    money: c,
                                    money4: c
                                },
                                success: function(t) {}
                            }), app.util.request({
                                url: "app/Running/ThreadFx",
                                cachetime: "0",
                                data: {
                                    user_id: s,
                                    money: c
                                },
                                success: function(t) {
                                    console.log(t);
                                }
                            }), setTimeout(function() {
                                wx.navigateBack({});
                            }, 1e3));
                        }
                    });
                }
            });
        }
    },
    tabClick: function(t) {
        console.log(t), this.setData({
            sliderOffset: t.currentTarget.offsetLeft,
            activeIndex: t.currentTarget.id
        });
    },


    onLoad: function(t) {
        var o = this;

        app.setNavigationBarColor(this);
        
        var e = wx.getStorageSync("users").id;
        null == t.type ? (o.getUserinfo(), o.setData({
          post_info_id: t.my_post
        })) : null != t.scene ? o.setData({
          user_id: e,
          post_info_id: t.scene,
          user_name: wx.getStorageSync("users").name
        }) : o.setData({
          user_id: e,
          post_info_id: t.id,
          user_name: wx.getStorageSync("users").name
        }), 
        
       
      
        
        app.util.request({
            url: "app/Running/ThreadSystem",
            cachetime: "0",
            success: function(t) {
                console.log(t), o.setData({
                    System: t.data
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadTop",
            cachetime: "0",
            success: function(t) {
                console.log(t);
                var e = t.data;
                for (var a in e) 1 == e[a].type ? e[a].array = "置顶一天（收费" : 2 == e[a].type ? e[a].array = "置顶一周（收费" : 3 == e[a].type && (e[a].array = "置顶一月（收费");
                console.log(e), o.setData({
                    stick: e
                });
            }
        }), wx.getSystemInfo({
            success: function(t) {
                console.log(t), -1 != t.system.indexOf("iOS") ? (console.log("ios"), o.setData({
                    isios: !0
                })) : console.log("andr");
            }
          }), o.reload();

        
    },





    reload: function(t) {
      var i = this, e = wx.getStorageSync("users").id, s = wx.getStorageSync("settings"), c = wx.getStorageSync("url"), r = wx.getStorageSync("users").img, l = i.data.page, u = i.data.postlist;
        
        
        console.log(e), 
        
        
        app.util.request({
            url: "app/Running/ThreadMyPost",
            cachetime: "0",
            data: {
                user_id: e ? e : s.SessionId,
                pagesize: 10,
                page: l
            },
            success: function(t) {
                console.log(t), i.setData({
                    page: l + 1
                }), console.log(t), t.data.length < 10 ? i.setData({
                    refresh_top: !0
                }) : i.setData({
                    refresh_top: !1
                }), u = u.concat(t.data), console.log(u);
                var e = [], a = [], o = [];
                for (var n in t.data) t.data[n].time = i.ormatDate(t.data[n].time).slice(0, 16), 
                t.data[n].img = t.data[n].img.split(",").slice(0, 4);
                for (var s in u) 1 == u[s].state && null != u[s].type_name ? e.push(u[s]) : 2 == u[s].state && null != u[s].type_name ? a.push(u[s]) : 3 == u[s].state && null != u[s].type_name && o.push(u[s]);
                i.setData({
                    postlist: u,
                    slide: u,
                    user_img: r,
                    url: c,
                    audit: e,
                    adopt: a,
                    refuse: o
                });
            }
        });
    },
    see: function(t) {
        console.log(t), console.log(this.data);
        var e = this.data.slide, a = t.currentTarget.dataset.id;
        for (var o in e) if (e[o].id == a) var n = e[o];
        console.log(n), wx.navigateTo({
            url: "../detail?id=" + n.id,
            success: function(t) {},
            fail: function(t) {},
            complete: function(t) {}
        });
    },
    ormatDate: function(t) {
        var e = new Date(1e3 * t);
        return e.getFullYear() + "-" + a(e.getMonth() + 1, 2) + "-" + a(e.getDate(), 2) + " " + a(e.getHours(), 2) + ":" + a(e.getMinutes(), 2) + ":" + a(e.getSeconds(), 2);
        function a(t, e) {
            for (var a = "" + t, o = a.length, n = "", s = e; s-- > o; ) n += "0";
            return n + a;
        }
    },

   
    cancel: function(a) {
        var o = this;
        wx.showModal({
            title: "提示",
            content: "是否删除帖子",
            showCancel: !0,
            cancelText: "取消",
            confirmText: "确定",
            success: function(t) {
                if (t.confirm) {
                    console.log("用户点击确定");
                    var e = a.currentTarget.dataset.id;
                    app.util.request({
                        url: "app/Running/ThreadDelPost",
                        cachetime: "0",
                        data: {
                            id: e
                        },
                        success: function(t) {
                            console.log(t), 
                            1 == t.data && (o.setData({
                                activeIndex: 0,
                                refresh_top: !1,
                                slide: [],
                                postlist: [],
                                page: 1
                            }), 
                            
                            o.reload());
                        }

                    });
                } else t.cancel && console.log("用户点击取消");
            },
            fail: function(t) {},
            complete: function(t) {}
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {
        this.setData({
            activeIndex: 0,
            refresh_top: !1,
            postlist: [],
            page: 1
        }), this.reload(), wx.stopPullDownRefresh();
    },
    onReachBottom: function() {
        console.log("上拉加载", this.data.page), 0 == this.data.refresh_top && this.reload();
    },
    onShareAppMessage: function() {}
});