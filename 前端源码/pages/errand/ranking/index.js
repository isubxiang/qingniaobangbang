var n = getApp(), o = (n.require("utils/util.js"), n.require("utils/api.js"));

Page({
    data: {
        setting: '',
        model: {}
    },

    
    onLoad: function(n) {
        var t = this;

        var app = getApp();
        app.util.request({
          url: "app/Running/getSetting",
          cachetime: "0",
          success: function (e) {
            t.setData({
              setting: e.data.Data
            });
            console.log(e);
            wx.setStorageSync("setting", e.data.Data);
          }
        });


        o.studentRanking(function(n) {
            t.setData({
                model: n
            });
        });
    },

    onPing: function (e) {
      console.log('ranking-onPing', e);
      var uid = e.currentTarget.dataset.uid
      wx.navigateTo({
        url: "../../errand/_/ping?uid=" + uid
      });
    },


    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {}
});