var t = getApp(), a = t.require("utils/util.js"), e = t.require("utils/api.js"), o = {}, i = !0;

Page({
    data: {
        screenWidth: t.globalData.systemInfo.windowWidth,
        screenHeight: t.globalData.systemInfo.windowHeight,
        model: {},
        log:{},
        carts: {
            Items: [],
            Quantity: 0
        },
        items: [],
        moneyPayable: 0,
        modalType: "none",
        popupType: "none",
        shopIndex: 0,
        categoryIndex: 0,
        options: {},
        cate_id: '',
        search:'',//搜索中

        //弹窗
        msgItem: "msgItem1",
        hidden: true,
        hiddentext: false,
        modelImg: null,
        modelName: null,
        modelDesc: null,
        selectPerson: true,
    },


    //弹窗model
    modelpop: function (e) {

      console.log("弹出框", e.currentTarget.dataset)

      var that = this;
      that.setData({
        hidden: false,
        modelImg: e.currentTarget.dataset.img,
        modelName: e.currentTarget.dataset.name,
        modelDesc: e.currentTarget.dataset.desc,
      })
    },

    //取消模态框1
    cancel: function () {
      this.setData({
        hidden: true,
      });
    },
    //取消模态框2
    modalConfirm: function () {
      this.setData({
        hidden: true,
      });
    },
  


    onLoad: function(t) {
        this.setData({
            options: t,
            cate_id: t.cate_id,
            search: t.search
        }), this.loadData(t);
    },


    onReady: function() {},
    onShow: function() {
        var a = this;
        if (i) i = !1; else {
            var e = a.data.model.Shops;
            if (e && e.length) {
                var o = e[a.data.shopIndex].Id;
                t.getStorage("cart")[o] || a.refreshCart(!1);
            }
        }
    },
    onHide: function() {
        "none" != this.data.modalType && this.setData({
            modalType: "none"
        });
    },
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},


    onShareAppMessage: function () {
      t.onAppShareAppMessage('');
    },


    loadData: function(t) {
        var a = this;
        e.shopIndex(t, function(t) {
            o = t, t.GroupInfo && (wx.setNavigationBarColor({
                frontColor: "#fff" == t.GroupInfo.NavForeColor ? "#ffffff" : "#000000",
                backgroundColor: t.GroupInfo.NavBackgroundColor || "#ffffff"
            }), 
            
            console.log(t.GroupInfo),
            console.log('t',t),
            console.log('t', t),

            wx.setNavigationBarTitle({
                title: t.GroupInfo.IsHideNavTitle ? t.GroupInfo.IsHideNavTitle : t.GroupInfo.Name
            })), t.Shops && t.Shops.length ? a.loadCategories(t, 0) : a.setData({
                modalType: "more"
            });
        });
    },
    loadCategories: function(i, r) {
        var n = this, s = i.Shops[r].Id, d = t.getStorage("cart"), m = d[s] || {
            Items: [],
            Quantity: 0
        }, u = !1, l = 0;
        if (m && m.Items.length) for (var f in m.Items) {
            var c = m.Items[f];
            for (var p in o.PopularCommodities) {
                var I = o.PopularCommodities[p];
                if (I.Id == c.Id) {
                    I.Quantity = c.Quantity, c.Price != I.Price && (c.OldPrice = c.Price, c.Price = I.Price, 
                    u = !0);
                    break;
                }
            }
            l = a.decimal.add(l, a.decimal.multiply(c.Price, c.Quantity));
            for (var h in o.Categories) {
                var g = o.Categories[h];
                if (g.Id == c.CategoryId) {
                    g.Quantity = (g.Quantity || 0) + c.Quantity;
                    break;
                }
            }
        }

      //二次加载运费
      var log = {};
      log.MinFreightMoney = i.MinFreightMoney;
      log.logistics = i.logistics;
      log.logistics_full = i.logistics_full;

      console.log('log', log),

        n.setData({
            model: i,
            items: i.PopularCommodities,
            carts: m,
            moneyPayable: l,
            log:log
        }), i.Shops.length && o.Categories && o.Categories.length ? e.shopCommodityList({
            id: i.Shops[r].Id
        }, function(t) {
            var e = null, i = [], r = n.data.carts;
            for (var s in t) {
                var m = t[s];
                if (!e || e.Id != m.CategoryId) for (var l in o.Categories) if ((e = o.Categories[l]).Id == m.CategoryId) break;
                e.Commodities || (e.Commodities = []);
                for (var f in r.Items) if ((p = r.Items[f]).Id == m.Id) {
                    m.Quantity = p.Quantity, i.push(p.Id), p.Price != m.Price && (p.OldPrice = p.Price, 
                    p.Price = m.Price, u = !0);
                    break;
                }
                e.Commodities.push(m);
            }
            var c = 0;
            for (var f in r.Items) {
                var p = r.Items[f];
                p.IsSale = i.indexOf(p.Id) >= 0, c = a.decimal.add(c, a.decimal.multiply(p.Price, p.Quantity));
            }
            var I = 0 == n.data.categoryIndex ? o.PopularCommodities : o.Categories[n.data.categoryIndex - 1].category.Commodities;
            
           

            n.setData({
                model: o,
                items: I,
                moneyPayable: c,
                carts: r
            }), u && d && d.save();
        }) : u && d && d.save();
    },
    refreshCart: function(e, i) {
        var r = this, n = !1, s = r.data.carts;
        if (e) {
            s.Quantity += i;
            for (var d in s.Items) if ((u = s.Items[d]).Id == e.Id) {
                1 == u.Quantity && i < 0 ? s.Items.splice(d, 1) : u.Quantity = (u.Quantity || 0) + i, 
                n = !0;
                break;
            }
            if (!n && i > 0) {
                var m = a.clone(e);
                m.Quantity = 1, s.Items.push(m);
            }
        } else s = {
            Items: [],
            Quantity: 0
        };
        for (var d in o.PopularCommodities) {
            var u = o.PopularCommodities[d];
            if (e) {
                if (u.Id == e.Id) {
                    u.Quantity = (u.Quantity || 0) + i;
                    break;
                }
            } else u.Quantity = 0;
        }
        for (var d in o.Categories) {
            var l = o.Categories[d];

            console.log('==o.Categories[d]==', o.Categories[d]);
            console.log('==l.Commodities==', l.Commodities);

            console.log('==o==', o);


            if (e) {
                if (e.CategoryId == l.Id && (l.Quantity = (l.Quantity || 0) + i, l.Commodities)) for (var f in l.Commodities) if ((u = l.Commodities[f]).Id == e.Id) {
                    u.Quantity = (u.Quantity || 0) + i;
                    break;
                }
            } else if (l.Quantity = 0, l.Commodities) for (var f in l.Commodities) (u = l.Commodities[f]).Quantity = 0;
        }


        var c = 0 == r.data.categoryIndex ? o.PopularCommodities : o.Categories[r.data.categoryIndex - 1].Commodities, p = e ? a.decimal.add(r.data.moneyPayable, a.decimal.multiply(e.Price, i)) : 0;
        r.setData({
            carts: s,
            moneyPayable: p,
            model: o,
            items: c
        });
        var I = r.data.model.Shops;
        if (I && I.length) {
            var h = I[r.data.shopIndex].Id, g = t.getStorage("cart");
            s.Items.length ? g[h] = s : delete g[h], g.save();
        }
    },
    onShopItemTap: function(t) {
        var a = this, i = t.currentTarget.dataset.index;
        i != a.data.shopIndex && (this.setData({
            shopIndex: i,
            categoryIndex: 0
        }), e.shopCategories({
            id: o.Shops[i].Id
        }, function(t) {
            t.Shops = o.Shops, o.PopularCommodities = t.PopularCommodities, o.Categories = t.Categories, 
            a.loadCategories(t, i);
        }));
    },


  onMenuItemTap: function (t) {
    var a = this, e = t.currentTarget.dataset.index;
    if (0 == e) a.setData({
      categoryIndex: e,
      items: o.PopularCommodities
    }); else {
      var i = o.Categories[e-1];

      console.log('=onMenuItemTap=');
      console.log(o.Categories);
      console.log(e);
      console.log(e);


      i.Commodities ? a.setData({
        categoryIndex: e,
        items: i.Commodities
      }) : a.setData({
        categoryIndex: e,
        items: []
      });
    }
  },
    onListPlusTap: function(t) {
        var a = this, e = t.currentTarget.dataset.index, i = null;
        (i = 0 == a.data.categoryIndex ? o.PopularCommodities[e] : o.Categories[a.data.categoryIndex - 1].Commodities[e]).IsSale = !0, 
        this.refreshCart(i, 1);
    },
    onListMinusTap: function(t) {
        var a = this, e = t.currentTarget.dataset.index, i = null;
        i = 0 == a.data.categoryIndex ? o.PopularCommodities[e] : o.Categories[a.data.categoryIndex - 1].Commodities[e], 
        this.refreshCart(i, -1);
    },
    onCartPlusTap: function(t) {
        var a = this, e = t.currentTarget.dataset.index, o = a.data.carts.Items[e];
        this.refreshCart(o, 1);
    },
    onCartMinusTap: function(t) {
        var a = this, e = t.currentTarget.dataset.index, o = a.data.carts.Items[e];
        this.refreshCart(o, -1);
    },
    onCartTap: function() {
        this.setData({
            popupType: "cart"
        });
    },
    onCartClear: function() {
        this.refreshCart(!1);
    },
    onPopupCancel: function() {
        this.setData({
            popupType: "none"
        });
    },


    onFormSubmit: function(t) {
        e.userSaveFormId({
            formId: t.detail.formId
        });
        var a = this, o = a.data.model.Shops;

        var moneyPayable = this.data.moneyPayable;//已点价格
        var moneyPayable = moneyPayable*100;//已点价格
        var since_money = o[a.data.shopIndex].since_money;

        var since_money2 = since_money/100;
        since_money2 = since_money2.toFixed(2);
      
        console.log('onFormSubmit-since_mone', o[a.data.shopIndex]);
        console.log('onFormSubmit-since_money2', since_money2);

        var IsClosed = o[a.data.shopIndex].IsClosed;
        if (IsClosed == 1) {
          wx.showToast({
            title: '当前商家未营业或者未在营业时间段',
            confirmColor: "#06c1ae",
            icon: 'none',
            duration: 3000
          })
          return false;
        }

        if(moneyPayable < since_money){
            wx.showToast({
              title: '再点一点吧，还不够起送价' +since_money2+'元哦',
              confirmColor: "#06c1ae",
              icon: 'none',
              duration: 3000
            })
            return false;
        }
     
     


      
      console.log('since_money =' + since_money );
      console.log('moneyPayable=' + moneyPayable);

        if (o && o.length) {
            var i = o[a.data.shopIndex].Id;
            wx.navigateTo({
              url: "../commit/index?id=" + i + "&groupId=" + (a.data.options.groupId || 0) + "&cate_id=" + (a.data.options.cate_id || 0)
            });
        }
    },
    onMoreShopTap: function() {
        this.setData({
            modalType: "more"
        });
    },
    onModalConfirm: function() {
        this.setData({
            modalType: "none"
        });
    },

    //测试搜索
    onQuery: function (t) {
      var a = this, shopId = t.currentTarget.dataset.shopid,shopName = t.currentTarget.dataset.shopname;
      console.log(t.currentTarget.dataset);
      wx.navigateTo({
        url: "query?shopId=" + shopId + "&shopName=" + shopName
      });
    },

    //关闭搜索
    offQuery: function (t) {
      var a = this, shopId = t.currentTarget.dataset.shopid, shopName = t.currentTarget.dataset.shopname;
      console.log(t.currentTarget.dataset);
      wx.navigateTo({
        url: "index?id=" + shopId
      });
    },

    onPing: function (e) {
      console.log('shop-onPing', e);
      var uid = e.target.dataset.uid
      wx.navigateTo({
        url: "../../errand/_/ping?uid=" + uid
      });
    },

});