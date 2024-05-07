var e = getApp(), t = e.require("utils/onfire.js"), n = e.require("utils/util.js"), a = e.require("utils/api.js"), o = e.require("utils/enums.js"), i = null, u = {}, r = null, s = -1, d = null, l = null, f = {
    Logs: []
};

Page({
    data: {
        errandWeights: o.ErrandWeights,
        giveupCauses: r,
        timeRemain: "",
        submitAction: null,
        orderStatus: null,
        model: f,
        modalType: "none",
        popupType: "none",
        dialogType: "none",

        fangqiType: "none",
        popupType2: "none",

        dialogMessage: null,
        fees: i,
        feeIndex: 0,
        feeInputFocus: !1,
        feeInputValue: 0,
        giveupCausesIndex: s
    },
    onLoad: function(e) {
        var a = this;
        i = n.makeArray(1, 6), r = [ "下单人主动要求取消", "订单描述与实际情况不符", "商家已打烊", "路途太远或者任务繁重，赏金太少", "临时有事，没时间继续跑腿" ], 
        a.setData({
            giveupCauses: r,
            fees: i
        }), d = null, u = e, a.loadData(e), t.on("orderReresh", function() {
            a.loadData(), t.un("orderReresh");
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {
        l && clearInterval(l), u.createorder && wx.navigateBack({
            delta: 10
        });
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function () {
      e.onAppShareAppMessage('');
    },
    loadData: function(e) {
        var i = this;
        if (e && e.status) {
            f.Status = e.status;
            var r = n.indexOfArray(o.OrderStatus, function(t) {
                return t.Value == e.status;
            });
            i.setData({
                model: f,
                timeRemain: "--:--",
                orderStatus: r >= 0 ? o.OrderStatus[r] : null
            });
        }
        a.orderInfo({
            id: e && e.id || u.id
        }, function(a) {

            console.log('a.orderInfo-a',a), 

            f = a, e && a.Status != e.status && t.fire("refreshHomeOrders");
            var u = n.indexOfArray(o.OrderStatus, function(e) {
                return e.Value == a.Status;
            });
            if (i.setData({
                model: f,
                orderStatus: u >= 0 ? o.OrderStatus[u] : null
            }), f.Status <= 1 && f.PaymentLimitSeconds > 0) {
                var r = 0;
                l = setInterval(function() {
                    var e = f.PaymentLimitSeconds - r, t = parseInt(e / 60), n = parseInt(e % 60);
                    i.setData({
                        timeRemain: e > 0 ? (t >= 10 ? t : "0" + t) + ":" + (n >= 10 ? n : "0" + n) : null
                    }), l && f.PaymentLimitSeconds - r <= 0 ? (clearInterval(l), i.loadData()) : r++;
                }, 1e3);
            }
        });
    },
    onStatusTap: function() {
        var e = this;
        e.data.model.Role > 0 && e.data.model.Role < 3 && this.setData({
            popupType: "track"
        });
    },

    //打开收款二维码
    onMoneyQrcode: function () {
      var e = this;
      e.data.model.Role > 0 && e.data.model.Role < 3 && this.setData({
        dialogType: "qrcode"
      });
    },


    onCancelTap: function() {
        var e = this;
        wx.showModal({
            title: "取消确认",
            confirmColor: "#06c1ae",
            content: "确定要取消订单？",
            success: function(t) {
                t.confirm && a.orderCancel({
                    id: u.id
                }, function() {
                    e.loadData();
                });
            }
        });
    },
    onModalConfirm: function() {
        var e = this;
        a.orderRefund({
            id: u.id
        }, function() {
            t.fire("refreshHomeOrders"), e.loadData();
        }), e.setData({
            modalType: "none"
        });
    },
    onModalCancel: function() {
        this.setData({
            modalType: "none"
        });
    },
    onRequestRefundTap: function() {
        this.setData({
            dialogType: "profile",
            dialogMessage: "请先联系猎人放弃订单，然后再取消"
        });
    },

    userQuxiao: function () {
      console.log('--userQuxiao--');
      this.setData({
        popupType2: "quxiao"
      });
    },



    onRefundTap: function() {
        var e = this;
        e.setData({
            modalType: "refund"
        });
    },
    onRaiseTap: function() {
        this.setData({
            popupType: "tip"
        });
    },
    onGiveupContentFocus: function(e) {
        var t = this;
        s = r.length, t.setData({
            giveupCausesIndex: s
        });
    },
    onGiveupContentChanged: function(e) {
        d = e.detail.value;
    },
    onGiveupCauseChange: function(e) {
        var t = this;
        s = e.detail.value, t.setData({
            giveupCausesIndex: s
        });
    },
    onGiveupTap: function() {
        this.setData({
            popupType: "giveup"
        });
    },

    //配送员放弃订单新版
    onGiveupTap2: function () {
      console.log('----onGiveupTap2----');
      this.setData({
        fangqiType: "fangqi"
      });
    },
    
    //用户取消配送员的订单
    onFinishQuxiao: function () {
      var e = this;
      n.orderFinishQuxiao({
        id: s.id
      }, function () {
        e.setData({
          popupType2: "none"
        }), t.fire("refreshHomeOrders"), e.loadData();
      });
    },



    onFinishTap: function() {
        this.setData({
            dialogType: "finish"
        });
    },
    onFinishConfirm: function() {
        var e = this;
        a.orderFinish({
            id: u.id
        }, function() {
            e.setData({
                dialogType: "none"
            }), t.fire("refreshHomeOrders"), e.loadData();
        });
    },
    onConfirmSubmit: function(e) {
        a.userSaveFormId({
            formId: e.detail.formId
        });
        var n = this;
        wx.showModal({
            title: "完成确认",
            confirmColor: "#06c1ae",
            content: "确定跑腿人员已经完成了订单？",
            success: function(e) {
                e.confirm && a.orderConfirmFinish({
                    id: u.id
                }, function() {
                    t.fire("refreshHomeOrders"), n.loadData(), wx.navigateTo({
                        url: "appraise?finish=true&id=" + u.id
                    });
                });
            }
        });
    },
    onAppraise: function() {
        wx.navigateTo({
            url: "appraise?id=" + u.id
        });
    },

    onFilePhoto: function() {
        var id = this.data.model.running_id;
        wx.navigateTo({
            url: "photo?id=" + id
        });
    },


    onFeeItemTap: function(e) {
        var t = this, n = e.currentTarget.dataset.index;
        t.setData({
            feeIndex: n,
            feeInputFocus: n < 0
        });
    },
    onFeeInputBlur: function(e) {
        var t = this, n = e.detail.value;
        n && n.length && (n = parseInt(n), t.setData({
            feeIndex: i.indexOf(n),
            feeInputFocus: i.indexOf(n) < 0,
            feeInputValue: i.indexOf(n) < 0 ? n : 0
        }));
    },
    onPopupConfirm: function() {
        var e = this, o = e.data.popupType;
        setTimeout(function() {
            if ("tip" == o) {
                if (e.data.feeIndex < 0 && !e.data.feeInputValue) return void n.toast("请输入小费金额");
            } else if ("giveup" == o) {
                if (s < 0) return void n.toast("请选择放弃订单原因");
                if (!(s != r.length || d && d.length)) return void n.toast("请输入原因说明");
            }
            e.setData({
                popupType: "none"
            }), setTimeout(function() {
                if ("tip" == o) {
                    var l = e.data.feeIndex < 0 ? e.data.feeInputValue >= 1 ? Math.floor(e.data.feeInputValue) : 1 : i[e.data.feeIndex];
                    a.orderPayment({
                        id: u.id,
                        money: l
                    }, function(t) {
                        e.loadData();
                    });
                } else "giveup" == o && a.orderGiveup({
                    id: u.id,
                    cause: s >= 0 && s < r.length ? r[s] : d
                }, function() {
                    t.fire("refreshHomeOrders"), f.Status = 512, f.Logs.splice(0, 0, {
                        Title: "赏金猎人取消订单",
                        CreatedTime: n.formatDate(new Date(), "s")
                    }), e.setData({
                        orderStatus: {
                            Name: "放弃跑腿",
                            Value: 512
                        },
                        model: f
                    });
                });
            }, 200);
        }, 50);
    },
    onPopupCancel: function() {
        this.setData({
            popupType: "none"
        });
    },
    onAvatarTap: function() {
        this.setData({
            dialogType: "profile",
            dialogMessage: null
        });
    },
    onPhoneTap: function() {
        wx.makePhoneCall({
            phoneNumber: 1 == f.Role ? f.DelivererMobile : f.Mobile
        });
    },
    onDialogClose: function() {
        this.setData({
            dialogType: "none"
        });
    },

    onDialogquxiao: function () {
      this.setData({
        popupType2: "none"
      });
    },


    onDialogFangqi: function () {
      this.setData({
        fangqiType: "none"
      });
    },
    


    onPaymentSubmit: function(e) {
        var n = this;
        f.Status <= 1 && n.data.timeRemain && a.orderPayment({
            id: u.id
        }, function(e) {
            n.loadData(), t.fire("refreshHomeOrders");
        });
    },
    onSecretTap: function() {
        wx.showModal({
            title: "温馨提示 ",
            content: "机智的校园跑腿，已将此订单设为私密。除你本人，和通过学生认证的赏金猎人以外，其他用户看不到此订单。",
            showCancel: !1,
            confirmText: "我知道了"
        });
    }
});