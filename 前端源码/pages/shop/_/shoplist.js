var a = getApp(), app = getApp(), e = null;

Page({
    data: {
        lists: [],
        pageimg: a._CFG.IMG_URL,
        cat_id: "",
        topmask: !0,
        morehidden: !0,
        loadhide: !0,
        page: 1,
        title: "",
        nodata: !0,
        isActive: "",
        tempText: "",
        wordLength: [],
        wordLength2: []
    },
    onLoad: function(i) {
        var s = this;

        console.log('onLoad-a._CFG.title',a._CFG.title);
        console.log('onLoad-a._CFG', a._CFG);

        app.util.request({
          url: "app/Running/shopsearch",
          cachetime: "0",
          data:{
            page: 1,
            type: "shops",
            title: a._CFG.title
          },
          success: function (a) {
            var a = a.data;

            if ("1" == a.error) {
              s.setData({
                morehidden: !0,
                nodata: !1,
              })
            };


            if (console.log(a), "0" == a.error) {
              for (var i = 0; i < a.data.items.length; i++)  s.data.wordLength.push(a.data.items[i].title.length);
              "" == a.data.items ? s.setData({
                nodata: !1
              }) : s.setData({
                nodata: !0
              }), s.setData({
                lists: a.data.items,
                wordLength2: s.data.wordLength
              }), s.shaixuan();
            } else wx.pubShow({
              title: a.message
            });
          }
        });

        s.setData({
          title: a._CFG.title,
          tempText: a._CFG.title
        });
    },


    backup: function() {
      console.log('backup');
      wx.navigateBack({
        delta: 1
      })
    },


    onShareAppMessage: function(t) {
        return {
            title: "微信小程序外卖",
            path: "/pages/shop/_/list"
        };
    },
    todetail: function(t) {
        var e = t.currentTarget.id;
        wx.navigateTo({
          url: "index?id=" + e,
        });
    },


    more: function() {
        var i = this.data.page;
        i++;
        var s = this;

        app.util.request({
          url: "app/Running/shopsearch",
          cachetime: "0",
          data: {
            page: i,
            type: "shops",
            title: a._CFG.title
          },
          success: function (a) {
              var a = a.data;

              if ("1" == a.error) {
                s.setData({
                  morehidden: !0,
                  nodata: !1,
                })
              }
              
              if ("0" == a.error) {
                0 == a.data.items.length ? s.setData({
                  morehidden: !1,
                  loadhide: !0
                }) : s.setData({
                  loadhide: !1,
                  lists: s.data.lists.concat(a.data.items),
                  page: i
                }), s.shaixuan();
              } else wx.pubShow({
                title: a.message
              });
            }

          });
    },



    zhankai: function(t) {
        var a = t.currentTarget.dataset.index;
        a === this.data.isActive ? this.setData({
            isActive: ""
        }) : this.setData({
            isActive: a
        });
    },


    shaixuan: function() {
        var t = JSON.parse(JSON.stringify(this.data.lists));
         this.setData({
            lists: t
        });
    },


    digui: function(t, a, e) {
        var i = this, s = new RegExp(i.data.tempText, "g");
        if (t.constructor == Array) t.forEach(function(o, n) {
            o.constructor == String ? a[e].splice(n, 1, o.replace(s, "<span style='color:#2998ff'>" + i.data.tempText + "</span>")) : i.digui(o, t);
        }); else if (t.constructor == Object) {
            var o = {};
            for (var e in t) o[e] = t, i.digui(t[e], t, e);
        } else t.constructor == String && e && (a[e] = t.replace(s, "<span style='color:#2998ff'>" + i.data.tempText + "</span>"));
    }
});