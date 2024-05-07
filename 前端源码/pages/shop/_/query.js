var t = getApp(), e = t.require("utils/util.js"), a = t.require("utils/api.js"), i = {}, o = null, r = !0;

Page({
  data: {
    screenWidth: t.globalData.systemInfo.windowWidth,
    screenHeight: t.globalData.systemInfo.windowHeight,
    shopId: 0,
    shopName: "外卖商家",
    keyword:'',
    value:'',
  },

  bindinput: function (t) {
    var a = t.detail.value;
    this.setData({
      value: a
    });
  },

  search: function (t) {
    var a = this.data.value, e = this;
    console.log(a),
    
    "" != a ? 
    //搜索跳转
    wx.navigateTo({
        url: "index?id=" + this.data.shopId + "&search=" + a
    }) : wx.showToast({
      title: "请输入关键字后点击搜索",
      icon: "loading"
    });
  },


  onLoad: function (t) {
    this.setData({
      shopId: t.shopId,
      shopName: t.shopName
    })
  },

  onReady: function () { },
  onShow: function () {
   
  },
  onHide: function () { },
  onUnload: function () { },
  onPullDownRefresh: function () { },
  onReachBottom: function () { },
  onShareAppMessage: function () {
    t.onAppShareAppMessage('');
  },
 
  onFormSubmit: function (t) {

    //搜跳转
    wx.navigateTo({
      url: "../commit/index?id=" + o.id
    });


  }
});