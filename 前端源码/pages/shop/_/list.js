var t = getApp(), app = getApp(), e = t.require("utils/util.js"), api = t.require("utils/api.js"), i = {}, n = 0, o = [], r = -1;

Page({
  data: {
    screenWidth: t.globalData.systemInfo.windowWidth,
    screenHeight: t.globalData.systemInfo.windowHeight,
    cateId:0,
    keyword:'',
    value:'',
    cateId: 0,
    cateName: '',
    typeIndex: r,
    lastPage: !1,
    model: o
  },


  bindinput: function (t) {
    var a = t.detail.value;
    this.setData({
      value: a
    });
  },

  search: function (t) {
    var a = this.data.value, e = this;
    var cateId = this.data.cateId;
    
    var s = {
      keyword:a,
      cateId: cateId,
      id: this.data.cateId
    };

    console.log('this.data', this.data),
    console.log('s',s),


    this.loadData(s);
  },


  onLoad: function (t) {

    console.log('onLoad',t);
    var cateId = t.cateId;

    this.setData({
      cateId:t.id,
      cateId: cateId,
      index: t.cate_id
    }),this.loadData(t);
  },


  onReady: function () { },
  onShow: function () { 
  },
  onHide: function () { },
  onUnload: function () { },
  
  
	onPullDownRefresh: function() {
		n = 0, this.loadData(null, wx.stopPullDownRefresh, wx.stopPullDownRefresh);
	},
	onReachBottom: function() {
		this.data.lastPage || this.loadData({
			pageIndex: ++n
		});
	},
	
	
  onShareAppMessage: function () {
    t.onAppShareAppMessage('');
  },
 
 
  onFormSubmit: function (t) {
   	console.log('onFormSubmit',t);
   
  },
  
  
  loadData: function(a, n, r) {
        var s = this;
        var homeData = wx.getStorageSync("homeData");
        var school_id = homeData.school_id;

        console.log('school_id',school_id);
        console.log('a',a);

        t.util.request({
          url: "app/Running/eleType",
          cachetime: "0",
          success: function (t) {
            var e = t.data;
            e.length <= 5 ? s.setData({
              height: 165
            }) : 5 < e.length && s.setData({
              height: 330
            });
            for (var a = [], n = 0, i = e.length; n < i; n += 10) a.push(e.slice(n, n + 10));
            console.log(a, e), 
            s.setData({
              nav: a,
              navs: e
            });
          }
        });
        
        api.shopList(a,function(t) {
            console.log('==t.list==',t.list);	
            if (a && a.pageIndex) for (var r in t) o.push(t[r]); else o = t.list;
              s.setData({
                  lastPage: t.length < e.pageSize,
                  model: o,
                  errandTypes: t.cates
              }), n && n();
          }, function() {
              r && r();
          });
    },

    tosearch: function () {
      wx.navigateTo({
        url: "search",
        success: function (t) {
        },
        fail: function (t) { },
        complete: function (t) { }
      });
    },

    jump: function (t) {
        var s = this;
        var e = t.currentTarget.dataset.id, a = t.currentTarget.dataset.name;
        wx.navigateTo({
          url: "list?cateId=" + e + "&name=" + a,
          success: function (t) {
            s.setData({
              cateId:e,
              cate_name:a
            });
           },
          fail: function (t) { },
          complete: function (t) { }
        });
    },
  
});