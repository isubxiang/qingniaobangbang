var t = getApp(),app = getApp(), e = t.require("utils/util.js"), a = t.require("utils/api.js"), n = t.require("utils/onfire.js"), d = [], o = new Date(), s = !1;

Page({
  data: {
    step: 0,
    todoStep: t.globalData.isDebugger ? 4 : 0,
    idCard_code: "",
    idCard_name: "",
    idCard_gender: 1,
    idCard_imageSrc: null,
    studentCard_code: "",
    studentCard_schoolId: 0,
    studentCard_schoolName: null,
    studentCard_faculty: "",
    studentCard_major: "",
    studentCard_enrollmentDate_min: e.formatDate(new Date(2010, 0, 1), "M"),
    studentCard_enrollmentDate_max: e.formatDate(o, "M"),
    studentCard_enrollmentDate: null,
    studentCard_imageSrc: null,
    mobile: "",
    logo:'',
    payCode: '',
    idCard_code_name: '身份证号',
    idCard_code_placeholder: '你的身份证号码18位',
    idCard_code_num: '17',
    is_auth_pay_code: '0',
    is_studentCard_code: '0',
    is_studentCard_faculty: '0',
    is_studentCard_major: '0',
    is_studentCard_enrollmentDate: '0',
    is_logo: '0',
    is_logo_name: '上传学生证',
    acceptAgreement: !0,
    smsCodeSentTick: -1,
    smsCode: d,
    smsCodeInputFocus: !1,
    dialogType: "none"
  },


  payCode: function (e) {
    var a = this, o = t.globalData.siteUrl;
    console.log(t),   
    wx.chooseImage({
      count: 1,
      sizeType: ["original", "compressed"],
      sourceType: ["album", "camera"],
      success: function (e) {
        console.log(e);
        var t = e.tempFilePaths[0];
        wx.uploadFile({
          url: o + "/app/app/uploadify",
          filePath: t,
          name: "upfile",
          formData: {},
          success: function (e) {
            console.log(e), 
            a.setData({
              payCode: e.data
            });
            n.fire("CardPhoto", e.data);
          },
          fail: function (e) {
            console.log(e);
          }
        }), a.setData({
          payCode: t
        });
      }
    });
  },

  choose: function (e) {
    var a = this, o = t.globalData.siteUrl;
    console.log(t),
      wx.chooseImage({
        count: 1,
        sizeType: ["original", "compressed"],
        sourceType: ["album", "camera"],
        success: function (e) {
          console.log(e);
          var t = e.tempFilePaths[0];
          wx.uploadFile({
            url: o + "/app/app/uploadify",
            filePath: t,
            name: "upfile",
            formData: {},
            success: function (e) {
              console.log(e),
                a.setData({
                  logo: e.data
                });
              n.fire("CardPhoto", e.data);
            },
            fail: function (e) {
              console.log(e);
            }
          }), a.setData({
            logo: t
          });
        }
      });
  },



  onLoad: function (t) {
      var e = this;

      //获取会员中心菜单
      app.util.request({
        url: "app/Running/getSetting",
        cachetime: "0",
        success: function (h) {
          //赋值
          e.setData({
            idCard_code_name: h.data.Data.idCard_code_name,
            idCard_code_placeholder: h.data.Data.idCard_code_placeholder,
            idCard_code_num: h.data.Data.idCard_code_num,
            is_auth_pay_code: h.data.Data.is_auth_pay_code,
            is_studentCard_code: h.data.Data.is_studentCard_code,
            is_studentCard_major: h.data.Data.is_studentCard_major,
            is_studentCard_faculty: h.data.Data.is_studentCard_faculty,
            is_studentCard_enrollmentDate: h.data.Data.is_studentCard_enrollmentDate,
            is_logo: h.data.Data.is_logo,
            is_logo_name: h.data.Data.is_logo_name,
            is_auth_pay_code: h.data.Data.is_auth_pay_code,
          });
          console.log(h);
          wx.setStorageSync("setting", h.data.Data);
        }
      });


      n.on("CardPhoto", function (t) {

      console.log(t);
      console.log(e);

      0 == e.data.step ? (e.setData({
        idCard_imageSrc: t
      }), e.updateNextButtonEnabled(0)) : (e.setData({
        studentCard_imageSrc: t
      }), e.updateNextButtonEnabled(1));
    }), n.on("SchoolSelect", function (t) {
      e.setData({
        studentCard_schoolId: t.Id,
        studentCard_schoolName: t.Name
      }), e.updateNextButtonEnabled(1);
    });
  },


  onReady: function () { },
  onShow: function () { },
  onHide: function () { },
  onUnload: function () {
    if (n.un("CardPhoto"), n.un("SchoolSelect"), 3 == this.data.step) {
      var t = getCurrentPages();
      wx.navigateBack({
        delta: t.length
      });
    }
  },


  onPullDownRefresh: function () { },
  onReachBottom: function () { },
  onShareAppMessage: function () {
    t.onAppShareAppMessage('');
  },


  updateNextButtonEnabled: function (e) {

    console.log('==updateNextButtonEnabled==',e);
  
    var a = this,
    n = a.data, d = !0;

    console.log(n.studentCard_code);
    console.log(n.studentCard_code);
    switch (e) {
      case 0:
        d = n.idCard_code.length >= n.idCard_code_num && n.idCard_name.length > 0 && n.acceptAgreement > 0;
        break;
      case 1:
        d = n.studentCard_schoolId && n.logo;
        break;
      case 2:
        d = 11 == n.mobile.length && n.smsCodeSentTick < 0;
    }
    d ? n.todoStep <= e && a.setData({
      todoStep: t.globalData.isDebugger ? 4 : e + 1
    }) : n.todoStep > e && a.setData({
      todoStep: t.globalData.isDebugger ? 4 : e
    });
  },


  sendSmsMessage: function (t) {
    var e = this, n = e.data.smsCodeSentTick;
    n < 0 && (n = 60, e.setData({
      smsCodeSentTick: --n
    }), a.commonSmsCode({
      mobile: e.data.mobile
    }, function () {
      t && t();
    }), setTimeout(function () {
      n > 0 ? e.setData({
        smsCodeSentTick: --n
      }) : e.setData({
        smsCodeSentTick: -1
      });
    }, 1e3));
  },


  onPrevTap: function () {
    var t = this;
    t.setData({
      step: t.data.step - 1
    });
  },


  onServiceContractLink: function () {
    var e = wx.getStorageSync("setting").wxapp;

    console.log('onServiceContractLink-delivery_apply_article_id', e.delivery_apply_article_id);
    wx.navigateTo({
      url: "../../common/content/web?url=" + encodeURIComponent("/wap/news/detail?article_id=" + e.delivery_apply_article_id)
    });
  },


  //选择按钮
  onServiceContract: function (e) {
    var n = this;
    console.log('onServiceContract-e', e);
    if(n.data.acceptAgreement ==1){
      var agreement = 0;
    }else{
      var agreement = 1;
    }
    n.setData({
      acceptAgreement: agreement,
    });
    n.updateNextButtonEnabled(0);
  },



  onNextTap: function (e) {
    console.log('onNextTap',e);
    var n = this;
    console.log('onNextTap-n.data.step', n.data.step);

    s ? (a.userSaveFormId({
      formId: e.detail.formId
    }), 2 == n.data.step ? n.sendSmsMessage(function () {
      n.setData({
        dialogType: "smsCode"
      }), setTimeout(function () {
        n.setData({
          smsCodeInputFocus: !0
        });
      }, 500);
    }) : n.setData({
      step: n.data.step + 1
    })) : t.callAuthorize(n, function () {
      s = !0;
    });
  },

  onIdCardNameInput: function (t) {
    var e = this;
    e.setData({
      idCard_name: t.detail.value
    }), e.updateNextButtonEnabled(0);
  },


  onGenderChange: function (t) {
    var e = this;
    e.setData({
      idCard_gender: t.detail.value
    }), e.updateNextButtonEnabled(0);
  },


  onIdCardCodeInput: function (t) {
    var e = this;
    e.setData({
      idCard_code: t.detail.value
    }), e.updateNextButtonEnabled(0);
  },
  onStudentCardCodeInput: function (t) {
    var e = this;
    e.setData({
      studentCard_code: t.detail.value
    }), e.updateNextButtonEnabled(1);
  },
  onStudentCardFacultyInput: function (t) {
    var e = this;
    e.setData({
      studentCard_faculty: t.detail.value
    }), e.updateNextButtonEnabled(1);
  },
  onStudentCardMajorInput: function (t) {
    var e = this;
    e.setData({
      studentCard_major: t.detail.value
    }), e.updateNextButtonEnabled(1);
  },
  onDateChange: function (t) {
    var e = this;
    e.setData({
      studentCard_enrollmentDate: t.detail.value
    }), e.updateNextButtonEnabled(1);
  },
  onGetPhoneNumber: function (t) {
    var a = this;
    t.errMsg ? e.toast(t.errMsg) : t.detail.encryptedData && a.onFinished({
      EncryptedData: t.detail.encryptedData,
      EncryptedIv: t.detail.iv
    });
  },
  onMobileInput: function (t) {
    var e = this;
    e.setData({
      mobile: t.detail.value
    }), e.updateNextButtonEnabled(2);
  },


  onSmsCodeInput: function (t) {
    var e = this, a = t.detail.value;
    d.splice(0, d.length);
    for (var n = 0; n < a.length; ++n) d.push(a.charAt(n));
    4 == a.length ? (e.setData({
      smsCode: d,
      smsCodeInputFocus: !1
    }), setTimeout(function () {
      e.onFinished({
        SmsCode: a,
        Mobile: e.data.mobile
      });
    }, 300)) : e.setData({
      smsCode: d
    });
  },

  onSmsCodeBlur: function () {
    this.setData({
      smsCodeInputFocus: !1
    });
  },

  onSmsCodeTap: function () {
    this.data.smsCodeInputFocus || this.setData({
      smsCodeInputFocus: !0
    });
  },

  onDialogClose: function (t) {
    this.setData({
      dialogType: "none",
      smsCodeInputFocus: !1
    });
  },

  onFinished: function (t) {
      var d = this;

      var settings = wx.getStorageSync("settings");
      var session_key = settings.session_key;

      console.log('session_key' + session_key);

        var s = e.extend({
          SchoolId: d.data.studentCard_schoolId,
          RealName: d.data.idCard_name,
          IdCode: d.data.idCard_code,
          Gender: d.data.idCard_gender,
          StudentCode: d.data.studentCard_code,
          Department: d.data.studentCard_faculty,
          Major: d.data.studentCard_major,
          EnrollmentDate: d.data.studentCard_enrollmentDate + "-01",
          FileList: d.data.logo,
          payCode: d.data.payCode,
          session_key: session_key
        }, t);

        console.log('==onFinished==');
        console.log(s);
        console.log('==e==');
        console.log(e);

        a.studentVerify(s, function () {
          var t = function (t) {
            t && t.indexOf("http://store/") >= 0 && wx.removeSavedFile({
              filePath: t,
              complete: function (t) {
                console.log(t && t.errMsg);
              }
            });
          };
          t(d.data.idCard_imageSrc), t(d.data.studentCard_imageSrc), d.setData({
            dialogType: "none",
            step: 3,
            todoStep: 3
          }), n.fire("studentAuthApplied");
        });
    
  
  }
});