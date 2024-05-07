var n = getApp(), o = (n.require("utils/util.js"), n.require("utils/api.js")), e = 0, t = -1, a = {
    Logs: []
};

Page({
    data: {
        lastPage: !1,
        model: a
    },
    onLoad: function(n) {
        this.loadData();
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {
        this.data.lastPage || this.loadData({
            pageIndex: ++e
        });
    },
    onShareAppMessage: function() {},

    loadData: function(i) {
        var s = this;
        o.userDeposit(i, function(o) {
           s.setData({
              model: o
            });
        });
    },

    //解冻保证金
    userDepositThaw: function (i) {
      var s = this;
      o.userDepositThaw(i, function (o) {
        console.log('userDepositThaw', o)
        s.setData({
          model: o
        });
      });
    },

    //支付保证金
    userDepositPay: function (i) {
      var s = this;
      o.userDepositPay({
        userId: s.data.model.user_id
      }, function (a) {
          wx.switchTab({
            url: "../../mine/_/index"
          });
      });
    },


});