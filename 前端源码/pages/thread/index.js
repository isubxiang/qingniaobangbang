var _Page;

function _defineProperty(t, e, a) {
    return e in t ? Object.defineProperty(t, e, {
        value: a,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : t[e] = a, t;
}

var app = getApp(), n = app.require("utils/onfire.js"), r = !1, Data = require("../../utils/util.js");

Page((_defineProperty(_Page = {
    data: {
        index: 0,
        schoolId:0,
        currentTab: 0,
        swiperCurrent: 0,
        indicatorDots: !1,
        autoplay: !0,
        interval: 5e3,
        duration: 1e3,
        circular: !0,
        school_name: null,
        averdr: !1,
        hotels: !1,
        nav2: "nav",
        refresh_top: !1,
        scroll_top: !0,
        index_class: !1,
        sxtab: [ "全部" ],
        activesxtab: 0
    },



  

    swiperChange: function(t) {
        this.setData({
            swiperCurrent: t.detail.current
        });
    },
    swiperChange1: function(t) {
        this.setData({
            swiperCurrent1: t.detail.current
        });
    },

  onSchoolTap: function () {
    var a = this;
    n.on("SchoolSelect", function (e) {
      app.globalData.schoolInfo = e, a.setData({
        school_id: e.Id,
        school_name: e.Name
      });
      var t = app.getStorage("homeData");
      t.schoolId = e.Id, t.schoolName = e.Name, t.save(), a.seller();
    }), wx.navigateTo({
      url: "../mine/school/select"
    });
  },
    

    jumps: function(t) {
        var e = this, a = (t.currentTarget.dataset.name, t.currentTarget.dataset.appid), n = t.currentTarget.dataset.src, i = t.currentTarget.dataset.id, r = t.currentTarget.dataset.sjtype;
        console.log(i);
        var o = t.currentTarget.dataset.type;

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
            url: "../car/car?vr=" + i + "&sjtype=" + r,
            success: function(t) {},
            fail: function(t) {},
            complete: function(t) {}
        }) : 3 == o && wx.navigateToMiniProgram({
            appId: a,
            path: "",
            extraData: {
                foo: "bar"
            },
            success: function(t) {
                e.setData({
                    averdr: !0
                });
            }
        });
    },
    city_select: function(t) {
        wx.navigateTo({
            url: "city",
            success: function(t) {},
            fail: function(t) {},
            complete: function(t) {}
        });
    },
    delete: function(t) {
        this.setData({
            averdr: !0
        });
    },
    changeIndicatorDots: function(t) {
        this.setData({
            indicatorDots: !this.data.indicatorDots
        });
    },
    changeAutoplay: function(t) {
        this.setData({
            autoplay: !this.data.autoplay
        });
    },
    intervalChange: function(t) {
        this.setData({
            interval: t.detail.value
        });
    },
    durationChange: function(t) {
        this.setData({
            duration: t.detail.value
        });
    },
    seller: function(t) {
        wx.navigateTo({
            url: "detail"
        });
    },
   

    onLoad: function(t) {
        
        var a = this, r = !1;


        //获取学校
        var homeData = app.getStorage("homeData");
        var schoolId = homeData.schoolId;

        console.log("---onLoad---");
        console.log(homeData);

        app.getUserInfo(function (t) {
            console.log('====app.getUserInfo===='),
            a.setData({
              userInfo: t,
              schoolId: schoolId,
              school_name: homeData.schoolName,
            });
        }), 

         wx.getLocation({
            type: "wgs84",
            success: function(t) {
                console.log(t), a.setData({
                    lat: t.latitude,
                    lng: t.longitude
                }), a.seller();
            }
        }), wx.getSystemInfo({
            success: function(t) {
                a.setData({
                    windowHeight: t.windowHeight
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadUrl2",
            cachetime: "0",
            success: function(t) {
                wx.setStorageSync("url2", t.data);
            }
        }), app.util.request({
            url: "app/Running/ThreadSystem",
            cachetime: "0",
            success: function(t) {
                console.log(t), 
                1e4 < Number(t.data.total_num) && (t.data.total_num = (Number(t.data.total_num) / 1e4).toFixed(2) + "万"),     

                wx.setStorageSync("System", t.data), 

                a.setData({
                    System: t.data,
                    userinfo: wx.getStorageSync("users")
                }), "1" == t.data.fj_tz && a.setData({
                    sxtab: [ "全部", "附近" ]
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadViews",
            cachetime: "0",
            success: function(t) {
                console.log(t);
                var e = t.data;
                "" == e ? e = 0 : 1e4 < Number(e) && (e = (Number(e) / 1e4).toFixed(2) + "万"), a.setData({
                    views: e
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadNum",
            data: {
              schoolId: schoolId
            },
            cachetime: "0",
            success: function(t) {
                a.setData({
                    Num: t.data
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadUrl",
            cachetime: "0",
            success: function(t) {
                wx.setStorageSync("url", t.data), a.setData({
                    url: t.data,
                    schoolId:schoolId
                });
            }
        }), a.refresh();
    },




  homeData: function (t) {
    console.log('执行函数homeData');
    var a = this, r = !1;
    //获取学校
    var homeData = app.getStorage("homeData");
    var schoolId = homeData.schoolId;
    a.setData({
      schoolId: schoolId,
      school_name: homeData.schoolName,
    });
  },


  onReady: function () {
    r = !0;
  },
  
  onShow: function () {
    console.log('执行函数onShow');
    var a = this;
    a.refresh();
  },
  onError: function (e) {
    console.log('onError');
    console.log(e);
  },

  onHide: function () { },
  onUnload: function () { },




    hddb: function() {
        wx.pageScrollTo({
            scrollTop: 0,
            duration: 1e3
        });
    },


    refresh: function(t) {
        var r = this, e = wx.getStorageSync("city");

        console.log('refresh');
        console.log(r.data.schoolId);

        app.util.request({
            url: "app/Running/ThreadStorelist",
            cachetime: "0",
            data: {
                cityname: e,
                schoolId: r.data.schoolId
            },
            success: function(t) {
                t.data.length <= 5 ? r.setData({
                    store: t.data
                }) : r.setData({
                    store: t.data.slice(0, 6)
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadAd",
            cachetime: "0",
            data: {
                cityname: e
            },
            success: function(t) {
                console.log(t);
                var e = [], a = [], n = [];
                for (var i in t.data) 8 == t.data[i].type && e.push(t.data[i]), 5 == t.data[i].type && a.push(t.data[i]), 
                7 == t.data[i].type && n.push(t.data[i]);
                r.setData({
                    slide: e,
                    advert: a,
                    ggslide: n
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadNews",
            cachetime: "0",
            data: {
                cityname: e
            },
            success: function(t) {
                var e = [];
                for (var a in t.data) 4 == t.data[a].type && e.push(t.data[a]);
                r.setData({
                    msgList: e
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadType",
            cachetime: "0",
            success: function(t) {
                var e = t.data;
                e.length <= 5 ? r.setData({
                    height: 165
                }) : 5 < e.length && r.setData({
                    height: 330
                });
                for (var a = [], n = 0, i = e.length; n < i; n += 10) a.push(e.slice(n, n + 10));
                console.log(a, e), r.setData({
                    nav: a,
                    navs: e
                });
            }
        });
    }
}, "seller", function(t) {
    var o = this, e = (o.data.index_class, wx.getStorageSync("city")), a = o.data.activeIndex, n = a ? o.data.navs[a].id : "", i = "1" == o.data.activesxtab ? "1" : "2", s = o.data.page, c = o.data.seller;

  console.log('seller');
  

    //获取学校刷新后改变学校
    var homeData = wx.getStorageSync("homeData");
    var schoolId = homeData.schoolId;
    console.log(schoolId);

    o.setData({
      schoolId: schoolId,
      school_name: homeData.schoolName,
    });


    console.log(a, e, s, n, i), null != s && "" != s || (s = 1), null != c && "" != c || (c = []), 
    app.util.request({
        url: "app/Running/ThreadList",
        cachetime: "0",
        data: {
            schoolId: schoolId,
            type_id: n,
            fj_tz: i,
            lat: o.data.lat,
            lng: o.data.lng,
            page: s,
            cityname: e
        },
        success: function(t) {
            if (console.log(t.data), 0 == t.data.length) o.setData({
                refresh_top: !0
            }); else {
                o.setData({
                    refresh_top: !1,
                    page: s + 1
                }), c = c.concat(t.data), c = function(t) {
                    for (var e = [], a = 0; a < t.length; a++) -1 == e.indexOf(t[a]) && e.push(t[a]);
                    return e;
                }(c);
            }


            if (0 < t.data.length) {

                console.log('===ThreadList==t.data=====', t.data);

                for (var e in t.data) {
                    var a = app.ormatDate(t.data[e].tz.sh_time);
                    t.data[e].tz.img = t.data[e].tz.img.split(","), t.data[e].tz.details = t.data[e].tz.details.replace("↵", " "), 
                    4 < t.data[e].tz.img.length && (t.data[e].tz.img_length = Number(t.data[e].tz.img.length) - 4), 
                    4 <= t.data[e].tz.img.length ? t.data[e].tz.img1 = t.data[e].tz.img.slice(0, 4) : t.data[e].tz.img1 = t.data[e].tz.img, 
                    t.data[e].tz.time = a.slice(0, 16), Number(t.data[e].juli) < 1e3 ? t.data[e].tz.juli = Number(t.data[e].tz.juli) + "m" : t.data[e].tz.juli = (Number(t.data[e].tz.juli) / 1e3).toFixed(2) + "km";
                }
                for (var n in c) {
                    for (var i in c[n].label) c[n].label[i].number = (void 0, r = "rgb(" + Math.floor(255 * Math.random()) + "," + Math.floor(255 * Math.random()) + "," + Math.floor(255 * Math.random()) + ")", 
                    r);
                    o.setData({
                        seller: c
                    });
                }
            } else o.setData({
                seller: c
            });
            var r;
        }
    });
}), _defineProperty(_Page, "commend", function(t) {
    var e = t.currentTarget.id;
    this.setData({
        page: "",
        seller: "",
        index_class: !0,
        activeIndex: e
    }), this.seller();
}), _defineProperty(_Page, "whole", function(t) {
    this.setData({
        activesxtab: t.currentTarget.dataset.index,
        activeIndex: null,
        page: 1,
        seller: [],
        index_class: !1
    }), this.seller();
}), _defineProperty(_Page, "bindinput", function(t) {
    var e = t.detail.value;
    "" != e && app.util.request({
        url: "app/Running/ThreadList",
        cachetime: "0",
        data: {
            keywords: e
        },
        success: function(t) {
            0 == t.data.length ? wx.showModal({
                title: "提示",
                content: "没有这个帖子",
                showCancel: !0,
                cancelText: "取消",
                confirmText: "确定",
                success: function(t) {},
                fail: function(t) {},
                complete: function(t) {}
            }) : wx.navigateTo({
                url: "detial?id=" + t.data[0].tz.id,
                success: function(t) {},
                fail: function(t) {},
                complete: function(t) {}
            });
        }
    });
}), 

_defineProperty(_Page, "ormatDate", function(t) {
    var e = new Date(1e3 * t);
    return e.getFullYear() + "-" + a(e.getMonth() + 1, 2) + "-" + a(e.getDate(), 2) + " " + a(e.getHours(), 2) + ":" + a(e.getMinutes(), 2) + ":" + a(e.getSeconds(), 2);
    function a(t, e) {
        for (var a = "" + t, n = a.length, i = "", r = e; r-- > n; ) i += "0";
        return i + a;
    }
}), 

_defineProperty(_Page, "thumbs_up", function(t) {
    var a = this, n = a.data.seller, i = t.currentTarget.dataset.id, r = wx.getStorageSync("users").id, e = (Number(t.currentTarget.dataset.num), 
    function(e) {
        n[e].tz.id == i && (n[e].thumbs_up = !0, app.util.request({
            url: "app/Running/ThreadLike",
            cachetime: "0",
            data: {
                information_id: i,
                user_id: r
            },
            success: function(t) {
                1 != t.data ? wx.showModal({
                    title: "提示",
                    content: "不能重复点赞",
                    showCancel: !0,
                    cancelText: "取消",
                    confirmText: "确认",
                    success: function(t) {},
                    fail: function(t) {},
                    complete: function(t) {}
                }) : (n[e].tz.givelike = Number(n[e].tz.givelike) + 1, a.setData({
                    seller: n
                }));
            }
        }));
    });
    for (var o in n) e(o);
}), 

_defineProperty(_Page, "previewImage", function(t) {
    console.log(t);
    var e = t.currentTarget.dataset.id, a = this.data.url, n = [], i = t.currentTarget.dataset.inde, r = this.data.seller;
    for (var o in r) if (r[o].tz.id == e) {
        var s = r[o].tz.img;
        for (var c in s) n.push(s[c]);
        wx.previewImage({
            current: s[i],
            urls: n
        });
    }
}), 

//发布信息
 _defineProperty(_Page, "fabu", function(t) {
    wx.navigateTo({
        url: "fabu"
    });
}), 




 _defineProperty(_Page, "phone", function(t) {
    var e = t.currentTarget.dataset.id;
    wx.makePhoneCall({
        phoneNumber: e
    });
}),


_defineProperty(_Page, "jump", function(t) {
    var e = t.currentTarget.dataset.id, a = t.currentTarget.dataset.name;
    wx.navigateTo({
        url: "lists?id=" + e + "&name=" + a,
        success: function(t) {},
        fail: function(t) {},
        complete: function(t) {}
    });
}), 


_defineProperty(_Page, "see", function(t) {
    this.data.seller;
    var e = t.currentTarget.dataset.id;
    wx.navigateTo({
        url: "detail?id=" + e,
        success: function(t) {},
        fail: function(t) {},
        complete: function(t) {}
    });
}), 
_defineProperty(_Page, "formid_one", function(t) {
    console.log("搜集第一个formid"), console.log(t), app.util.request({
        url: "app/Running/SaveFormid",
        cachetime: "0",
        data: {
            user_id: wx.getStorageSync("users").id,
            form_id: t.detail.formId,
            openid: wx.getStorageSync("openid")
        },
        success: function(t) {}
    });
}), 
_defineProperty(_Page, "onReady", function() {
    this.setData({
        first: 1
    });
}), 

_defineProperty(_Page, "onShow", function() {
    console.log('执行函数onShow');
    var a = this;
    a.homeData();
}), 

_defineProperty(_Page, "onHide", function() {}), 

_defineProperty(_Page, "onUnload", function() {
    wx.removeStorageSync("city_type");
}), 

_defineProperty(_Page, "onPullDownRefresh", function() {
    this.setData({
        page: 1,
        seller: [],
        refresh_top: !1
    }), this.refresh(), this.seller(), wx.stopPullDownRefresh();
}), 

_defineProperty(_Page, "onReachBottom", function() {
    0 == this.data.refresh_top && this.seller();
}), 

_defineProperty(_Page, "onShareAppMessage", function() {
    app.onAppShareAppMessage('');
}), _Page));