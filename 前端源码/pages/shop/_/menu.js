var t = getApp(), e = t.require("utils/util.js"), a = t.require("utils/api.js"), i = {}, o = null, r = !0;

Page({
    data: {
        screenWidth: t.globalData.systemInfo.windowWidth,
        screenHeight: t.globalData.systemInfo.windowHeight,
        model: {},
        carts: {
            Items: [],
            Quantity: 0
        },
        items: [],
        moneyPayable: 0,
        popupType: "none",
        categoryIndex: 0
    },
    onLoad: function(t) {
        o = t, this.loadData(o);
    },
    onReady: function() {},
    onShow: function() {
        r ? r = !1 : t.getStorage("cart")[o.id] || this.refreshCart(!1);
    },
    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function() {},
    loadData: function(e) {
        var r = this;
        a.shopShopIndex({
            id: e.id
        }, function(n) {
            i = n;
            var s = t.getStorage("cart")[e.id] || {
                Items: [],
                Quantity: 0
            }, d = 0;
            if (s && s.Items.length) for (var m in s.Items) {
                var u = s.Items[m];
                for (var c in i.PopularCommodities) {
                    var f = i.PopularCommodities[c];
                    if (f.Id == u.Id) {
                        f.Quantity = u.Quantity, u.Price != f.Price && (u.OldPrice = u.Price, u.Price = f.Price);
                        break;
                    }
                }
                d += u.Price * u.Quantity;
                for (var y in i.Categories) {
                    var l = i.Categories[y];
                    if (l.Id == u.CategoryId) {
                        l.Quantity = (l.Quantity || 0) + u.Quantity;
                        break;
                    }
                }
            }
            r.setData({
                model: n,
                items: n.PopularCommodities,
                carts: s,
                moneyPayable: d
            }), a.shopCommodityList({
                id: o.id
            }, function(t) {
                var e = null, a = [], o = r.data.carts;
                for (var n in t) {
                    var s = t[n];
                    if (!e || e.Id != s.CategoryId) for (var d in i.Categories) if ((e = i.Categories[d]).Id == s.CategoryId) break;
                    e.Commodities || (e.Commodities = []);
                    for (var m in o.Items) if ((c = o.Items[m]).Id == s.Id) {
                        s.Quantity = c.Quantity, a.push(c.Id), c.Price != s.Price && (c.OldPrice = c.Price, 
                        c.Price = s.Price);
                        break;
                    }
                    e.Commodities.push(s);
                }
                var u = 0;
                for (var m in o.Items) {
                    var c = o.Items[m];
                    c.IsSale = a.indexOf(c.Id) >= 0, u += c.Price * c.Quantity;
                }
                var f = 0 == r.data.categoryIndex ? i.PopularCommodities : i.Categories[r.data.categoryIndex - 1].category.Commodities;
                r.setData({
                    model: i,
                    items: f,
                    moneyPayable: u,
                    carts: o
                });
            });
        });
    },
    refreshCart: function(a, r) {
        var n = this, s = !1, d = n.data.carts;
        if (a) {
            d.Quantity += r;
            for (var m in d.Items) if ((c = d.Items[m]).Id == a.Id) {
                1 == c.Quantity && r < 0 ? d.Items.splice(m, 1) : c.Quantity = (c.Quantity || 0) + r, 
                s = !0;
                break;
            }
            if (!s && r > 0) {
                var u = e.clone(a);
                u.Quantity = 1, d.Items.push(u);
            }
        } else d = {
            Items: [],
            Quantity: 0
        };
        for (var m in i.PopularCommodities) {
            var c = i.PopularCommodities[m];
            if (a) {
                if (c.Id == a.Id) {
                    c.Quantity = (c.Quantity || 0) + r;
                    break;
                }
            } else c.Quantity = 0;
        }
        for (var m in i.Categories) {
            var f = i.Categories[m];
            if (a) {
                if (a.CategoryId == f.Id && (f.Quantity = (f.Quantity || 0) + r, f.Commodities)) for (var y in f.Commodities) if ((c = f.Commodities[y]).Id == a.Id) {
                    c.Quantity = (c.Quantity || 0) + r;
                    break;
                }
            } else if (f.Quantity = 0, f.Commodities) for (var y in f.Commodities) c.Quantity = 0;
        }
        var l = 0 == n.data.categoryIndex ? i.PopularCommodities : i.Categories[n.data.categoryIndex - 1].Commodities, I = a ? n.data.moneyPayable + a.Price * r : 0;
        n.setData({
            carts: d,
            moneyPayable: I,
            model: i,
            items: l
        });
        var C = t.getStorage("cart");
        d.Items.length ? C[o.id] = d : delete C[o.id], C.save();
    },
    onMenuItemTap: function(t) {
        var e = this, a = t.currentTarget.dataset.index;
        if (0 == a) e.setData({
            categoryIndex: a,
            items: i.PopularCommodities
        }); else {
            var o = i.Categories[a - 1];
            o.Commodities ? e.setData({
                categoryIndex: a,
                items: o.Commodities
            }) : e.setData({
                categoryIndex: a,
                items: []
            });
        }
    },
    onListPlusTap: function(t) {
        var e = this, a = t.currentTarget.dataset.index, o = null;
        o = 0 == e.data.categoryIndex ? i.PopularCommodities[a] : i.Categories[e.data.categoryIndex - 1].Commodities[a], 
        this.refreshCart(o, 1);
    },
    onListMinusTap: function(t) {
        var e = this, a = t.currentTarget.dataset.index, o = null;
        o = 0 == e.data.categoryIndex ? i.PopularCommodities[a] : i.Categories[e.data.categoryIndex - 1].Commodities[a], 
        this.refreshCart(o, -1);
    },
    onCartPlusTap: function(t) {
        var e = this, a = t.currentTarget.dataset.index, i = e.data.carts.Items[a];
        this.refreshCart(i, 1);
    },
    onCartMinusTap: function(t) {
        var e = this, a = t.currentTarget.dataset.index, i = e.data.carts.Items[a];
        this.refreshCart(i, -1);
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
        a.userSaveFormId({
            formId: t.detail.formId
        }), wx.navigateTo({
            url: "../commit/index?id=" + o.id
        });
    }
});