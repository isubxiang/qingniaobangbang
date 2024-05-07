require("./utils/showToast.js"),
App({


    onLaunch: function(e) {
        // 获取小程序更新机制兼容
        if (wx.canIUse('getUpdateManager')) {
          const updateManager = wx.getUpdateManager()
          updateManager.onCheckForUpdate(function (res) {
            // 请求完新版本信息的回调
            if (res.hasUpdate) {
              updateManager.onUpdateReady(function () {
                wx.showModal({
                  title: '更新提示',
                  content: '新版本已经准备好，是否重启应用？',
                  success: function (res) {
                    if (res.confirm) {
                      // 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
                      updateManager.applyUpdate()
                    }
                  }
                })
              })
              updateManager.onUpdateFailed(function () {
                // 新的版本下载失败
                wx.showModal({
                  title: '已经有新版本了哟~',
                  content: '新版本已经上线啦~，请您删除当前小程序，重新搜索打开哟~',
                })
              })
            }
          })
        } else {
          // 如果希望用户在最新版本的客户端上体验您的小程序，可以这样子提示
          wx.showModal({
            title: '提示',
            content: '当前微信版本过低，无法使用该功能，请升级到最新微信版本后重试。'
          })
        };

        //更新
        var t = require("utils/onfire.js"), a = this;
        a.login(), wx.getSystemInfo({
            success: function(e) {
                a.globalData.systemInfo = e;
            }
        }), wx.getLocation({
            success: function(e) {
                a.globalData.locationInfo = e, t.fire("location", e);
            }
        });

        //获取系统信息
        wx.getSystemInfo({
          success: function (i) {
            a.globalData.systemInfo = i;
          }
        });
    },


    onShow: function() {
        var e = require("utils/onfire.js"), t = getCurrentPages();
        t.length && t[t.length - 1].route.indexOf("errand/_/index") >= 0 && e.fire("refreshHomeOrders");

        //调试下是否开启实时定位功能
        //var t=this;
        //t.onUserLocationChange();
    },

  
  setNavigationBarColor: function (o) {

    var e = wx.getStorageSync("setting").color;

    console.log(e, o), e && wx.setNavigationBarColor({
      frontColor: "#ffffff",
      backgroundColor: e
    });
    /*
    var t = this;
    var city_id = wx.getStorageSync('city_id');
    e || t.util.request({
      url: "app/Running/getSetting",
      success: function (e) {
        console.log(e),
          getApp().xtxx = e.data,
          t.globalData.color = e.data.color,
          t.setNavigationBarColor(o);
      }
    });
    */
  },


    onLoad: function (e) {
      this.setNavigationBarColor(this);
      console.log("app-onLoad",e);
    },

    util: require("utils/request.js"),
    siteInfo: require("siteinfo.js"),

    id: "wxe8ff72966a5c807b",
    baiduMapAK: "y4Kum6vKLxRNvSOThScUXhzs5wuprANs",
    globalData: {
      siteUrl: "https://www.rzsilbi.cn",
      isDebugger: !1,
      systemInfo: {},
      disableLoading: !1,
      userInfo: null,
      locationInfo: null,
      schoolInfo: null,
      settingInfo: null,
      systemInfo: {},
      platform: "wx",
      appVersion: "1.1.15"//版本号
    },

    pageSize: 10,
    require: require,

    getSettings: function () {
      return this.getStorage("settings");
    },



    onUserLocationChange: function () {
      this.getUserLocation();
      const _locationChangeFn = res => {
        var settings = wx.getStorageSync("settings");
        var SessionId = settings.SessionId;
        console.log('SessionId', SessionId)
        console.log('location change', res.latitude, res.longitude)
        wx.request({
          url: this.globalData.siteUrl + "/app/Running/location?userId=" + SessionId + "&lat=" + res.latitude + "& lng=" + res.longitude,
          header: {
            'content-type': 'application/json' // 默认值
          },
          success(res) {
            console.log('location',res);
          }
        })
      }
      wx.onLocationChange(_locationChangeFn);
    },



    ormatDate: function (e) {
      var o = new Date(1e3 * e);
      return o.getFullYear() + "-" + t(o.getMonth() + 1, 2) + "-" + t(o.getDate(), 2) + " " + t(o.getHours(), 2) + ":" + t(o.getMinutes(), 2) + ":" + t(o.getSeconds(), 2);
      function t(e, o) {
        for (var t = "" + e, a = t.length, n = "", s = o; s-- > a;) n += "0";
        return n + t;
      }
    },

    //新版函数整合开始
    checkMultiClick: function (t, i) {
      var n = this;
      n.globalData.multiClicked || (n.globalData.multiClicked = !0, setTimeout(function () {
        n.globalData.multiClicked = !1;
      }, 1e3), t.onSubmit(i));
    },
    getSystemInfo: function () {
      var t = this.globalData.systemInfo;
      return void 0 === t.SDKVersion && (t = wx.getSystemInfoSync()), t.appVersion = this.globalData.appVersion,
        t;
    },
    getSystemWidth: function () {
      return this.globalData.systemInfo.windowWidth || wx.getSystemInfoSync().windowWidth;
    },
    getSystemHeight: function () {
      var t = this.globalData.systemInfo.windowHeight || wx.getSystemInfoSync().windowHeight;
      return this.isWx() ? t : t + 54;
    },
    getShareData: function () {
      return this.globalData.shareData;
    },
    isIos: function () {
      return "ios" == this.getPlatForm();
    },
    isAndroid: function () {
      return "android" == this.getPlatForm();
    },
    isWx: function () {
      return "wx" == this.globalData.platform;
    },
    payAgent: function () {
      return this.isWx() ? "微信支付" : "QQ钱包";
    },
    getPlatForm: function () {
      return this.globalData.systemInfo.platform || wx.getSystemInfoSync().platform;
    },
    getQrScene: function () {
      return this.globalData.qrScene;
    },
    getAndroidVersion: function () {
      var t = this;
      if (this.isAndroid()) {
        var i = t.globalData.systemInfo.system.toLowerCase().split("android ");
        return 2 == i.length ? i[1] : "";
      }
      return "";
    },

	


    getStorage: function(e) {
        var t = wx.getStorageSync(e) || {};
        return t.save = function() {
            var t = {};
            for (var a in this) "save" != a && (t[a] = this[a]);
            wx.setStorageSync(e, t);
        }, t;
    },
    login: function() {
        var e = this, t = (require("utils/util.js"), require("utils/api.js")), a = require("utils/onfire.js"), n = function(n) {
            t.loginWechat({
                code: n || "",
                referrerId: ""
            }, function(t) {
                var n = e.getSettings();
                n.SessionId = t.SessionId, n.UserType = t.Type, n.session_key = t.session_key,n.save(), e.globalData.userInfo = t, 
                a.fire("login", t);
            }, function(t) {
                
            });
        };
        wx.login({
            success: function(e) {
                n(e.code);
            }
        });
    },

    //分享页面
    onAppShareAppMessage:function(e){
      
      console.log('=onAppShareAppMessage=');
      //获取学校
      var homeData = wx.getStorageSync("homeData");
      var schoolId = homeData.schoolId;

      var settings = wx.getStorageSync("settings");
      var SessionId = settings.SessionId;

      console.log('学校ID=' + schoolId);
      console.log('SessionId=' + SessionId);
      console.log('分享的url=', "/pages/errand/_/index?fuid=" + SessionId + "&schoolId=" + schoolId);

      var pages = getCurrentPages() //获取加载的页面
      var currentPage = pages[pages.length - 1] //获取当前页面的对象
      var pageUrl = currentPage.route //当前页面url

      console.log('当前页面url=', pageUrl);

      return {
        path: "/pages/errand/_/index?fuid=" + SessionId + "&schoolId=" + schoolId,
        success: function (t) { },
        fail: function (t) { }
      };
    },



  authorizeCallback: function (e, t , h) {
  
        var encryptedData = '', iv = '', signature='';

        if ( h != undefined) {
          var encryptedData = h.encryptedData;
          var iv = h.iv;
          var signature = h.signature
        }
        
        var settings = wx.getStorageSync("settings");
        var SessionId = settings.SessionId;
        var session_key = settings.session_key;

        //调用注册方法
        //this.getUser();

        var a = this, n = require("utils/util.js"), r = require("utils/api.js"), i = {
            AvatarUrl: t.avatarUrl,
            NickName: t.nickName,
            Gender: t.Gender,
            sencryptedData: encryptedData,
            iv: iv,
            signature: signature,
            session_key: session_key,
            SessionId: SessionId
        };
        return a.globalData.userInfo || (a.globalData.userInfo = {}), a.globalData.userInfo.AvatarUrl == i.AvatarUrl && a.globalData.userInfo.NickName == i.NickName && a.globalData.userInfo.Gender == i.Gender || r.updateWechatInfo({
            NickName: i.NickName,
            AvatarUrl: i.AvatarUrl,
            Gender: i.Gender,
            sencryptedData: encryptedData,
            iv: iv,
            signature: signature,
            session_key: session_key,
            SessionId: SessionId
        }), n.extend(a.globalData.userInfo, i), e.data.userInfo ? e.setData({
            dialogType: null,
            userInfo: i
        }) : e.setData({
            dialogType: null
        }), i;
    },


    callAuthorize: function(e, t) {

        var a = this, n = a.globalData.userInfo;
        n && (n.NickName || n.AvatarUrl) && e.setData({
            dialogType: null
        });


        var r = function() {
            wx.getUserInfo({
                success: function(n) {
                  console.log('=====callAuthorize获取用户信息======', n);  
                  var r = a.authorizeCallback(e,n.userInfo,n);
                    t && t(r);
                }
            });
        };
        wx.getSetting({
            success: function(t) {
                t.authSetting["scope.userInfo"] ? r() : e.setData({
                    dialogType: "authorize"
                });
            }
        });
    },


  getUser: function (n) {
    var s = this;
    wx.login({
      success: function (e) {
        var o = e.code;
        wx.setStorageSync("code", o), wx.getUserInfo({
          success: function (e) {
            console.log(e), wx.setStorageSync("user_info", e.userInfo);
            var t = e.userInfo.nickName, a = e.userInfo.avatarUrl;
            s.util.request({
              url: "app/Running/ThreadOpenid",
              cachetime: "0",
              data: {
                code: e,
                encryptedData: e.encryptedData,
                iv: e.iv,
                nickName: t,
                avatarUrl: a,
              },
              success: function (e) {
                console.log(e), wx.setStorageSync("key", e.data.session_key), wx.setStorageSync("openid", e.data.openid);
                var o = e.data.openid;
                var fuid = wx.getStorageSync("fuid");

                s.util.request({
                  url: "app/Running/ThreadLogin",
                  cachetime: "0",
                  data: {
                    openid: o,
                    img: a,
                    name: t,
                    user_id: t.data.user_id,
                    fuid: fuid
                  },
                  success: function (e) {
                    console.log(e), wx.setStorageSync("users", e.data), wx.setStorageSync("uniacid", e.data.uniacid),
                      n(e.data);
                  }
                });
              }
            });
          },
          fail: function (e) {
            wx.getSetting({
              success: function (e) {
                0 == e.authSetting["scope.userInfo"] && wx.openSetting({
                  success: function (e) {
                    e.authSetting["scope.userInfo"], s.getUser(n);
                  }
                });
              }
            });
          }
        });
      }
    });
  },
  
  //登录新版
  getUserInfo: function (o) {
    var t = this, e = this.globalData.userInfo;
      console.log('==getUserInfo=='),
      console.log(e),
      e ? "function" == typeof o && o(e) : wx.login({
        success: function (e) {
          wx.showLoading({
            title: "正在登录",
            mask: !0
          }),

            console.log('=======Openid======='),
            console.log(e),

            t.util.request({
              url: "app/Running/ThreadOpenid",
              cachetime: "0",
              data: {
                code: e.code,
                encryptedData: t.encryptedData,
                iv: t.iv,
                nickName: e.niakname,
                avatarUrl: e.avatarUrl,
              },
              header: {
                "content-type": "application/json"
              },
              dataType: "json",
              success: function (e) {
                console.log("openid信息", e.data), getApp().getOpenId = e.data.openid, getApp().getSK = e.data.session_key,
                  t.util.request({
                    url: "app/Running/ThreadLogin",
                    cachetime: "0",
                    data: {
                      openid: e.data.openid,
                      img: e.data.face,
                      name: e.data.nickname,
                      user_id: e.data.user_id,
                    },
                    header: {
                      "content-type": "application/json"
                    },
                    dataType: "json",
                    success: function (e) {
                      console.log("用户信息", e), 
                      getApp().getuniacid = e.data.uniacid, wx.setStorageSync("users", e.data),
                        t.globalData.userInfo = e.data, "function" == typeof o && o(t.globalData.userInfo);
                    }
                  });
              },
              fail: function (e) { },
              complete: function (e) { }
            });
        }
      });
  },

 
  


  getUrl: function (o) {
    var e = this.globalData.url;
    console.log(e, o), o.setData({
      url: e
    });
    var t = this;
    e || t.util.request({
      url: "app/Running/ThreadUrl",
      success: function (e) {
        console.log(e), wx.setStorageSync("url", e.data), t.globalData.url = e.data, t.getUrl(o);
      }
    });
  },


  //分享封装
  share: function (t, n) {
    return {
      title: t,
      path: n,
      success: function (t) { },
      fail: function (t) { }
    };
  },

  getUserLocation() {
    wx.getSetting({
      success(res) {
        console.log(res)
        if (res.authSetting['scope.userLocationBackground']) {
          wx.startLocationUpdateBackground({
            success: (res) => {
              console.log('startLocationUpdate-res', res)
            },
            fail: (err) => {
              console.log('startLocationUpdate-err', err)
            }
          })
        } else {
          if (res.authSetting['scope.userLocation'] == false) {
            console.log('打开设置页面去授权')
          } else {
            wx.startLocationUpdateBackground({
              success: (res) => {
                console.log('startLocationUpdate-res', res)
              },
              fail: (err) => {
                console.log('startLocationUpdate-err', err)
              }
            })
          }
        }
      }
    })
  },

  //常用变量
  _CFG: {
    BASE_API: "https://paotui.jintaocms.com/app/ele/common",
    wx_code: "",
    wx_openid: "",
    wx_unionid: "",
    wx_info: null,
    TOKEN: "",
    PHPSESSID: "",
    UID: 0,
    userInfo: null,
    userInfo2: null,
    shoptail: "",
    title: "",
    cateid: "",
    payorderid: "",
    isindexshow: !0,
    currentlat: "",
    currentlng: "",
    double: !0,
    useraddr: [],
    is_fz: !1
  }



});