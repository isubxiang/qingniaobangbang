var dsq, app = getApp();

Page({
    data: {},
    phone: function(t) {
        var e = this.data.store.tel;
        wx.makePhoneCall({
            phoneNumber: e
        });
    },
    dizhi: function(t) {
        var e = this, a = Number(e.data.store.coordinates.split(",")[0]), o = Number(e.data.store.coordinates.split(",")[1]);
        wx.openLocation({
            latitude: a,
            longitude: o,
            name: e.data.store.store_name,
            address: e.data.store.address
        });
    },

    qrmd: function(t) {
      var a = Number(this.data.yhq.money), 
      e = wx.getStorageSync("users").id, 
      u = wx.getStorageSync("users"), 
      o = this.data.store.id,
      n = wx.getStorageSync("openid"), 
      s = t.detail.formId, 
      i = this.data.yhq.id;

      console.log(a, e, o, n, s, i), 
      app.util.request({
            url: "app/Running/SaveFormid",
            cachetime: "0",
            data: {
                user_id: e,
                form_id: s,
                openid: n
            },
            success: function(t) {}
        }), this.setData({
            mflqdisabled: !0
        }), app.util.request({
            url: "app/Running/LqCoupon",
            cachetime: "0",
            data: {
                user_id: e,
                coupons_id: i,
                lq_money: a
            },
            success: function(t) {
                console.log(t);
                var e = t.data;
                1 == t.data.code ? 0 == a ? (wx.showModal({
                    title: "提示",
                    content: t.data.msg
                }), setTimeout(function() {
                    wx.redirectTo({
                        url: "../coupon/user"
                    });
                }, 1e3)) : app.util.request({
                    url: "app/Running/pay5",
                    cachetime: "0",
                    data: {
                        user_id: u.id,
                        openid: u.openid,
                        money: a,
                        download_id: e.download_id
                    },


                    success: function(t) {
                        console.log(t), wx.requestPayment({
                            timeStamp: t.data.timeStamp,
                            nonceStr: t.data.nonceStr,
                            package: t.data.package,
                            signType: t.data.signType,
                            paySign: t.data.paySign,
                            success: function(t) {
                                console.log(t);
                            },
                            complete: function(t) {
                                console.log(t), "requestPayment:fail cancel" == t.errMsg && wx.showToast({
                                    title: "取消支付"
                                }), "requestPayment:ok" == t.errMsg && (wx.showModal({
                                    title: "提示",
                                    content: "领取成功"
                                }), setTimeout(function() {
                                    wx.redirectTo({
                                        url: "../coupon/user"
                                    });
                                }, 1e3));
                            }
                        });
                    }
                  }) : wx.showModal({
                    title: "错误提示",
                    content: t.data.msg
                });
            }
        });
    },


    onLoad: function(t) {
        console.log(t);
        var e = this, a = wx.getStorageSync("users").id;

        console.log(a);
        if (a == '' || a == undefined) {
          app.getUserInfo(function (t) {
            console.log('登录领取优惠券==app.getUserInfo==');
            console.log(t),
              wx.setStorageSync("users", t),
              e.setData({
                userInfo: t
              });
          });
        }


        console.log(a), e.setData({
            coupon_img: wx.getStorageSync("System").coupon_img
        }), app.util.request({
            url: "app/Running/CouponInfo",
            cachetime: "0",
            data: {
                coupon_id: t.yhqid,
                user_id: a
            },
            success: function(t) {
                console.log(t.data),
                wx.setNavigationBarTitle({
                    title: t.data.name
                }), e.setData({
                    yhq: t.data,
                    url: wx.getStorageSync("url")
                });
            }
        }), 

        console.log('-----ttt--'),
        console.log(t),
        app.util.request({
            url: "app/Running/StoreInfo",
            cachetime: "0",
            data: {
                id: t.sjid
            },
            success: function(t) {
                console.log(t.data), e.setData({
                    store: t.data.store[0]
                });
            }
        }), null != t.qid && (app.util.request({
            url: "app/Running/CouponCode",
            cachetime: "0",
            data: {
                id: t.qid
            },
            success: function(t) {
                console.log(t.data), e.setData({
                    hxm: t.data
                });
            }
        }), dsq = setInterval(function() {
            app.util.request({
                url: "app/Running/MyCouponsInfo",
                cachetime: "0",
                data: {
                    id: t.qid,
                    user_id: a
                },
                success: function(t) {
                    console.log(t.data),
                    
                    1 == t.data.code && (wx.showToast({
                        title: t.data.msg,
                        duration: 1e3
                    }), 
                    
                    setTimeout(function() {
                        wx.reLaunch({
                            url: "/pages/errand/_/index"
                        });
                    }, 1e3));


                }
            });
        }, 5e3));
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {
        clearInterval(dsq);
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});