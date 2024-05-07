var e = getApp(), app = getApp(), a = e.require("utils/util.js"), o = e.require("utils/api.js"), t = e.require("utils/onfire.js"), n = (e.require("utils/enums.js"), 
!1), i = {
    VerifyStatus: 2
};

Page({
    data: {
        model: i,
        userId: 'ID错误',
        notifyFlag2: !1,
        setting:'',
        tmplIds:'',
        uid:'',
        userInfo: {},
        longTapAvatar: !1,
        canIUse: wx.canIUse("button.open-type.getUserInfo")
    },


  switchChange: function (t) {
      console.log('==switchChange==');
      this.setData({
        notifyFlag2: t.detail.value
      });
  },

  switchChangeMsg: function (t) {
      console.log('==switchChangeMsg==');

      wx.requestSubscribeMessage({
        tmplIds: [''+this.data.tmplIds+''], // 此处可填写多个模板 ID，但低版本微信不兼容只能授权一个
        success(res) {
          console.log('已授权接收订阅消息')
        },
        fail(res) {
          console.log(res)
        }
      })

      var e = this;
      o.notifyFlag2(function (a) {
        e.setData({
          notifyFlag2: !0,
        });
      });
  },


    onLoad: function(e) {
        var a = this;

 
        a.loadData(), t.on("studentAuthApplied", function() {
            a.loadData();
        });
        
        //获取会员中心菜单
        app.util.request({
          url: "app/Running/getSetting",
          cachetime: "0",
          success: function (e){
            a.setData({
              setting: e.data.Data
            });
            console.log(e);
            wx.setStorageSync("setting", e.data.Data);
          }
        });
      
    },


    onReady: function() {
        n = !0;
    },
    onShow: function() {
        var a = this;
        a.setData({
            longTapAvatar: !1
        }), e.callAuthorize(a), n && a.loadData(), t.fire("hideIndexDialog");
    },


    onHide: function() {},
    onUnload: function() {},
    onPullDownRefresh: function() {},
    onReachBottom: function() {},
    onShareAppMessage: function () {
      e.onAppShareAppMessage('');
    },
    onAuthorizeCallback: function(a) {
        var o = this;
      a.detail && a.detail.userInfo && e.authorizeCallback(o, a.detail.userInfo,a.detail);
    },


    loadData: function() {
        var e = this;
        o.homeMine(function(a) {

          console.log('=loadData=');
          console.log(a);

            e.setData({
                model: a,
                userId:a.user_id,
                notifyFlag2: a.notifyFlag2,
                tmplIds: a.tmplIds,
            });
        });
    },




    onAuthTap: function() {
        var e = this;
        wx.navigateTo({
            url: 0 == e.data.model.VerifyStatus ? "../info/auth" : 3 == e.data.model.VerifyStatus ? "../../common/result/fail?title=" + encodeURIComponent("认证审核失败") + "&button=" + encodeURIComponent("重新认证") + "&remark=" + encodeURIComponent(e.data.model.VerifyRemark) : "../info/student"
        });
    },


    onAvatarTap: function() {
        var e = this;
        e.data.longTapAvatar || wx.navigateTo({
            url: "../info/index?status=" + e.data.model.VerifyStatus
        }), e.setData({
            longTapAvatar: !1
        });
    },


    onPing: function (e) {
      var uid = wx.getStorageSync("settings").SessionId;
      wx.navigateTo({
        url: "../../errand/_/ping?uid=" + uid
      });
    },


    onAvatarLongTap: function() {
        if (e.globalData.userInfo && (4 | e.globalData.userInfo.Type) == e.globalData.userInfo.Type) {
            this.setData({
                longTapAvatar: !0
            });
            var a = "/sys/home?sessionId=" + e.globalData.userInfo.SessionId;
            wx.navigateTo({
                url: "../../common/content/web?headerForeColor=" + encodeURIComponent("#000000") + "&headerBgColor=" + encodeURIComponent("#fff") + "&title=" + encodeURIComponent("管理后台") + "&url=" + encodeURIComponent(a)
            });
        }
    },


  onServiceShop: function () {

      var settings = wx.getStorageSync("settings");
      var SessionId = settings.SessionId;
      var UserType = settings.UserType;
      var session_key = settings.session_key;

      var a = "/seller/index/index?uid=" + e.globalData.userInfo.SessionId+"&UserType="+UserType+"&session_key="+session_key;

      wx.navigateTo({
        url: "../../common/content/web?headerForeColor=" + encodeURIComponent("#000000") + "&headerBgColor=" + encodeURIComponent("#fff") + "&title=" + encodeURIComponent("商家管理后台") + "&url=" + encodeURIComponent(a)
      });
  },


    onServicePhoneTap: function() {
        var e = this;
        wx.makePhoneCall({
            phoneNumber: e.data.model.ServicePhone
        });
    },

  onFormSubmit2: function (i) {
      console.log('---onFormSubmit2---');
      o.userSaveFormId({
        formId: i.detail.formId
      })
      var a = this;
      a.loadData();
  },



  //关闭按钮
  onAuthorizeClose: function () {
    console.log('-onAuthorizeClose-')
    var e = this;
    e.setData({
      dialogType: null
    });
  },




    onServiceDescTap: function() {
        var e = this.data.model.ServiceDesc.match(/[\d]{5,}/);
        e && e.length && wx.showActionSheet({
            itemList: [ "复制" ],
            success: function() {
                wx.setClipboardData({
                    data: e[0],
                    success: function() {
                        a.toast("成功复制到剪贴板");
                    }
                });
            }
        });
    }
});