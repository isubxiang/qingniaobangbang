var e = getApp(), a = e.require("utils/util.js"), t = e.require("utils/api.js"), n = e.require("utils/enums.js"), i = e.require("utils/onfire.js"), r = new Date(), o = a.makeArray(1, 24), d = [], s = null, u = [ [], [] ], l = {
    MinFreightMoney: 1
}, m = null, c = !1, p = !1;

Page({
    data: {
        isBusy: !1,
        dialogType: null,
        errandType: n.ErrandTypes[0],
        errandGenderLimit: n.ErrandGenderLimits[0],
        errandOrderType: n.ErrandOrderTypes[0],
        moneyPayable: l.MinFreightMoney,
        moneyPackage: 0,
        delivererIndex: 0,
        deliveryTimes: u,
        deliveryTimeIndex: [ 0, 0 ],
        deliveryTimeValue: {
            text: "即刻出发",
            value: null
        },
        address: null,
        carts: {},
        expiredTimes: o,
        expiredIndex: o.length - 1,
        expiredValue: 24,
        remarkValue: null,
        couponValidCount: 0,
        logistics: 0,
        logistics_full: 0,
        couponValue: null,
        popupType: "none",
        acceptAgreement: !0,
        expiredTime: null,
        model: l,
        cate_id: '',

        redpacket_money: 0,//红包
        redpacket_id: null,
        redpacket_info: null,

        wallet: 0,
        showModal: !1,
        zffs: 1,
        zfz: !1,
        zfwz: "微信支付",
        btntype: "btn_ok1",


    },
    onLoad: function(a){

        this.setData({
          cate_id: a.cate_id
        });

        s = a;
        var t = e.getStorage("homeData");
        if (t.model && t.model.SettingInfo) {
            var n = t.model.SettingInfo.MinFreightMoney;
            n != l.MinFreightMoney && (l.MinFreightMoney = n), this.setData({
                moneyPayable: n
            });
        }
        this.loadData(a);
    },
    onReady: function() {
        var a = this;
        e.callAuthorize(a, function() {
            p = !0;
        });
    },
    onShow: function() {
        i.un("commonInput"), e.getStorage("cart")[s.id] || wx.navigateBack(), this.setData({
            isBusy: !1
        });
    },
    onHide: function() {
        wx.hideLoading();
    },
    onUnload: function() {
        i.un("selectAddress");
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    onAuthorizeCallback: function(a) {
        var t = this;
        if (a.detail && a.detail.userInfo) {
            t.setData({
                dialogType: null
            });
          e.authorizeCallback(t, a.detail.userInfo,a.detail);
        }
    },


    loadData: function(n) {

        console.log('====loadData-n====',n);
        var shopId = n.id;
        var cateId = n.cate_id;

        var i = this, r = e.getStorage("cart")[n.id];

        console.log('====loadData-i====', i.data);

        var u = [];
        for (var c in r.Items) {
          var f = r.Items[c];
          u.push({
            CommodityId: f.Id,
            Money: f.Money,
            Price: f.Price,
            Quantity: f.Quantity
          });
        }

      console.log('====loadData-u====', u);
      console.log('====loadData-r====', r);

        i.setData({
            carts: r
        }), t.orderTakeoutPrepare({
            shopId: n.id,
            groupId: n.groupId || 0,
            items: u
        }, function(e) {

            console.log('====orderTakeoutPrepare====',e);
            
            //返回上一页
            if(e.error == 1) {
              wx.showModal({
                  title: "错误提示",
                  content: e.errorMsg,
                  showCancel: !1,
                  confirmText: "确定",
                  success: function (e) {
                    wx.navigateTo({
                      url: "../_/index?id=" + shopId + "&cate_id=" + cateId
                    });
                  },
                  fail: function (e) { 
                      //跳转错误
                  },
                  complete: function (e) { 
                      //跳转错误
                  }
                })
                
            }

            c = !0, l = e, u = i.getPickerTimes(0, l.ErrandTimeRangeBegin || "8:00", l.ErrandTimeRangeEnd || "22:00");
            var t = 0;
            if (l.MoneyPackage && r.Items) {
                var n = 0;
                for (var o in r.Items) n += r.Items[o].Quantity;
                t = a.decimal.multiply(l.MoneyPackage, n);
            }

            i.setData({
              wallet: l.wallet,
              redpacket_money: l.redpacket_money,//红包
              redpacket_id: l.redpacket_id,//红包ID
              redpacket_info: l.redpacket_info//红包说明
            }),
          

            l.AddressInfo && (l.AddressInfo.self = !0), i.setData({
                model: l,
                logistics: l.logistics,
                logistics_full: l.logistics_full,
                moneyPackage: t,
                address: l.AddressInfo,
                deliveryTimes: u,
                wallet: l.wallet,
                redpacket_money: l.redpacket_money,//红包
                redpacket_id: l.redpacket_id,//红包ID
                redpacket_info: l.redpacket_info//红包说明
            }), i.updateMoneyPayable();
        });
    },

    //刷新价格
    updateMoneyPayable: function() {
        var e = this;

        var orderType = e.data.errandOrderType.Value;

        if(orderType == 1){
          var t = l.MinFreightMoney;
        }else{
          var t = 0;
        }

        

        1 == e.data.delivererIndex && (t = a.decimal.add(t, e.data.model.DiffSpDevPrice)), 
        e.data.couponInfo && (t = a.decimal.subtract(t, e.data.couponInfo.Money));
        var n = 0;


        for (var i in e.data.carts.Items) {
            console.log('==r==');
            var r = e.data.carts.Items[i];
            console.log(r.Quantity);
            console.log(r.Price);
            console.log(t, r.Quantity * r.Price);
            t = a.decimal.add(t, r.Quantity * r.Price), n += r.Quantity;
        }

        //包装费+
        t = a.decimal.add(t, (l.MoneyPackage || 0) * n), 
        //红包抵扣-
        t = a.decimal.subtract(t, (l.redpacket_money || 0)), 


        console.log(l);
        console.log('===updateMoneyPayable==');

        var logistics_full = e.data.logistics_full;//满多少运费
        var logistics = e.data.logistics;//配送费

        var s = t - l.MinFreightMoney;



        if(logistics_full && s >= logistics_full){
          t = s;
          logistics = 0;
          logistics_full = logistics_full;
        }else{
          logistics = logistics;
          logistics_full = 0;
        }




        console.log('实际消费==' + s);
        console.log('运费==' + logistics);
        


        //转换小数点
        var fomatFloat = parseFloat(t);// 先转成parseFloat
        t = fomatFloat.toFixed(2);// toFixed四舍五入保留2位小数
        
        //t = t.toFixed(2);
        console.log('实际付款==' + t);

        e.setData({
            logistics: logistics,
            moneyPayable: t > 0 ? t : 0
        });
    },
    
    onAddressTap: function(e) {
        t.userSaveFormId({
            formId: e.detail.formId
        });
        var a = this, n = [ "user" ];
        i.un("selectAddress"), i.on("selectAddress", function(e) {
            a.setData({
                address: e
            });
        }), wx.navigateTo({
            url: "../../common/location/select?index=1&tabs=" + n.join(",")
        });
    },
    onDelivererChange: function(e) {
        var t = this, n = parseInt(e.detail.value);
        4 == t.data.errandType.Value && (n = 1, a.toast("目前代寄物品仅限平台专员配送")), t.setData({
            delivererIndex: n
        }), t.updateMoneyPayable();
    },
    getPickerTimes: function(e, t, n) {
        var i = [ [], [] ];
        t = t || "00:00", n = n || "23:30", i[0].push({
            text: "今天",
            value: 0
        });
        for (y = 0; y < l.ErrandTimeRangeDays - 1; ++y) {
            var o = 0 == y ? "明天" : 1 == y ? "后天" : "";
            i[0].push({
                text: (o.length ? o + "（" : o) + a.formatDate(a.addDate(r, y + 1)) + (o.length ? "）" : o),
                value: y + 1
            });
        }
        var d = null;
        if (e) {
            var s = a.addDate(r, 1), u = parseInt(t.split(":")[0]), m = parseInt(t.split(":")[1]);
            d = new Date(s.getFullYear(), s.getMonth(), s.getDate(), u, m, 0);
        } else {
            d = a.addDate(r, 30, "m"), i[1].push({
                text: "即刻出发",
                value: null
            });
            var c = 30 - d.getMinutes() % 30;
            d = a.addDate(a.addDate(d, c, "m"), -d.getSeconds(), "s");
        }
        for (var p = parseInt(n.split(":")[0]), f = parseInt(n.split(":")[1]), v = new Date(d.getFullYear(), d.getMonth(), d.getDate(), p, f, 0), g = d, y = 0; g < v; y++) g = a.addDate(d, 30 * y, "m"), 
        i[1].push({
            text: a.formatDate(g, "minute", "time"),
            value: g
        });
        return i;
    },
    onDeliveryTimeChange: function(e) {
        var a = this, t = e.detail.value[0], n = e.detail.value[1];
        u[0][t], u[1][e.detail.value[1]];
        a.setData({
            deliveryTimeValue: {
                text: (0 == t && 0 == n ? "" : u[0][t].text + " ") + u[1][n].text,
                value: u[1][n].value
            }
        });
    },
    onDeliveryTimeColumnChange: function(e) {
        if (0 == e.detail.column) {
            var a = this, t = a.getPickerTimes(e.detail.value, a.data.model.ErrandTimeRangeBegin || "8:00", a.data.model.ErrandTimeRangeEnd || "22:00");
            u.splice(0, u.length), u.push(t[0]), u.push(t[1]), a.setData({
                deliveryTimes: u
            });
        }
    },
    onExpiredTap: function() {
        this.setData({
            popupType: "expired"
        });
    },
    onPopupCancel: function() {
        this.setData({
            popupType: "none"
        });
    },
    onPopupConfirm: function() {
        var e = this, a = e.data.popupType;
        setTimeout(function() {
            e.setData({
                popupType: "none"
            }), setTimeout(function() {
                "expired" == a ? e.setData({
                    expiredValue: o[e.data.expiredIndex]
                }) : "remark" == a && e.setData({
                    remarkValue: m
                });
            }, 200);
        }, 50);
    },
    onExpiredSliderChange: function(e) {
        this.setData({
            expiredIndex: e.detail.value
        });
    },


    onLimitTap: function(e) {
        var a = this;
        wx.showActionSheet({
            itemList: n.getNames(n.ErrandGenderLimits),
            success: function(e) {

                console.log(n.ErrandGenderLimits[e.tapIndex]);

                a.setData({
                    errandGenderLimit: n.ErrandGenderLimits[e.tapIndex]
                });
            },
            fail: function(e) {
                console.log(e.errMsg);
            }
        });
    },

    onOrderTypeTap: function (e) {
      var a = this;
      wx.showActionSheet({
        itemList: n.getNames(n.ErrandOrderTypes),
        success: function (e) {

          var ordertype = n.ErrandOrderTypes[e.tapIndex];
          console.log(ordertype.value);
          

          a.setData({
            errandOrderType: n.ErrandOrderTypes[e.tapIndex]
          });

          //刷新价格
          a.updateMoneyPayable();

        },
        fail: function (e) {
          console.log(e.errMsg);
        }
      });
    },


    onRemarkTap: function() {
        var e = this;
        i.on("commonInput", function(a) {
            e.setData({
                remarkValue: a
            });
        }), wx.navigateTo({
            url: "../../common/content/input?title=备注&placeholder=口味、偏好等要求&value=" + (e.data.remarkValue || "")
        });
    },
    onRemarkContentChanged: function(e) {
        m = e.detail.value;
    },
    onCouponTap: function() {
        var e = this;
        i.un("CouponSelect"), i.one("CouponSelect", function(t) {
            var n = 0;
            d.splice(0, d.length);
            for (var i in t) n = a.decimal.add(n, t[i].Money), d.push(t[i]);
            e.setData({
                couponValue: n
            });
        }), wx.navigateTo({
            //url: "../../mine/coupon/index?list=true",
            url: "../../mine/coupon/index?list=true&money=" + e.data.freightValue,
        }), i.fire("CouponLoad", e.data.model.Coupons);
    },


    //提交订单 
    onForm: function (i) {

      var that = this;
      console.log('==onForm提交订单==', that.data);
      console.log(e);

      var s = {
        OrderAddressList: [{
          AddressId: that.data.address.Id,
          Address: that.data.address.Linkman + "(" + that.data.address.Phone + ")",
          Description: that.data.address.Address,
          Longitude: that.data.address.Longitude,
          Latitude: that.data.address.Latitude,
          IsUserAddress: !!that.data.address.self,
          IsOutSide: that.data.address.IsOutSide
        }],
      };


      console.log('==s.OrderAddressList==', s.OrderAddressList);
  

      if (s.OrderAddressList[0].AddressId == null || s.OrderAddressList.length == 0) {
        a.toast("请选择跑腿地址");
        return false;
      }



      if (e.globalData.userInfo == '') {
        e.callAuthorize(d, function () {
          c = !0;
        });
        return false;
      }
      if (e.globalData.userInfo.Mobile == '') {
        wx.navigateTo({
          url: "../../mine/info/bindmobile"
        })
        return false;
      }
      this.setData({
        showModal: !0
      });

    },


    onFormSubmit: function(r) {


      var that = this;
      that.setData({
        showModal: !1
      }), wx.showLoading({
        title: "正在提交支付中...",
        mark: !0
      });


      if ("微信支付" == that.data.zfwz) var pay = 1; else if ("余额支付" == that.data.zfwz) pay = 2;
      console.log('---onFormSubmit---' + that.zfwz);
      console.log('---支付方式---' + pay);


        var o = this;
        if (p) if (t.userSaveFormId({
            formId: r.detail.formId
        }), e.globalData.userInfo && e.globalData.userInfo.Mobile) {
            var u = [], m = 0;
            for (var c in o.data.carts.Items) {
                var f = o.data.carts.Items[c];
                u.push({
                    CommodityId: f.Id,
                    Money: f.Money,
                    Price: f.Price,
                    Quantity: f.Quantity
                }), m = a.decimal.add(m, a.decimal.multiply(f.Price, f.Quantity));
            }
            console.log(o.data);

            var v = {
                cate_id: o.data.cate_id,
                Type: n.OrderTypes[0].Value,
                Money: m,
                MoneyFreight: l.MinFreightMoney,
                logistics:o.data.logistics,
                logistics_full:o.data.logistics_full,

                redpacket_money: o.data.redpacket_money,//红包
                redpacket_id: o.data.redpacket_id,//红包ID
                redpacket_info: o.data.redpacket_info,//红包说明

                LimitDeliverer: o.data.delivererIndex + 1,
                LimitDelivererGender: o.data.errandGenderLimit.Value,
                orderType: o.data.errandOrderType.Value,
                ShopId: s.id,
                GroupId: s.groupId,
                Items: u,
                OrderAddressList: [ {
                    AddressId: o.data.address.Id,
                    Address: o.data.address.Linkman + "(" + o.data.address.Phone + ")",
                    Description: o.data.address.Address,
                    Longitude: o.data.address.Longitude,
                    Latitude: o.data.address.Latitude,
                    IsUserAddress: !!o.data.address.self,
                    IsOutSide: o.data.address.IsOutSide
                } ],
                CouponList: [],
                ExpectTime: o.data.deliveryTimeValue && o.data.deliveryTimeValue.value,
                ExpiredMinutes: 60 * o.data.expiredValue,
                MoneyPayment: o.data.moneyPayable,
                Remark: o.data.remarkValue || ""
            };

            console.log('=提交订单=');
            console.log(v);

            for (var c in d) v.CouponIds.push(d[c].Id);
            if (v.OrderAddressList.length) {
                var g = function() {
                    var a = e.getStorage("cart");
                    delete a[s.id], a.save();
                };
                o.setData({
                    isBusy: !0
                }), t.orderCommit(v, function(e) {

                  //余额支付
                  if (pay == 2) {
                    that.yuePay(e.log_id, e.running_id, 'running');
                  } else {
                    //微信支付
                   

                    t.orderPayment({
                        id: e.running_id
                    }, function(a) {
                        g(), !0 === a ? (i.fire("refreshHomeOrders"), wx.navigateTo({
                            url: "../../order/_/info?createorder=true&id=" + e.running_id
                        })) : !1 === a ? wx.navigateTo({
                            url: "../../order/_/paycomplete?id=" + e.running_id
                        }) : wx.navigateTo({
                              url: "../../order/_/info?createorder=true&id=" + e.running_id
                        });
                    }, function() {
                        g(), wx.navigateTo({
                          url: "../../order/_/info?createorder=true&id=" + e.running_id
                        });
                    });

                  }
                    
                }, 
                
                  
                function() {
                    o.setData({
                        isBusy: !1
                    });
                });
            } else a.toast("请选择配送地址");
        } else wx.navigateTo({
            url: "../../mine/info/bindmobile"
        }); else e.callAuthorize(o, function() {
            p = !0;
        });
    },
    onServiceContractLink: function() {
        var e = this;
        wx.navigateTo({
            url: "../../common/content/web?url=" + encodeURIComponent("/wap/news/info?id=" + e.data.model.ErrandServiceArticleId)
        });
    },

    radioChange: function (e) {
      console.log("radio1发生change事件，携带value值为：", e.detail.value), "wxzf" == e.detail.value && this.setData({
        zffs: 1,
        zfwz: "微信支付",
        btntype: "btn_ok1"
      }), "yezf" == e.detail.value && this.setData({
        zffs: 2,
        zfwz: "余额支付",
        btntype: "btn_ok2"
      }), "jfzf" == e.detail.value && this.setData({
        zffs: 3,
        zfwz: "积分支付",
        btntype: "btn_ok3"
      }), "hdfk" == e.detail.value && this.setData({
        zffs: 4,
        zfwz: "货到付款",
        btntype: "btn_ok4"
      });
    },

    
    yuePay: function (e, r, p) {
      console.log("调用余额支付");
      this.data.openid;

      var wallet = this.data.wallet;
      var moneyPayable = this.data.moneyPayable;

      console.log("支付金额", moneyPayable);
      console.log("您的余额", wallet);

      if (moneyPayable > wallet) {
        a.toast("支付金额" + moneyPayable + "大于您的余额" + wallet + "请更换其他支付方式");
        this.setData({
          isBusy: !1
        });
        return false;
      }

      t.orderyuePay({
        log_id: e,
        type: p
      }, function (a) {

        console.log(a), 0 == a.code ? (wx.hideLoading(), wx.showToast({
          title: a.message
        }), setTimeout(function () {
          wx.navigateTo({
            url: "../../order/_/paycomplete?id=" + r
          });
        }, 1500)) : (wx.hideLoading(), wx.showToast({
          title: a.message
        }), setTimeout(function () {
          wx.navigateBack({
            delta: 2
          });
        }, 1500));
      }
      )
    },



});