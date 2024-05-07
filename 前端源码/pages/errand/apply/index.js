var e = getApp(), a = e.require("utils/util.js"), t = e.require("utils/api.js"), n = e.require("utils/enums.js"), r = e.require("utils/onfire.js"), i = null, d = a.makeArray(1, 24), s = [], o = [], u = null, l = [ [], [] ], p = {
    MinFreightMoney: 1
}, f = n.ErrandTypes[3], h = !1, c = !1;


Page({
    data: {
        isBusy: !1,
        dialogType: null,
        errandType: f,
        errandGenderLimit: n.ErrandGenderLimits[0],
        errandWeights: n.ErrandWeights,
        weightIndex: 0,
        weightValue: null,
        freights: [],
        freightIndex: 0,
        freightValue: null,
        feeInputFocus: !1,
        feeInputValue: 0,
        moneyValue: null,
        moneyInputValue: null,
        moneyInputFocus: !1,
        moneyPayable: p.MinFreightMoney,
        deliveryTimes: l,
        deliveryTimeIndex: [ 0, 0 ],
        deliveryTimeValue: {
            text: "即刻出发",
            value: null
        },
        expiredTimes: d,
        expiredIndex: d.length - 1,
        expiredValue: 24,

        redpacket_money: 0,//红包
        redpacket_id: null,
        redpacket_info: null,

        attr_id: 0,//规格ID
        attr_name: null,
        attr_money: 0,

        remarkValue: null,
        remarkPlaceHolder: null,
        remarkInputFocus: !1,
        addresses: s,
        addressAdd: !1,
        couponValidCount: 0,
        couponValue: 0,
        download_coupon_id	: 0,
        popupType: "none",
        acceptAgreement: !0,
        expiredTime: null,
        isSpecial: !1,
        model: p,
        cate_id : '',
        file:'',
        fileName:'请选择文件',

        wallet:0,
        showModal: !1,
        zffs: 1,
        zfz: !1,
        zfwz: "微信支付",
        btntype: "btn_ok1",
        notifyFlag2:0,


    },

    onLoad: function(a) {
        this.setData({
          cate_id: a.cate_id
        });
        i = new Date();
        var t = e.getStorage("homeData");
        if (t.model && t.model.SettingInfo) {
            var n = t.model.SettingInfo, r = n.MinFreightMoney;
            r != p.MinFreightMoney && (p.MinFreightMoney = r);
            var d = !1;
            a.type && (d = (n.NormalDeliveryAllowOrderTypes | 2 * a.type) != n.NormalDeliveryAllowOrderTypes), 
            this.setData({
                moneyPayable: r,
                isSpecial: d
            });
        }
        u = a, a.remark && this.setData({
            remarkPlaceHolder: a.remark
        }), this.loadData(a),this.onErrandTypeChange(a);
    },




    onReady: function() {
        var a = this;
        e.callAuthorize(a, function() {
            c = !0, a.setData({
                remarkInputFocus: h && c && !u.type
            });
        });
    },


    onShow: function() {
        console.log('=onShow=');
        var e = this;
        //更新运费
        console.log('=更新运费=');
        e.updateMoneyPayable();
        this.setData({
            isBusy: !1
        });
    },



    onHide: function() {
        wx.hideLoading();
    },


    onUnload: function() {
        r.un("selectAddress");
    },


    onAuthorizeCallback: function(a) {
        var t = this;
        if (a.detail && a.detail.userInfo) {
            t.setData({
                dialogType: null
            });
          e.authorizeCallback(t, a.detail.userInfo, a.detail);
        }
    },

    //发送订阅消息
    switchChangeMsg: function (t) {
      console.log('==switchChangeMsg==', this.data.model.tmplIds);
      var tmp = this.data.model.tmplIds;

      wx.requestSubscribeMessage({
        tmplIds: [''+tmp.tid1+'',''+tmp.tid2+'', ''+tmp.tid3+''], // 此处可填写多个模板 ID，但低版本微信不兼容只能授权一个
        success(res) {
          console.log('已授权接收订阅消息')
        },
        fail(res) {
          console.log(res)
        }
      })
      var e = this;
      e.setData({
        notifyFlag2: !0,
      });
    },



    loadData: function() {

        console.log('====loadData====');
        var e = this, n = a.makeArray(1, 6);
        t.orderErrandPrepare({
          cate_id: e.options.cate_id
        }, function(a){

            console.log('==a.cates==');
            console.log(e.options.cate_id);
            console.log('==a==');
            console.log(a);

          h = !0, p = a, l = e.getPickerTimes(p.ErrandTimeRangeDays || 0, p.ErrandTimeRangeBegin || "8:00", p.ErrandTimeRangeEnd || "22:00"), 

          console.log('=getPickerTimes=llll==');
          console.log(l);

            e.setData({
                model: p,
                freightValue: p.MinFreightMoney,//修复bug
                freights: n,
                remarkInputFocus: h && c && !u.type,
                deliveryTimes: l,
                errandType: a.cates,
                wallet: a.wallet,
                redpacket_money: a.redpacket_money,//红包
                redpacket_id: a.redpacket_id,//红包ID
                redpacket_info: a.redpacket_info,//红包说明
                attr_id: a.attr_id,//规格ID
                attr_name: a.attr_name,//规格名称
                attr_money: a.attr_money,//规格价格

            }), e.updateMoneyPayable();
        });
    },

    updateMoneyPayable: function() {

        var e = this, t = e.data.freightValue || p.MinFreightMoney;

        console.log('==更新价格updateMoneyPayable-t==',t);
        console.log('==更新价格updateMoneyPayable-e.data==', e.data);

        var fomatFloat = parseFloat(t);// 先转成parseFloat
        var t = fomatFloat.toFixed(2);// toFixed四舍五入保留2位小数

        


        e.data.couponValue && (t = a.decimal.subtract(t,e.data.couponValue)), //减去优惠券费用
        e.data.redpacket_money && (t = a.decimal.subtract(t, e.data.redpacket_money)), //减去红包费用
        e.data.attr_money && (t = a.decimal.add(t, e.data.attr_money)), //+规格费用

        console.log('==updateMoneyPayable-规格费用==', e.data.attr_money);


        e.setData({
            moneyPayable: t > 0 ? t : 0
        });
    },





    onErrandTypeChange: function(e) {

        var t = this, r = u.type || f.Value, i = a.indexOfArray(n.ErrandTypes, function(e) {
            return r == e.Value;
        }), d = !1;


        console.log('==onErrandTypeChange-n=');
        console.log(n);
        console.log('==eeeeeee==');
        console.log(e);


        if (s.splice(0, s.length), i == n.ErrandTypes.length - 1) d = !0, s.push({
            self: null,
          text: e.end ? e.end : "送去哪里？",
            address: null
        }); else switch (i) {
          case 0:
            s.push({
              self: !1,
              text: e.start ? e.start : "在哪里",
              address: null
            }), s.push({
              self: !0,
              text: e.end ? e.end : "到哪里去",
              address: p.AddressInfo
            });
            break;

        
          default:
            s.push({
              self: !1,
              text: e.start ? e.start : "在哪里",
              address: p.AddressInfo
            }), s.push({
              self: !0,
              text: e.end ? e.end : "到哪里去",
              address: null
            });
        }

        console.log('=======n.ErrandTypes[i]==========');
        console.log(n.ErrandTypes[i]);

        t.setData({
            addressAdd: d,
            addresses: s,
            errandType: n.ErrandTypes[i]
        });
    },


    onTypeTap: function(e) {
        console.log('userSaveFormId');
        t.userSaveFormId({
            formId: e.detail.formId
        });
        var a = this;
        wx.showActionSheet({
            itemList: n.getNames(n.ErrandTypes),
            success: function(e) {
                a.onErrandTypeChange({
                    type: n.ErrandTypes[e.tapIndex].Value
                });
            }
        });
    },
    onRemarkConfirm: function(e) {
        this.setData({
            remarkValue: e.detail.value
        });
    },
    onMoneyTap: function() {
        var e = this;
        setTimeout(function() {
            e.setData({
                popupType: "money",
                moneyInputValue: e.data.moneyValue || 0
            }), setTimeout(function() {
                e.setData({
                    moneyInputFocus: !0
                });
            }, 300);
        }, 300);
    },
    onMoneyInputBlur: function(e) {
        this.setData({
            moneyInputValue: e.detail.value && e.detail.value.length ? e.detail.value : 0
        });
    },

    onAddressTap: function(e) {
      
      console.log('onAddressTap');
      console.log(e);

        t.userSaveFormId({
            formId: e.detail.formId
        });

        var a = this, i = e.currentTarget.dataset.self, d = e.currentTarget.dataset.index, o = 0, u = [];
        !0 === i && (o = 1, u.push("user")), !1 === i && u.push("common,nearest"), r.un("selectAddress"), 
        r.on("selectAddress", function(e) {
            s[d].address = e, d == s.length - 1 && a.data.errandType.Value == n.ErrandTypes[n.ErrandTypes.length - 1].Value && s.push({
                self: null,
                text: "然后到哪里去",
                address: null
            }), a.setData({
                addresses: s
            });
        }), wx.navigateTo({
            url: "../../common/location/select?index=" + o + "&tabs=" + u.join(",")
        });
    },

    onAddressAddTap: function(e) {
        t.userSaveFormId({
          formId: e.detail.formId
        });
        var a = this, t = e.currentTarget.dataset.index;
        void 0 !== t && (t == s.length - 1 ? s.push({
            self: !1,
            text: "然后到哪里去",
            address: null
        }) : (0 == t && (s[t + 1].text = s[t].text), s.splice(t, 1)), a.setData({
            addresses: s
        }));
    },

    getPickerTimes: function(e, t, n) {

        console.log('==getPickerTimes函数==');
     

        var r = [ [], [] ];
        var star = 0;

        t = t || "00:00", n = n || "23:30", r[0].push({
            text: "今天",
            value: 0
        });


        for (v = 0; v < p.ErrandTimeRangeDays - 1; ++v) {
            var d = 0 == v ? "明天" : 1 == v ? "后天" : "";
            r[0].push({
                text: (d.length ? d + "（" : d) + a.formatDate(a.addDate(i, v + 1)) + (d.length ? "）" : d),
                value: v + 1
            });
        }
        var s = null;

        if (e) {
            var o = a.addDate(i, 1), l = parseInt(t.split(":")[0]), f = parseInt(t.split(":")[1]);
            s = new Date(o.getFullYear(), o.getMonth(), o.getDate(), l, f, 0);

            console.log('new Date');
            console.log(s);

            star = 1;
           
        } else {
            s = a.addDate(i, 30 * (4 == u.type ? 1 : 2), "m"), r[1].push({
                text: "即刻出发",
                value: null
            });
            var h = 30 - s.getMinutes() % 30;
            s = a.addDate(a.addDate(s, h, "m"), -s.getSeconds(), "s");
            star = 0;

        }
        for (var c = parseInt(n.split(":")[0]), g = parseInt(n.split(":")[1]), m = new Date(s.getFullYear(), s.getMonth(), s.getDate(), c, g, 0), y = s, v = 0; y < m; v++) y = a.addDate(s, 30 * v, "m"), 

        r[1].push({
            text: a.formatDate(y, "minute", "time"),
            value: y
        });

        if(star){
          //更新出发时间
          this.setData({
            deliveryTimeValue: {
              text: "即刻出发",
              value: null
            }
          });
        }
        return r;
    },






    onDeliveryTimeChange: function(e) {
        var a = this, t = e.detail.value[0], n = e.detail.value[1];
        l[0][t], l[1][e.detail.value[1]];
        a.setData({
            deliveryTimeValue: {
                text: (0 == t && 0 == n ? "" : l[0][t].text + " ") + l[1][n].text,
                value: l[1][n].value
            }
        });
    },

    onDeliveryTimeColumnChange: function(e) {

      console.log('==onDeliveryTimeColumnChange==');
      console.log('==e.detail.value==');
      console.log(e.detail.value);

        if (0 == e.detail.column) {
            var a = this, t = a.getPickerTimes(e.detail.value, a.data.model.ErrandTimeRangeBegin || "8:00", a.data.model.ErrandTimeRangeEnd || "22:00");
            l.splice(0, l.length), l.push(t[0]), l.push(t[1]), a.setData({
                deliveryTimes: l
            });
        }
    },


    onWeightTap: function() {
        var e = this;
        e.setData({
            popupType: "weight",
            weightIndex: e.data.weightValue ? a.indexOfArray(n.ErrandWeights, function(a) {
                return a.Value == e.data.weightValue;
            }) : 0
        });
    },


    onExpiredTap: function() {
        var e = this;
        e.setData({
            popupType: "expired",
            weightIndex: e.data.weightValue ? a.indexOfArray(n.ErrandWeights, function(a) {
                return a.Value == e.data.weightValue;
            }) : 0
        });
    },


    onFreightTap: function() {
        var e = this;
        e.setData({
            popupType: "freight",
            freightIndex: e.data.freightValue ? e.data.freights.indexOf(e.data.freightValue) : 0,
            feeInputFocus: !(!e.data.freightValue || !e.data.freights.indexOf(e.data.freightValue)),
            feeInputValue: e.data.freightValue && e.data.freights.indexOf(e.data.freightValue) < 0 ? e.data.freightValue : 0
        });
    },

   //更新规格费用
   onAttrTap: function (e) {
     console.log('==onAttrTap-e==',e);
      var t = this;
      t.setData({
        attr_id: e.target.dataset.id,
        attr_money: e.target.dataset.money,
        attr_name: e.target.dataset.name
      });
      t.updateMoneyPayable();
    },


    onPopupCancel: function() {
        this.setData({
            popupType: "none"
        });
    },


    onPopupConfirm: function() {
        var e = this, t = e.data.popupType;

        console.log('==onPopupConfirm==',t);

        setTimeout(function() {
            if ("money" == t) {
                if ("" == e.data.moneyInputValue) return void a.toast("请输入金额");
            } else if ("tip" == t && e.data.freightIndex < 0 && !e.data.feeInputValue) return void a.toast("请输入小费金额");


          console.log('e.data.weightIndex', n.ErrandWeights);
          console.log(e.data.weightIndex);

          if (e.data.weightIndex == 5){
            var f = (e.data.weightIndex) -1;
            var weightValue = n.ErrandWeights[f].Value;
          }else{
            var weightValue = n.ErrandWeights[e.data.weightIndex].Value;
          }

          console.log('==e.data.freightIndex==', e.data.freightIndex);
          console.log('==e.data.feeInputValue==', e.data.feeInputValue)
          console.log('==p.MinFreightMoney==', p.MinFreightMoney)
          console.log('==e.data.freights[e.data.freightIndex]==', e.data.freights[e.data.freightIndex])

        
          //解决小数点不对的bug
          var fomatFloat = parseFloat(e.data.feeInputValue);// 先转成parseFloat
          e.data.feeInputValue = fomatFloat.toFixed(2);// toFixed四舍五入保留2位小数


            e.setData({
                popupType: "none",
                feeInputFocus: !1,
                moneyInputFocus: !1
            }), setTimeout(function() {
                "money" == t ? e.setData({
                    moneyValue: parseInt(e.data.moneyInputValue)
                }) : "weight" == t ? e.setData({
                    weightValue: weightValue
                }) : "expired" == t ? e.setData({
                    expiredValue: d[e.data.expiredIndex]
                }) : "freight" == t && (e.setData({
                    freightValue: e.data.freightIndex < 0 ? e.data.feeInputValue <= p.MinFreightMoney ? p.MinFreightMoney :(e.data.feeInputValue) : e.data.freights[e.data.freightIndex]
                }), 
                e.updateMoneyPayable());
            }, 200);
        }, 50);
    },



    onWeightSliderChange: function(e) {
        this.setData({
            weightIndex: e.detail.value
        });
    },
    onExpiredSliderChange: function(e) {
        this.setData({
            expiredIndex: e.detail.value
        });
    },


    onFeeItemTap: function(e) {
        var a = this, t = e.currentTarget.dataset.index;
        console.log('onFeeItemTap',t);
        t >= 0 && a.data.freights[t] < p.MinFreightMoney || a.setData({
            freightIndex: t,
            feeInputFocus: t < 0
        });
    },


    onFeeInputBlur: function(e) {
        var a = this, t = e.detail.value;

        console.log('freights', a.data.freights);
        console.log('输入金额',t);
        console.log('输入金额a.data.freights.indexOf(t)', a.data.freights.indexOf(t));
        console.log('最低金额', p.MinFreightMoney);
      

        t && t.length && ((t = t) < p.MinFreightMoney && (t = p.MinFreightMoney), 

        console.log('实际金额', t),

        a.setData({
            feeInputFocus: a.data.freights.indexOf(t) > 0,
            feeInputValue: a.data.freights.indexOf(t) < 0 ? t : 0
        }));

        console.log('实际data', this.data);
    },


    onLimitTap: function(e) {
        var a = this;
        wx.showActionSheet({
            itemList: n.getNames(n.ErrandGenderLimits),
            success: function(e) {
                a.setData({
                    errandGenderLimit: n.ErrandGenderLimits[e.tapIndex]
                });
            },
            fail: function(e) {
                console.log(e.errMsg);
            }
        });
    },
    onCouponTap: function() {
        var e = this;
        r.un("CouponSelect"), r.one("CouponSelect", function(t) {
            var n = 0;
            o.splice(0, o.length);

            for (var r in t) n = a.decimal.add(n, t[r].Money), o.push(t[r]);
            
            var yunfei = e.data.freightValue;

            console.log('=====onCouponTap===');
            console.log(t[r].download_id);

            //运费
            if(yunfei > n){
              var couponValue = n
            }else{
              var couponValue = 0
            }

            e.setData({
                couponValue: couponValue,
                download_coupon_id: t[r].download_id
            });
        }), 

        console.log(e.data);
        console.log(e.data.freightValue);


        wx.navigateTo({
          url: "../../mine/coupon/index?list=true&money=" + e.data.freightValue,
        }), r.fire("CouponLoad", e.data.model.Coupons);
    },

    //提交订单 
    onForm: function (i) {
      var that = this;
      console.log('==onForm提交订单==',that.data);
      console.log(e);

      var s = {
        OrderAddressList: [],
        Remark: that.data.remarkValue || ""
      };

      for (var u in that.data.addresses) {
        var l = that.data.addresses[u];
        l.address && s.OrderAddressList.push({
          AddressId: l.address.Id || 0,
          IsUserAddress: !!l.self,
          Address: !l.self && l.address.Name ? l.address.Name : l.address.Linkman + "(" + l.address.Phone + ")",
          Description: l.address.Address,
          Longitude: l.address.Longitude,
          Latitude: l.address.Latitude,
          IsOutSide: l.address.IsOutSide
        });
      }

      if (s.Remark.length == 0){
        a.toast("请填写跑腿描述");
        return false;
      }
      if (s.OrderAddressList.length == 0) {
        a.toast("请选择跑腿地址");
        return false;
      }
      if (e.globalData.userInfo == '') {
        e.callAuthorize(d, function (){
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

    onFormSubmit: function(i) {

        var that = this;
        that.setData({
          showModal: !1
        }), wx.showLoading({
          title: "正在提交支付中...",
          mark: !0
        });


        if ("微信支付" == that.data.zfwz) var pay = 1; else if ("余额支付" == that.data.zfwz) pay = 2;


        console.log('---onFormSubmit---' +that.zfwz);
        console.log('---支付方式---'+pay);

        var d = this;
        d.updateMoneyPayable();//先更新运费


        console.log(d.data.errandType.Value);

        if (c) if (t.userSaveFormId({
            formId: i.detail.formId
        }), e.globalData.userInfo && e.globalData.userInfo.Mobile) {


            var s = {
                file: d.data.file,//文件
                cate_id: d.data.cate_id,
                Type: n.OrderTypes[1].Value,
                Stype: d.data.errandType.Value,
                Money: d.data.moneyValue,
                MoneyFreight: d.data.freightValue || p.MinFreightMoney,
                LimitDelivererGender: d.data.errandGenderLimit.Value,
                OrderAddressList: [],
                CouponList: [],
                coupon_price: d.data.couponValue,
                download_coupon_id: d.data.download_coupon_id,
                redpacket_money: d.data.redpacket_money,//红包
                redpacket_id: d.data.redpacket_id,//红包ID
                redpacket_info: d.data.redpacket_info,//红包说明

                attr_id: d.data.attr_id,//规格ID
                attr_name: d.data.attr_name,//规格名称
                attr_money: d.data.attr_money,//规格价格

                ExpectTime: d.data.deliveryTimeValue && d.data.deliveryTimeValue.value,
                ExpiredMinutes: 60 * d.data.expiredValue,
                Weight: d.data.weightValue,
                MoneyPayment: d.data.moneyPayable,
                Remark: d.data.remarkValue || ""
            };
            for (var u in d.data.addresses) {
                var l = d.data.addresses[u];
                l.address && s.OrderAddressList.push({
                    AddressId: l.address.Id || 0,
                    IsUserAddress: !!l.self,
                    Address: !l.self && l.address.Name ? l.address.Name : l.address.Linkman + "(" + l.address.Phone + ")",
                    Description: l.address.Address,
                    Longitude: l.address.Longitude,
                    Latitude: l.address.Latitude,
                    IsOutSide: l.address.IsOutSide
                });
            }

          console.log('==s.CouponIds==')
          console.log(s.CouponIds)

         

            s.Remark.length ? s.OrderAddressList.length ? (d.setData({
                isBusy: !0
            }), t.orderCommit(s, function(e) {
                
                console.log('==t.orderCommit==')
                console.log(e)

                //余额支付
                if(pay == 2){
                  that.yuePay(e.log_id,e.running_id,'running');
                }else{
                  //微信支付
                  t.orderPayment({
                    id: e.running_id
                  }, function (a) {
                    !0 === a ? (r.fire("refreshHomeOrders"), wx.navigateTo({
                        url: "../../order/_/info?createorder=true&id=" + e.running_id
                    })) : !1 === a ? wx.navigateTo({
                        url: "../../order/_/paycomplete?id=" + e.running_id
                    }) : wx.navigateTo({
                          url: "../../order/_/info?createorder=true&id=" + e.running_id
                    });
                  }, function () {
                    wx.navigateTo({
                      url: "../../order/_/info?createorder=true&id=" + e.running_id
                    });
                  });
                }
            }, function() {
                d.setData({
                    isBusy: !1
                });
            })) : a.toast("请选择跑腿地址") : a.toast("请详细描述跑腿事宜的要求");
        } else wx.navigateTo({
            url: "../../mine/info/bindmobile"
        }); else e.callAuthorize(d, function() {
            c = !0;
        });
    },


    yuePay: function (e,r,p) {
      console.log("调用余额支付");
      this.data.openid;

      var wallet = this.data.wallet;
      var moneyPayable = this.data.moneyPayable;

      console.log("支付金额", moneyPayable);
      console.log("您的余额", wallet);

      if(moneyPayable > wallet){
        a.toast("支付金额" + moneyPayable + "大于您的余额" + wallet + "请更换其他支付方式");
        this.setData({
          isBusy: !1
        });
        return false;
      }



      t.orderyuePay({
        log_id: e,
        type:p
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


    



    onExpressFeeLink: function() {
        var e = this;
        wx.navigateTo({
          url: "../../common/content/web?url=" + encodeURIComponent("/wap/news/detail?article_id=" + e.data.model.ExpressCostArticleId)
        });
    },



    onServiceContractLink: function() {
        var e = this;
      console.log(e.data.model);
        wx.navigateTo({
          url: "../../common/content/web?url=" + encodeURIComponent("/wap/news/detail?article_id=" + e.data.model.ErrandServiceArticleId)
        });
    },

    onFileLink: function () {
      var e = this;
      console.log(e.data.model);
      wx.navigateTo({
        url: "../../common/content/web?url=" + encodeURIComponent("/wap/news/files")
      });
    },


    //关闭按钮
    onAuthorizeClose: function () {
      console.log('-onAuthorizeClose-')
      var e = this;
      e.setData({
        dialogType: null
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



});