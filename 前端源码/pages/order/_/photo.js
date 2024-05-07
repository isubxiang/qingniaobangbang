var e = getApp(), t = e.require("utils/onfire.js"), n = e.require("utils/util.js"), a = e.require("utils/api.js"), o = e.require("utils/enums.js");


Page({

  data: {
    running_id : 0
  },

 
  onLoad: function (options) {
     var a = this;
     var id = options.id;
     a.loadData(id);
  },

 
  loadData: function(e) {
    var i = this;
    a.orderInfo({
        id: e
    }, function(a) {
        console.log('loadData',a), 
        i.setData({
          model: a
        });
    });
  },

  previewImage:function(t){
   
    var e = t.currentTarget.dataset.id,  n = [], i = t.currentTarget.dataset.inde, r = this.data.model.files;

    for (var c in r) n.push(r[c].srcImg);
  
    wx.previewImage({
        current: n[i],
        urls: n
    });
  
  },

  

  onReady: function () {
  },

 
  onShow: function () {
  },

 
  onHide: function () {
  },

 
  onUnload: function () {
  },

 
  onPullDownRefresh: function () {
  },

  onReachBottom: function () {
  },

  onShareAppMessage: function () {
  }
})