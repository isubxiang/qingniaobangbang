var app = getApp();

Page({
  data: {
    mdoaltoggle: !0,
    sign_up: [{
      name: "姓名"
    }, {
      name: "手机号"
    }]
  },
  KeyName: function (e) {
    this.setData({
      name: e.detail.value
    });
  },
  KeyAddr: function (e) {
    this.setData({
      addr: e.detail.value
    });
  },
  KeyMobile: function (e) {
    this.setData({
      mobile: e.detail.value
    });
  },
  tjddformSubmit: function (e) {
    console.log(e);
    var o = wx.getStorageSync("users").id;

    //获取学校
    var homeData = wx.getStorageSync("homeData");
    var schoolId = homeData.schoolId;

    var settings = wx.getStorageSync("settings");
    var SessionId = settings.SessionId;

    console.log('学校ID=' + schoolId);
    console.log('SessionId=' + SessionId);


    app.util.request({
      url: "app/Running/SaveFormid",
      cachetime: "0",
      data: {
        user_id: o ? o : SessionId,
        form_id: e.detail.formId
      },
      success: function (e) {
        console.log(e.data);
      }
    });
  },
  ckwz: function (e) {
    console.log(e.currentTarget.dataset.jwd);
    var o = e.currentTarget.dataset.jwd.split(",");
    console.log(o);
    wx.openLocation({
      latitude: Number(o[0]),
      longitude: Number(o[1]),
      name: this.data.store.store_name,
      address: this.data.store.address
    });
  },
  onLoad: function (e) {

    console.log('====orderonLoad====');

    var o = this;

    app.setNavigationBarColor(this), console.log(e), o.setData({
      options: e
    }), app.getUserInfo(function (e) {
      console.log(e), o.setData({
        user_id: e.id,
        openid: e.openid
      });
    }), o.refresh();
  },
  
  refresh: function (e) {
    var t = this;
    app.util.request({
      url: "app/Running/GroupType",
      cachetime: "0",
      success: function (e) {
        console.log("分类列表", e), t.setData({
          nav_array: e.data
        });
      }
    }), app.util.request({
      url: "app/Running/GoodsInfo",
      cachetime: "0",
      data: {
        goods_id: t.data.options.id
      },
      success: function (e) {
        console.log("商品详情", e);
        var o = e.data.goods;
        1 == t.data.options.type ? (o.yh = (Number(o.y_price) - Number(o.dd_price)).toFixed(2),
          o.money = o.dd_price) : (o.yh = (Number(o.y_price) - Number(o.pt_price)).toFixed(2),
            o.money = o.pt_price), t.setData({
              goods: e.data.goods
            }), t.store_info(e.data.goods.store_id);
      }
    });
  },
  store_info: function (e) {
    var n = this;
    app.util.request({
      url: "app/Running/StoreInfo",
      cachetime: "0",
      data: {
        id: e
      },
      success: function (e) {
        console.log(e.data);
        var o = e.data.store[0], t = e.data.store[0].coordinates.split(","), a = {
          lng: Number(t[1]),
          lat: Number(t[0])
        };
        console.log(a), n.setData({
          store: o
        });
      }
    });
  },
  alone: function (e) {
    wx.showLoading({
      title: "正在提交报名",
      mark: !0
    });
    var o = this;
    o.setData({
      place_num: 2
    });

    console.log(o.data);
    var user_id = wx.getStorageSync("users").id;

    //获取学校
    var homeData = wx.getStorageSync("homeData");
    var schoolId = homeData.schoolId;

    var settings = wx.getStorageSync("settings");
    var SessionId = settings.SessionId;

    console.log('学校ID=' + schoolId);
    console.log('SessionId=' + SessionId);



    var t = o.data, a = t.goods, n = t.options, i = t.nav_array, s = t.user_id;
    for (var r in i) if (i[r].id == a.type_id) var d = i[r].name;
    if (1 == n.type) var c = a.dd_price; else c = a.pt_price;
    var u = c;
    1 == o.confirm_info() && app.util.request({
      url: "app/Running/SaveGroupOrder",
      data: {
        user_id: user_id ? user_id : SessionId,
        goods_id: a.id,
        school_id: schoolId,
        logo: a.logo,
        store_id: a.store_id,
        goods_name: a.name,
        goods_type: d,
        price: c,
        goods_num: 1,
        money: u,
        receive_name: t.name,
        receive_tel: t.mobile,
        receive_addr: t.addr,
        receive_address: t.store.address,
        type: t.options.type,
        pay_type: 1,
        kt_num: t.options.kt_num,
        group_id: n.group_id,
        dq_time: a.end_time,
        xf_time: a.xf_time
      },

      success: function (e) {
        console.log(e), console.log("确定调用"), "商品已销售完毕或拼团已失效" == e.data ? wx.showModal({
          title: "温馨提示",
          content: "商品已售完",
          success: function (e) {
            e.cancel && wx.reLaunch({
              url: "../errand/_/index",
            });
          }
        }) : o.pay(e.data, u);
      }
    });
  },


  confirm_info: function (e) {
    var o = this, t = o.data;
    if (console.log(t), 0 < Number(t.goods.inventory)) if (null == t.name || "" == t.name) wx.showModal({
      title: "温馨提示",
      content: "请输入您的姓名"
    }), o.setData({
      place_num: 1
    }), wx.hideLoading(); else if (null == t.addr || "" == t.addr) wx.showModal({
      title: "温馨提示",
      content: "请输入您的地址"
    }), o.setData({
      place_num: 1
    }), wx.hideLoading(); else {
      if (null != t.mobile && "" != t.mobile) return !0;
      wx.showModal({
        title: "温馨提示",
        content: "请输入您的联系电话"
      }), o.setData({
        place_num: 1
      }), wx.hideLoading();
    } else wx.showModal({
      title: "温馨提示",
      content: "商品库存不足，无法购买"
    }), o.setData({
      place_num: 1
    }), wx.hideLoading();
  },


  pay: function (e, o) {
    var t = this;
    console.log("调用微信支付");
    var a = t.data.openid;
    var openid = wx.getStorageSync("openid");



    //获取学校
    var homeData = wx.getStorageSync("homeData");
    var schoolId = homeData.schoolId;

    var settings = wx.getStorageSync("settings");
    var SessionId = settings.SessionId;

    console.log('学校ID=' + schoolId);
    console.log('SessionId=' + SessionId);


    app.util.request({
      url: "app/Running/GroupPay",
      data: {
        order_id: e,
        money: o,
        openid: a ? a : openid,
        user_id: SessionId,
        school_id: schoolId
      },
      success: function (e) {
        console.log(e), wx.requestPayment({
          timeStamp: e.data.timeStamp,
          nonceStr: e.data.nonceStr,
          package: e.data.package,
          signType: e.data.signType,
          paySign: e.data.paySign,



          success: function (s) {
            console.log(s),
              wx.hideLoading(),

              app.util.request({
                url: "app/Running/SavePayLog",
                cachetime: "0",
                data: {
                  log_id: e.data.log_id,
                },
              })


            wx.showToast({
              title: "支付成功"
            }), setTimeout(function () {
              t.setData({
                place_num: 2
              }), wx.redirectTo({
                url: "group_order"
              });
            }, 1500);
          },


          fail: function (e) {
            console.log(e), wx.showLoading({
              title: "支付失败"
            }), setTimeout(function () {
              wx.hideLoading(), t.setData({
                place_num: 2
              }), wx.reLaunch({
                url: "../errand/_/index",
              });
            }, 1500);
          }
        });
      }
    });
  },
  onReady: function () { },
  onShow: function () { },
  onHide: function () { },
  onUnload: function () { },
  onPullDownRefresh: function () { },
  onReachBottom: function () { },


});