var t = getApp(), app = getApp(), a = t.require("utils/util.js"), o = t.require("utils/api.js"), n = 0, e = {
  Logs: []
};

Page({
  data: {
    modalType: "none",
    buttonEnabled: !1,
    remarkValue: '',
    is_cash:0,
    is_alipay_cash: 0,
    model: {}
  },
  onLoad: function (t) {
    var e = this;
    //获取会员中心菜单
    app.util.request({
      url: "app/Running/getSetting",
      cachetime: "0",
      success: function (h) {
        //赋值
        e.setData({
          is_cash: h.data.Data.is_cash,
          is_alipay_cash: h.data.Data.is_alipay_cash,
        });

        console.log(h);
        wx.setStorageSync("setting", h.data.Data);
      }
    });

    n = 0, this.loadData(t);
  },




  onReady: function () { },
  onShow: function () { },
  onHide: function () { },
  onUnload: function () { },
  onPullDownRefresh: function () { },
  onReachBottom: function () {
    this.data.lastPage || this.loadData({
      pageIndex: ++n
    });
  },
  onShareAppMessage: function () { },
  loadData: function () {
    var a = this;



    o.userWithdrawPrepare({
      pageIndex: n
    }, function (o) {
      if (0 == n) e = o; else for (var i in o.Logs) e.Logs.push(o.Logs[i]);
      a.setData({
        lastPage: o.Logs.length < t.pageSize,
        model: e
      });
    });
  },



  onQuestionTap: function () {
    this.setData({
      modalType: "explain"
    });
  },
  onModalConfirm: function () {
    this.setData({
      modalType: "none"
    });
  },

  bindTextAreaBlur: function (e) {
    this.setData({
      remarkValue: e.detail.value
    });
  },


  onWithdrawSubmit: function (t) {
    var n = parseFloat(t.detail.value.money);

    console.log(t);

    

    if (n > e.MoneyUsable) a.toast("可提现金额不足");
    else if (n < e.MoneyMinWithdraw) a.toast("单次提现金额必须大于" + e.MoneyMinWithdraw + "元");
    else if (t.detail.value.info == "") a.toast("请填写提现说明");
    else if (n < e.MoneyMinWithdraw) a.toast("单次提现金额必须大于" + e.MoneyMinWithdraw + "元"); else {

      var i = this;
      wx.showModal({
        title: "提现确认",
        confirmColor: "#06c1ae",
        content: "如果不填写支付宝信息就打入微信账户，确定提现？",
        success: function (a) {
          a.confirm && (o.userSaveFormId({
            formId: t.detail.formId
          }), o.userWithdraw({
            money: n,
            info: t.detail.value.info,
            alipay_real_name: t.detail.value.alipay_real_name,
            alipay_account: t.detail.value.alipay_account,
          }, function () {
            i.loadData(), wx.showModal({
              title: "提现成功",
              content: "你已成功提现" + n + "元",
              showCancel: !1
            });
          }));
        }
      });
    }
  },
  onMoneyInput: function (t) {
    var a = parseFloat(t.detail.value);
    this.setData({
      buttonEnabled: t.detail.value && t.detail.value.length && NaN != a && a > 0
    });
  }
});