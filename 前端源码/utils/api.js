var e = require("util.js"), n = (require("enums.js"), require("md5.js"), function(e, n) {
    for (var i = [], t = 0; t < e.length; ++t) t >= n && i.push(e[t]);
    return i;
}), i = function(n) {
    var i = {
        data: null,
        success: null,
        fail: null
    }, t = function(n) {
        var t = 0;
        "function" != typeof n[0] && (e.extend(i, {
            data: n[0]
        }), ++t), n.length > t && (i.success = n[t], n.length >= t + 2 && (i.fail = n[t + 1]));
    };
    return "function" == typeof n[0] || n.length > 1 && "function" == typeof n[1] ? t(n) : 1 == n.length && void 0 !== n[0].length ? t(n[0]) : (i.data = n[0], 
    t(n[1])), i;
}, t = function(t, r) {
    var o = i(n(arguments, 1));
    e.request(t, o.data, "GET", o.success, o.fail);
}, r = function(t, r) {
    var o = i(n(arguments, 1));
    e.request(t, o.data, "POST", o.success, o.fail);
}, o = function() {
    return getApp().globalData.locationInfo || {};
};

module.exports = {
    commonSmsCode: function() {
        t("/App/Running/SendVerifySms", {
            type: 1
        }, arguments);
    },
    commonUpload: function(n, i, t) {
        if (n) {
            var r = n instanceof Array, o = [], d = r ? n : [ n ];
            if (d.length) {
                !function n(s) {
                    s = s || 0, e.upload(d[s], null, function(e) {
                        o.push(e), o.length != d.length ? i && ++s < d.length && n(s) : t && t(r ? o : o[0]);
                    }), !i && ++s < d.length && n(s);
                }();
            }
        }
    },


    getSetting: function () {
      t("/App/Running/getSetting", {
        id: 0,
      }, arguments);
    },

    homeNearestSchool: function() {
        t("/App/Running/HomeNearestSchool", {
            longitude: 0,
            latitude: 0,
            school: ""
        }, arguments);
    },
    homeIndex: function() {
        t("/App/Running/HomeIndex", {
            pageIndex: 0,
            pageSize: 10,
            scene: ""
        }, arguments);
    },
    homeMine: function() {
        t("/App/Running/HomeMine", {
            mine: ""
        }, arguments);
    },
    notifyFlag2: function () {
      t("/App/Running/notifyFlag2", {
        mine: ""
      }, arguments);
    },
    shopIndex: function() {
        t("/App/Running/ShopIndex", {
            id: 0,
            type: "",
            groupId: 0,
            scene: ""
        }, arguments);
    },
	
	  shopList: function() {
        t("/App/Running/ShopList", {
            type: "",
            scene: "",
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },
	
	
    shopCategories: function() {
        t("/App/Running/ShopShopCategories", {
            categories: ""
        }, arguments);
    },
    shopCommodityList: function() {
        t("/App/Running/ShopCommodityList", {
            commodityList: ""
        }, arguments);
    },
    loginWechat: function() {
        
        t("/App/Running/LoginLoginWechat", {
            wechat: "",
        }, arguments);
    },

    updateWechatInfo: function() {
      var fuid = wx.getStorageSync("fuid");
      console.log('==推荐人ID=='+fuid);
      console.log(fuid);
      r("/App/Running/UserUpdateUserInfo",  {
        fuid: fuid,
      }, arguments);
    },

    orderErrandList: function() {
        t("/App/Running/OrderErrandOrderList", {
            stype: 0,
            pageIndex: 0,
            pageSize: 10,
            errandlist: ""
        }, arguments);
    },
    orderErrandPrepare: function() {
        t("/App/Running/OrderErrandOrderPrepare", {
            errandPrepare: ""
        }, arguments);
    },
    orderTakeoutPrepare: function() {
        t("/App/Running/OrderTakeoutOrderPrepare", {
            groupId: 0,
            takeoutPrepare: ""
        }, arguments);
    },
    orderAcceptInfo: function() {
        t("/App/Running/OrderOrderInfoForAccept", {
            forAccept: ""
        }, arguments);
    },
    orderInfo: function() {
        t("/App/Running/OrderOrderInfo", arguments);
    },


    orderList: function() {
        t("/App/Running/OrderOrderList", {
            role: 1,
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },

    pingList: function () {
      t("/App/Running/pingList", {
        role: 1,
        pageIndex: 0,
        pageSize: 10,
        uid: 0
      }, arguments);
    },


    orderCommit: function() {
        r("/App/Running/OrderOrderCommit", {
            latitude: o().latitude,
            longitude: o().longitude
        }, arguments);
    },
    orderAccept: function() {
        t("/App/Running/OrderOrderAccept", {
            latitude: o().latitude,
            longitude: o().longitude,
            accept: ""
        }, arguments);
    },
	
	orderSavePayLog: function () {
      t("/App/Running/savepaylog", {
        accept: ""
      }, arguments);
    },
	
    orderPayment: function(n, i, r) {
        var o = this;
        t("/App/Running/OrderOrderPayment", e.extend({
            money: 0,
            payment: ""
        }, n), function(n) {
            if (n.IsSuccess) setTimeout(function() {
                wx.showLoading({
                    title: "请稍后",
                    mask: !0
                });
            }, 1e3), wx.requestPayment({
                timeStamp: n.TimeStamp,
                nonceStr: n.NonceStr,
                package: n.Package,
                signType: n.SignType,
                paySign: n.PaySign,
                success: function(e) {
                    o.orderPaymentCheck({
                        paymentCode: n.Code,
						            id: n.order_id,
                        log_id: n.log_id
                    }, function(e) {
                        i && i(e), wx.hideLoading();
                    }, function(e) {
                        r && r(e);
                    });
                },
                fail: function(n) {
                    wx.hideLoading(), "requestPayment:fail cancel" == n.errMsg ? r && r(n) : e.toast(n.errMsg);
                },
                complete: function(e) {
                    "requestPayment:cancel" == e.errMsg && (wx.hideLoading(), r && r(e));
                }
            }); else {
                var t = getCurrentPages();
                t.length && t[t.length - 1].route.indexOf("order/_/info") >= 0 ? e.toast(n.ErrorMessage) : i && i();
            }
        });
    },
    orderPaymentCheck: function() {
        t("/App/Running/OrderPaymentCheck", {
            check: ""
        }, arguments);
    },
    orderFinish: function() {
        t("/App/Running/OrderOrderFinish", {
            finish: ""
        }, arguments);
    },
    orderConfirmFinish: function() {
      t("/App/Running/orderConfirmFinish", {
            confirmFinish: ""
        }, arguments);
    },
    orderCancel: function() {
        t("/App/Running/OrderOrderCancel", {
            cancel: ""
        }, arguments);
    },
    orderGiveup: function() {
        t("/App/Running/OrderOrderGiveup", {
            giveup: ""
        }, arguments);
    },
    orderRefund: function() {
        t("/App/Running/OrderOrderRefund", {
            refund: ""
        }, arguments);
    },
    orderComment: function() {
        t("/App/Running/OrderOrderComment", {
            comment: ""
        }, arguments);
    },
    couponList: function() {
        t("/App/Running/CouponList", arguments);
    },
    studentVerify: function() {
        r("/App/Running/StudentSaveStudentInfo", arguments);
    },
    studentRanking: function() {
        t("/App/Running/StudentGeDelivererList", arguments);
    },
    studentInfo: function() {
        t("/App/Running/StudentGetStudentInfo", arguments);
    },
    getAssginSetting: function() {
        t("/App/Running/UserSettingGetSetting", {
            errandSetting: ""
        }, arguments);
    },
    updateAssignSetting: function() {
        t("/App/Running/UserSettingUpdateAssignSettings", arguments);
    },
    schoolList: function() {
        t("/App/Running/SchoolGetSchoolList", arguments);
    },
    commonAddressList: function() {
        t("/App/Running/UserAddressCommonAddressList", {
            commonList: ""
        }, arguments);
    },
    userAddressList: function() {
        t("/App/Running/UserAddressAddressList", arguments);
    },
    userAddressInfo: function() {
        t("/App/Running/UserAddressAddressInfo", arguments);
    },
    userAddressDelete: function() {
        t("/App/Running/UserAddressDeleteInfo", {
            delete: ""
        }, arguments);
    },
    userAddressSave: function() {
        r("/App/Running/UserAddressSaveOrUpdateInfo", arguments);
    },
    userSaveFormId: function() {
        console.log(arguments);
      arguments[0] && arguments[0].formId && /^[A-Za-z\d\+\/\=]+$/.test(arguments[0].formId) ? t("/App/Running/SaveWechatFormId", arguments) : console.log("Not found form_id.");
    },
    userBindMobile: function() {
        t("/App/Running/UserBindMobile", {
            bindMobile: ""
        }, arguments);
    },
    userBindWechatMobile: function() {
        t("/App/Running/UserBindWechatMobile", {
            bindWechatMobile: ""
        }, arguments);
    },
    userAccount: function() {
        t("/App/Running/UserAccountAccountInfo", {
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },
    userWithdrawPrepare: function() {
        t("/App/Running/UserAccountWithdrawPrepare", {
            pageIndex: 0,
            pageSize: 10,
            withdrawPrepare: ""
        }, arguments);
    },
    userWithdraw: function() {
        t("/App/Running/UserAccountWithdraw", {
            withdraw: ""
        }, arguments);
    },
    userCoin: function() {
        t("/App/Running/UserCoinCoinInfo", {
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },
    adminHomeIndex: function() {
        t("/App/Running/AdminHomeIndex", {
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },
    adminUserInfo: function() {
        t("/App/Running/AdminUserInfo", arguments);
    },
    adminUserList: function() {
        t("/App/Running/AdminUserList", {
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },
    adminStudentAudit: function() {
        t("/App/Running/AdminUserStudentAudit", {
            remark: ""
        }, arguments);
    },
    adminStudentInfo: function() {
        t("/App/Running/AdminUserStudentInfo", {
            student: ""
        }, arguments);
    },
    adminSchoolList: function() {
        t("/App/Running/AdminSchoolList", {
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },
    adminOrderList: function() {
        t("/App/Running/AdminOrderList", {
            pageIndex: 0,
            pageSize: 10
        }, arguments);
    },
    adminOrderInfo: function() {
        t("/App/Running/AdminOrderInfo", arguments);
    },
    adminOrderPaymentInfo: function() {
        t("/App/Running/AdminOrderPaymentInfo", {
            paymentInfo: ""
        }, arguments);
    },
    adminOrderRefund: function() {
        t("/App/Running/AdminOrderRefund", {
            refund: ""
        }, arguments);
    },
    adminCheckPayment: function() {
        t("/App/Running/AdminOrderCheckPayment", {
            checkPayment: ""
        }, arguments);
    },
    adminCheckRefund: function() {
        t("/App/Running/AdminOrderCheckRefund", {
            checkRefund: ""
        }, arguments);
    },
    adminCommonAddressList: function() {
        t("/App/Running/AdminUserAddressCommonList", {
            pageIndex: 0,
            pageSize: 10,
            common: ""
        }, arguments);
    },

    getFundList: function () {
      t("/App/Running/getFundList", {
        checkRefund: ""
      }, arguments);
    },

    fundSta: function () {
      t("/App/Running/fundSta", {
        checkRefund: ""
      }, arguments);
    },

    getMonthSta: function () {
      t("/App/Running/getMonthSta", {
        checkRefund: ""
      }, arguments);
    },

    getNotifyInfo: function () {
      t("/App/Running/getNotifyInfo", {
        checkRefund: ""
      }, arguments);
    },

    getNotifyUrl: function () {
      t("/App/Running/getNotifyUrl", {
        checkRefund: ""
      }, arguments);
    },

    submitNotify: function () {
      t("/App/Running/submitNotify", {
        checkRefund: ""
      }, arguments);
    },

    //充值订单
    rechargeOrder: function () {
      t("/App/Running/rechargeOrder", {
        checkRefund: ""
      }, arguments);
    },


    //跑腿余额付款
    orderyuePay: function () {
      t("/App/Running/orderyuePay", {
        checkRefund: ""
      }, arguments);
    },

    //保证金
    userDeposit: function () {
      t("/App/Running/userDeposit", {
        checkRefund: ""
      }, arguments);
    },

    
    userDepositPay: function () {
      t("/App/Running/userDepositPay", {
        checkRefund: ""
      }, arguments);
    },

    //保证金微信支付
    userDepositPay: function (n, i, r) {
      var o = this;
      t("/App/Running/userDepositPay", e.extend({
        money: 0,
        payment: ""
      }, n), function (n) {

        console.log('===配送保证金支付中支付开始--userDepositPay====', n)

        if (n.IsSuccess) setTimeout(function () {
          wx.showLoading({
            title: "请稍后",
            mask: !0
          });
        }, 1e3), 
        wx.requestPayment({

          timeStamp: n.TimeStamp,
          nonceStr: n.NonceStr,
          package: n.Package,
          signType: n.SignType,
          paySign: n.PaySign,
          success: function (e) {

            console.log('===配送保证金支付中==success====', e)

            o.depositPayPaymentCheck({
              log_id: n.log_id
            }, function (e) {
              i && i(e), wx.hideLoading();
            }, function (e) {
              r && r(e);
            });
          },
          fail: function (n) {
            console.log('=配送保证金支付后=fail=', n)
            wx.hideLoading(), "requestPayment:fail cancel" == n.errMsg ? r && r(n) : e.toast('支付失败-fail');
          },
          complete: function (e) {
            console.log('=配送保证金支付后=complete=',e)
            "requestPayment:cancel" == e.errMsg && (wx.hideLoading(), r && r(e));
          }
        }); else {

          console.log('=配送保证金支付没开始=else支付失败=')
          wx.navigateTo({
            url: "pages/mine/_/index"
          });
        }
      });
    },

    //保证金微信支付后回调
    depositPayPaymentCheck: function () {
      t("/App/Running/depositPayPaymentCheck", {
        check: ""
      }, arguments);
    },

    //保证金余额支付
    userDepositMoneyPay: function () {
      t("/App/Running/userDepositMoneyPay", {
        checkRefund: ""
      }, arguments);
    },

    //保证金解冻
    userDepositThaw: function () {
      t("/App/Running/userDepositThaw", {
        checkRefund: ""
      }, arguments);
    },

    //会员红包列表
    userRedPacket: function () {
      t("/App/Running/userRedPacket", {
        checkRefund: ""
      }, arguments);
    },



};