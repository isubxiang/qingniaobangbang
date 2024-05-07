var t = getApp();

Page({
    data: {
        hotSearch: [],
        hisSearch: [],
        kong: ""
    },
    onShow: function() {
        var e = this;

      t.util.request({
        url: "app/Running/hotsearch",
        cachetime: "0",
        success: function (t) {
          var hots = t.data.data.hots;
          console.log('hots',t.data.data.hots);


          
          wx.getStorage({
            key: "hisSearch",

            success: function (t) {
              e.setData({
                hisSearch: t.data,
                kong: ""
              });
            }
          }), "0" == t.data.error ? e.setData({
            hotSearch: hots
          }) : wx.pubShow({
            title: t.data.message
          });
        }
      });
    },


    inputvalue: function(e) {
        t._CFG.title = e.detail.value;
    },

    sousou: function(e) {

        console.log('==sousou==', e)
        console.log('==sousou-t._CFG.title==', t._CFG.title)

        var a = this.data.hisSearch;

        if (a.reverse(), "" != t._CFG.title && null != t._CFG.title) if (a.length >= 1) for (var s = 0; s < a.length; s++) {
            if (t._CFG.title == a[s]) {
                a.splice(s, 1), a.push(t._CFG.title);
                break;
            }
            s == a.length - 1 && a.push(t._CFG.title);
        } else a.push(t._CFG.title);
        a.reverse(), this.setData({
            hisSearch: a,
            kong: ""
        }), wx.setStorage({
            key: "hisSearch",
            data: a
        }), 
        
        wx.navigateTo({
          url: "shoplist",
        });
    },
    resou: function(e) {
      console.log('resou', e)
        t._CFG.title = e.currentTarget.dataset.idx, this.sousou();
    },


    clearup: function(t) {
        wx.clearStorage({
            key: "hisSearch"
        }), this.setData({
            hisSearch: []
        });
    },

    hislink: function(e) {
      var title = e.currentTarget.dataset.itm;
      console.log('hislink',e)
      console.log('hislink-title', title)

      t._CFG.title = title,

      wx.navigateTo({
        url: "shoplist?title=" + title,
      });
    },

    onShareAppMessage: function() {
        t.share("微信小程序外卖", "/pages/errand/_/index");
    }
});


