var o = getApp(), a = o.require("utils/util.js"), e = o.require("utils/api.js"), t = o.require("utils/enums.js"), n = o.require("utils/onfire.js"), r = 0, l = !1, s = !1, i = null, d = 0, c = {
    ErrandOrders: []
};

Page({
    data: {
        userInfo: null,
        dialogType: null,
        modalType: null,
        errandTypes: t.ErrandTypes,
        school_id: 0,
        school_name: null,
        lastPage: !1,
        inputNotice: "",
        categories: [],
        categoryIndex: 0,
        disappearPopup: !1,
        model: c,
        nav: "nav",
        setting: '',
        banner: [],
        showOverlay: !1,
        nearestSchool: null,
        hydl: !1,
        is_index_category_type:0
    },



    onLoad: function(a) {
      
      var t = this, r = o.getStorage("homeData");
      //二维码扫码第一个参数
      if (a.scene) {
        let scene = decodeURIComponent(a.scene);
        //&是我们定义的参数链接方式第一个参数
        let fuid = a.scene.split("&")[0];
        console.log("转发获取到的fuid:", fuid);
        //设置缓存
        wx.setStorageSync("fuid", fuid);
      }

      //获取会员中心菜单
      o.util.request({
        url: "app/Running/getSetting",
        cachetime: "0",
        success: function (e) {

          console.log('getSetting', e.data.Data.wxapp);
      
          t.setData({
            setting: e.data.Data,
            hydl: e.data.Data.wxapp.is_index_pop,
            is_index_category_type: e.data.Data.wxapp.is_index_category_type
          });
          console.log(e);
          wx.setStorageSync("setting", e.data.Data);
        }
      });


        if(r.model) {
          wx.setNavigationBarTitle({
            title: r.model.siteName,
          })
        }


        if (r.model) {
            var l = [];
            if (r.model.Keywords) for (var i in r.model.Keywords) {
                if (i % 8 == 0) {
                    var d = new Array(8);
                    l.push(d);
                }
                l[l.length - 1][i % 8] = r.model.Keywords[i];
            }
            s = r.model && r.model.PopupAd && (!r.disappearPopup || r.disappearPopup != r.model.PopupAd.Title), 
            o.globalData.schoolInfo = {
                Id: r.schoolId,
                Name: r.schoolName
            }, t.setData({
                school_id: r.schoolId,
                school_name: r.schoolName,
                model: r.model,
                categories: l,
                dialogType: s ? "modal" : null
            });
        }


        n.on("refreshHomeOrders", function() {
            t.loadData();
        }), t.loadData(a);
        
        /*
        a.scene && wx.navigateTo({
            url: "../../shop/_/index?scene=" + a.scene
        });
        */



        var c = function(a) {
            e.homeNearestSchool({
                longitude: a.longitude,
                latitude: a.latitude
            }, function(a) {
                var e = o.getStorage("homeData");
                a && a.Id ? e.schoolId ? a.Id != e.schoolId && (a.IsDefault || t.setData({
                    modalType: "switchSchool",
                    nearestSchool: a
                })) : (o.globalData.schoolInfo = a, t.setData({
                    school_id: a.Id,
                    school_name: a.Name
                }), e.schoolId = a.Id, e.schoolName = a.Name, e.save()) : e.schoolId || t.setData({
                    school_id: 0,
                    school_name: "没有找到学校，重新定位"
                });
            });
        };


      n.on("location", function (o) {
        console.log(o);
        c(o);
      });  
        console.log(o.globalData);

        o.globalData.locationInfo ? c(o.globalData.locationInfo) : n.on("location", function(o) {
            console.log(o);
            c(o);
        });
    },

    homeData: function (t) {

      console.log('==homeData==');
      console.log(t);

      console.log('执行函数homeData');


      var a = this, r = !1;
      //获取学校
      var homeData = o.getStorage("homeData");
      var schoolId = homeData.schoolId;

      console.log('schoolId' + schoolId);

      a.setData({
        schoolId: schoolId,
        school_name: homeData.schoolName,
      });
    },


    onReady: function() {
        var o = this;
        n.on("login", function() {
            l = !0, d && (c.IsLogin || o.loadData());
        });
    },

    onShow: function() {
        n.un("SchoolSelect");
        var o = this;
        o.homeData(),
        o.loadData()
    },


    onHide: function() {},
    onUnload: function() {
        n.un("hideIndexDialog");
    },
    onPullDownRefresh: function() {
        r = 0, o.globalData.disableLoading = !0, this.loadData(null, function() {
            o.globalData.disableLoading = !1, wx.stopPullDownRefresh();
        });
    },
    onReachBottom: function() {
        console.log('==onReachBottom=');
        this.data.lastPage || this.loadData({
            pageIndex: ++r
        });
    },


    onShareAppMessage: function () {
      o.onAppShareAppMessage('');
    },


    runNoticeAnimate: function() {
        var o = this, a = [ "I", "", "I", "", "I", "想", "想让", "想让同", "想让同学", "想让同学帮", "想让同学帮你", "想让同学帮你干", "想让同学帮你干点", "想让同学帮你干点什", "想让同学帮你干点什么", "想让同学帮你干点什么..." ], e = 0;
        i = setInterval(function() {
            o.setData({
                inputNotice: a[e]
            }), e == a.length - 1 ? clearInterval(i) : e++;
        }, 250);
    },
    loadData: function(t3, n) {
        var r = this,t2 = t;
        e.homeIndex(t3, function(e) {
            var i = o.getStorage("homeData");

            console.log('==e.ErrandOrders=ttttt=');
            console.log(t3);
            console.log(n);

            if (t3 && t3.pageIndex) for (var u in e.ErrandOrders) c.ErrandOrders.push(e.ErrandOrders[u]); else {
                c = e;

                console.log('== if (t && t.pageIndex)=');
                console.log(c);


                var p = function() {
                    if (i.model) {
                        var o = function(o) {
                            o.Src && o.Src.indexOf("http://store/") >= 0 && wx.removeSavedFile({
                                filePath: o.Src,
                                complete: function(o) {
                                    console.log(o && o.errMsg);
                                }
                            });
                        };
                        if (i.model.Banners && i.model.Banners.length) for (var a in i.model.Banners) o(i.model.Banners[a]);
                        e.PopupAd && e.PopupAd.Src && i.model.PopupAd && o(i.model.PopupAd);
                    }


                    console.log('==e.ErrandOrders==');
                    console.log(e.ErrandOrders);

                    i.model = e, i.save();
                };


                if (e.Banners.length > 0 || e.PopupAd) {
                    var h = [];
                    for (var u in e.Banners) e.Banners[u] && h.push(e.Banners[u]);
                    e.PopupAd && e.PopupAd.Src && h.push(e.PopupAd);
                    /*
                    !function o() {
                        h.length ? a.download(h[0].Src, function(a) {
                            h[0].Src = a, h.splice(0, 1), o();
                        }) : p();
                    }();
                    */

                } else p();
            }


            var g = [];
            if (c.Keywords) for (var u in c.Keywords) {
                if (u % 8 == 0) {
                    var f = new Array(8);
                    g.push(f);
                }
                g[g.length - 1][u % 8] = c.Keywords[u];
            }


          //如果没缓存获取学校
          var homeData = wx.getStorageSync("homeData");
          if (homeData == '' || homeData == undefined){
            var t = o.getStorage("homeData");
            t.schoolId = e.SettingInfo.RunningSchoolId, t.schoolName = e.SettingInfo.RunningSchoolName, t.save();
          }



            var m = {
                userInfo: o.globalData.userInfo,
                lastPage: e.ErrandOrders.length < o.pageSize,
                model: c,
                banner: e.Banners,
                categories: g,
                schoolId: e.SettingInfo.RunningSchoolId,
                school_name: e.SettingInfo.RunningSchoolName,
            };

            console.log('--setData-m-');
            console.log(m);


            s || !c.PopupAd || i.model && i.model.PopupAd || (m.dialogType = "modal", s = !0), 
            r.setData(m), l ? d < 2 && !c.IsLogin && (d = 2, r.loadData()) : d = 1, n && n();
        }, n);
    },
    



    redirectUrl: function(o) {
      console.log('=redirectUrl=');
      console.log(o);

        if (o) {
            var a = o.indexOf("app://");
            a <= 0 ? (0 == a && (o = o.replace("app://", "/")), wx.navigateTo({
                url: o
            })) : wx.navigateTo({
                url: "/pages/common/content/web?url=" + encodeURIComponent(o)
            });
        }
    },





    onCategoryChange: function(o) {
        this.setData({
            categoryIndex: o.detail.current
        });
    },


    onSchoolTap: function() {
        var a = this;
        n.on("SchoolSelect", function(e) {
            o.globalData.schoolInfo = e, a.setData({
                school_id: e.Id,
                school_name: e.Name
            });
            var t = o.getStorage("homeData");
            t.schoolId = e.Id, t.schoolName = e.Name, t.save(), a.loadData();
        }), wx.navigateTo({
            url: "../../mine/school/select"
        });
    },
    onItemTap: function(o) {
        var a = o.currentTarget.dataset.index, e = c.ErrandOrders[a];
        e.IsSecret && 0 == e.Remark.replace(/(私密订单|\*)/g, "").length ? wx.showModal({
            title: "温馨提示 ",
            content: "机智的校园跑腿，已将此订单设为私密，你无法查看此订单信息。",
            showCancel: !1,
            confirmText: "我知道了"
        }) : wx.navigateTo({
            url: (e.DelivererUserId > 0 ? "../../order/_/info?id=" : "info?id=") + e.Id + "&status=" + e.Status
        });
    },
    onItemSubmitTap: function(o) {
        var a = o.currentTarget.dataset.index, e = c.ErrandOrders[a];
        e.IsSecret && 0 == e.Remark.replace(/(私密订单|\*)/g, "").length ? wx.showModal({
            title: "温馨提示 ",
            content: "机智的校园跑腿，已将此订单设为私密，你无法查看此订单信息。",
            showCancel: !1,
            confirmText: "我知道了"
        }) : wx.navigateTo({
            url: "info?id=" + e.Id + (2 != e.Status || e.IsForbiddenAccept ? "" : "&submit=true")
        });
    },
    onCategoryItemTap: function(o) {
        var a = o.currentTarget.dataset.url;
        var cate_id = o.currentTarget.dataset.id;
        console.log(a);
        console.log(cate_id);
        var url = a + '&cate_id=' + cate_id;
        this.redirectUrl(url);
    },


    onBannerTap: function(o) {
        var a = o.currentTarget.dataset.target;
        console.log(o);
        this.redirectUrl(a);
    },


    onDialogClose: function(a) {
        var e = this;
        e.setData({
            dialogType: null
        });
        var t = o.getStorage("homeData");
        t.disappearPopup = e.data.disappearPopup, t.save();
    },


    onDialogAdTap: function() {
        var o = this.data.model.PopupAd.Url;
        this.redirectUrl(o);
    },

    hydl:function(){
      var o = this;
      o.setData({
        hydl: !1
      });
    },


    onDialogAdDisappear: function() {
        var o = this;
        o.setData({
            disappearPopup: !o.data.disappearPopup && o.data.model.PopupAd.Title
        });
    },



    onModalConfirm: function() {
        var a = this, e = a.data.nearestSchool, t = o.getStorage("homeData");
        t.schoolId = e.Id, t.schoolName = e.Name, t.save(), o.globalData.schoolInfo = e, 
        a.setData({
            school_id: e.Id,
            school_name: e.Name,
            modalType: "none"
        }), a.loadData();
    },
    onModalCancel: function() {
        this.setData({
            modalType: "none"
        });
    },
    onQuestTap: function() {
        0 == this.data.categories.length ? wx.navigateTo({
            url: "/pages/errand/apply/index?type=1024"
        }) : this.setData({
            showOverlay: !0
        });
    },
    onOverlaySubmit: function() {
        this.setData({
            showOverlay: !1
        });
    },


  jumps: function (t) {
    var e = this, a = (t.currentTarget.dataset.name, t.currentTarget.dataset.appid), n = t.currentTarget.dataset.src, i = t.currentTarget.dataset.id, r = t.currentTarget.dataset.sjtype;

    console.log('=jumps=');
    console.log(i);
    var o = t.currentTarget.dataset.type;
    console.log(o);
    
    if (1 == o) {
      wx.navigateTo({
        url: n,
        success: function (t) {
          e.setData({
            averdr: !0
          });
        },
        fail: function (t) { },
        complete: function (t) { }
      });
    } else 2 == o ? wx.navigateTo({
      url: "../../car/car?vr=" + i + "&sjtype=" + r,
      success: function (t) { },
      fail: function (t) { },
      complete: function (t) { }
    }) : 3 == o && wx.navigateToMiniProgram({
      appId: a,
      path: "",
      extraData: {
        foo: "bar"
      },
      success: function (t) {
        e.setData({
          averdr: !0
        });
      }
    });
  },


});