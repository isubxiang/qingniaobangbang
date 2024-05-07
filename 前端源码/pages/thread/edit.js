var app = getApp(), _imgArray = [];

Page({
    data: {
        stick_none: !1,
        checked: !1,
        checked_welfare: !1,
        checked_average: !1,
        checked_password: !1,
        know: !1,
        num: 1,
        disabled: !1,
        money1: 0,
        countries: [ "本地", "全国" ],
        countryIndex: 0,
        radiochecked: !0
    },
    checkboxChange: function(e) {
        this.setData({
            radiochecked: !this.data.radiochecked
        });
    },
    bindCountryChange: function(e) {
        var t = this.data.zdindex, a = this.data.stick;
        console.log("picker country 发生选择改变，携带值为", e.detail.value, t, a);
        this.setData({
            countryIndex: e.detail.value,
            money: this.data.moneyarr[e.detail.value]
        }), null != t && this.setData({
            money1: 0 == e.detail.value ? a[t].money : a[t].money2
        });
    },

    bindMultiPickerChange: function(e) {
        this.setData({
            multiIndex: e.detail.value
        });
    },

    bindPickerChange: function(e) {
        var t = this.data.stock[e.detail.value];
        this.setData({
            index: e.detail.value,
            text: t
        });
    },

    onLoad: function(e) {
        console.log('onLoad');
        console.log(e);
        
        app.setNavigationBarColor(this);

        var i = this, t = wx.getStorageSync("users").id;
        console.log(t);
        if (t == '' || t == undefined) {
            app.getUserInfo(function (t) {
                console.log('app.getUserInfo');
                console.log(t),
                wx.setStorageSync("users",t),
                i.setData({
                  userInfo: t
                });
            });
        }
        
        //再次请求缓存
        t = wx.getStorageSync("users").id;


        app.util.request({
            url: "app/Running/ThreadGetUserInfo",
            cachetime: "0",
            data: {
                user_id: t
            },
            success: function(e) {
                2 == e.data.state && wx.showModal({
                    title: "提示",
                    content: "您的账号异常，请尽快联系管理员",
                    showCancel: !0,
                    cancelText: "取消",
                    confirmText: "确定",
                    success: function(e) {
                        wx.navigateBack({
                            delta: 1
                        });
                    },
                    fail: function(e) {},
                    complete: function(e) {}
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadGetSensitive",
            cachetime: "0",
            success: function(e) {
                console.log(e), e.data ? i.setData({
                    mgnr: e.data
                }) : i.setData({
                    mgnr: {
                        content: ""
                    }
                });
            }
        }), 
        
        app.util.request({
            url: "app/Running/ThreadSystem",
            cachetime: "0",
            success: function(e) {
                i.setData({
                    System: e.data
                });
            }
        }), wx.getSystemInfo({
            success: function(e) {
                console.log(e), -1 != e.system.indexOf("iOS") ? (console.log("ios"), i.setData({
                    isios: !0
                })) : console.log("andr");
            }
        });
        var a = e.info, n = e.money.split(","), o = e.type_id, s = e.type2_id, c = wx.getStorageSync("System");

 
       if(s == '' || s == 0){
         wx.showModal({
           title: "提示",
           content: "必须选择子分类",
           showCancel: !0,
           cancelText: "取消",
           confirmText: "确定",
           success: function (e) {
             wx.navigateTo({
               url: "fabu"
             });
           },
           fail: function (e) { },
           complete: function (e) { }
         });
        }



        wx.setNavigationBarTitle({
            title: a
        });


        wx.getStorageSync("uniacid");
        console.log(wx.getStorageSync("users"), n), i.setData({
            type_id: o,
            type2_id: s,
            info: a,
            procedures: Number(c.hb_sxf),
            money: n[0],
            moneyarr: n,
            url: wx.getStorageSync("url2"),
            url1: wx.getStorageSync("url"),
            name: wx.getStorageSync("users").name
        }), wx.getLocation({
            type: "wgs84",
            success: function(e) {
                var t = e.latitude, a = e.longitude, n = t + "," + a;
                app.util.request({
                    url: "app/Running/ThreadMap",
                    cachetime: "0",
                    data: {
                        op: n
                    },
                    success: function(e) {
                        i.setData({
                            lat: t,
                            lng: a,
                            address: e.data.result.address
                        });
                    }
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadTop",
            cachetime: "0",
            success: function(e) {
                console.log("top", e.data);
                var t = e.data;
                for (var a in t) 1 == t[a].type ? t[a].array = "置顶一天（收费" : 2 == t[a].type ? t[a].array = "置顶一周（收费" : 3 == t[a].type && (t[a].array = "置顶一月（收费");
                var n = [];
                t.map(function(e) {
                    var t;
                    t = e.array, n.push(t);
                }), n.push("取消置顶"), i.setData({
                    stock: n,
                    stick: t
                });
            }
        }), app.util.request({
            url: "app/Running/ThreadLabel",
            cachetime: "0",
            data: {
                type2_id: s
            },
            success: function(e) {
                for (var t in e.data) e.data[t].click_class = "selected1";
                i.setData({
                    label: e.data
                });
            }
        });
    },


    selected: function(e) {
        var t = this.data.countryIndex, a = e.currentTarget.id, n = this.data.stick;
        this.setData({
            zdindex: a,
            stick_info: n[a].array,
            money1: 0 == t ? n[a].money : n[a].money2,
            type: n[a].type,
            checked: !1,
            stick_none: !0
        }), console.log(t, this.data.money1);
    },


    add: function(e) {
        var i = this;
        wx.chooseLocation({
            type: "wgs84",
            success: function(e) {
                var t = e.latitude, a = e.longitude, n = (e.speed, e.accuracy, e.latitude + "," + e.longitude);
                i.setData({
                    address: e.address,
                    lat: t,
                    lng: a,
                    coordinates: n
                });
            }
        });
    },


    label: function(e) {
        var t = this.data.label, a = e.currentTarget.dataset.inde;
        "selected1" == t[a].click_class ? t[a].click_class = "selected2" : "selected2" == t[a].click_class && (t[a].click_class = "selected1"), 
        this.setData({
            label: t
        });
    },
  


    imgArray1: function(e) {
        var a = this, n = wx.getStorageSync("uniacid"), t = 9 - _imgArray.length;
        0 < t && t <= 9 ? wx.chooseImage({
            count: t,
            sizeType: [ "compressed" ],
            sourceType: [ "album", "camera" ],
            success: function(e) {
                wx.showToast({
                    icon: "loading",
                    title: "正在上传"
                });
                var t = e.tempFilePaths;
                a.uploadimg({
                  url: a.data.url + "/app/app/uploadify",
                    path: t
                });
            }
        }) : wx.showModal({
            title: "上传提示",
            content: "最多上传9张图片",
            showCancel: !0,
            cancelText: "取消",
            confirmText: "确定",
            success: function(e) {},
            fail: function(e) {},
            complete: function(e) {}
        });
    },


    uploadimg: function(e) {
        var t = this, a = e.i ? e.i : 0, n = e.success ? e.success : 0, i = e.fail ? e.fail : 0;
        wx.uploadFile({
            url: e.url,
            filePath: e.path[a],
            name: "upfile",
            formData: null,
            success: function(e) {
                console.log(e), "" != e.data ? (n++, _imgArray.push(e.data), t.setData({
                    imgArray1: _imgArray
                })) : wx.showToast({
                    icon: "loading",
                    title: "请重试"
                });
            },
            fail: function(e) {
                i++;
            },
            complete: function() {
                ++a == e.path.length ? (t.setData({
                    images: e.path
                }), wx.hideToast()) : (e.i = a, e.success = n, e.fail = i, t.uploadimg(e));
            }
        });
    },

    delete: function(e) {
        Array.prototype.indexOf = function(e) {
            for (var t = 0; t < this.length; t++) if (this[t] == e) return t;
            return -1;
        }, Array.prototype.remove = function(e) {
            var t = this.indexOf(e);
            -1 < t && this.splice(t, 1);
        };
        var t = e.currentTarget.dataset.inde;
        _imgArray.remove(_imgArray[t]), this.setData({
            imgArray1: _imgArray
        });
    },

    switch1Change: function(e) {
        console.log(e.detail.value), e.detail.value || this.setData({
            stick_none: !1,
            money1: 0,
            type: 0
        }), this.setData({
            checked: e.detail.value
        });
    },
    switch2Change: function(e) {
        this.setData({
            checked_welfare: e.detail.value
        });
    },
    switch3Change: function(e) {
        this.setData({
            checked_average: e.detail.value
        });
    },
    switch4Change: function(e) {
        this.setData({
            checked_password: e.detail.value
        });
    },


    formSubmit: function(e) {
        if (console.log("这是保存formid2"), console.log(e), app.util.request({
            url: "app/Running/SaveFormid",
            cachetime: "0",
            data: {
                user_id: wx.getStorageSync("users").id,
                form_id: e.detail.formId,
                openid: wx.getStorageSync("openid")
            },
            success: function(e) {}
        }), this.data.radiochecked) {
            var t = this, a = 0 == t.data.countryIndex ? wx.getStorageSync("city") : "";
            console.log("city", a);
            var n = t.data.num + 1;
            t.setData({
                num: n
            });
            var i = t.data.money1;
            if ("1" == t.data.System.is_tzdz) var o = e.detail.value.dzaddress; else o = "";
            console.log(o);
            var s = t.data.procedures;
            if (null == t.data.type) var c = 0; else c = t.data.type;
            if (null == i) i = 0; else i = t.data.money1;
            var l = t.data.label, r = [];
            for (var d in l) "selected2" == l[d].click_class && r.push(l[d]);
            var u = [];
            r.map(function(e) {
                var t = {};
                t.label_id = e.id, u.push(t);
            });
            var m = wx.getStorageSync("openid"), y = (e.detail.formId, e.detail.value.content.replace("\n", "↵")), p = this.data.mgnr.content.split(",");
            if (console.log(p, y), "" != this.data.mgnr.content) for (var h = 0; h < p.length; h++) if (-1 != y.indexOf(p[h])) return console.log(y.indexOf(p[h])), 
            void wx.showModal({
                title: "温馨提示",
                content: "您发布的内容在第" + (y.indexOf(p[h]) + 1) + "个字符出现违规敏感词汇,请修改后提交"
            });
            var g = e.detail.value.name, f = e.detail.value.tel;
            console.log(f);
            var w = t.data.lunbo;
            null != w && 0 != w.length || (w = "");
            t.data.url, wx.getStorageSync("uniacid");
            var x = t.data.type2_id, _ = t.data.type_id, v = Number(t.data.money) + Number(i), S = v, b = wx.getStorageSync("users").id;
            console.log(b);
            var k = "", D = t.data.checked_welfare, T = t.data.checked_password, A = t.data.checked_average, N = 0, C = "", q = "", z = 0, P = 0, I = new RegExp("^[一-龥]+$"), M = 0;
            if (1 == D) {
                if (0 == A) {
                    P = 1, N = Number(e.detail.value.welfare_money), q = Number(e.detail.value.welfare_share);
                    var L = N / q;
                    M = N + s / 100 * N, v += Number(M.toFixed(2));
                } else {
                    P = 2, N = Number(e.detail.value.welfare_money), q = Number(e.detail.value.welfare_share);
                    L = 1;
                    M = N * q + N * q * (s / 100), v += Number(M.toFixed(2));
                }
                1 == T ? (C = e.detail.value.welfare_pass, z = 2) : z = 1;
            } else v = v;
            if ("" == y ? k = "内容不能为空" : 540 <= y.length ? k = "内容超出了" : "" == g ? k = "姓名不能为空"  : 1 == D && ("" == N ? k = t.data.System.hb_name + "金额不能为空" : !t.data.checked_average && N < 1 ? k = t.data.System.hb_name + "金额不能小于1元" : "" == q ? k = t.data.System.hb_name + "个数不能为空" : L < .1 ? k = t.data.System.hb_name + "份数过大，请合理设置" : t.data.checked_average && N < .1 ? k = "单个" + t.data.System.hb_name + "最小金额不能小于0.1元" : 1 == T && ("" == C ? k = "口令不能为空" : I.test(C) || (k = "口令只能输入汉字"))), 
            "" != k) wx.showModal({
                title: "提示",
                content: k,
                success: function(e) {},
                fail: function(e) {},
                complete: function(e) {}
            }); else {
                v = v;
                s = wx.getStorageSync("System");

                //获取学校
                var homeData = wx.getStorageSync("homeData");
                var schoolId = homeData.schoolId;
                console.log(schoolId);

                if (0 == _imgArray.length) var O = ""; else O = _imgArray.join(",");

                console.log('走这里V是价格哦' +v);

                if (v <= 0) t.setData({
                    disabled: !0
                }), app.util.request({
                    url: "app/Running/ThreadPosting",
                    cachetime: "0",
                    data: {
                        schoolId: schoolId,
                        lat: t.data.lat,
                        lng: t.data.lng,
                        details: y,
                        img: O,
                        user_id: b,
                        user_name: g,
                        user_tel: f,
                        type2_id: x,
                        type_id: _,
                        money: v,
                        type: c,
                        sz: u,
                        address: o,
                        hb_money: N,
                        hb_keyword: C,
                        hb_num: q,
                        hb_type: z,
                        hb_random: P,
                        city_id: s.city_id,
                        cityname: a
                    },


                    success: function(e) {
                        wx.showToast({
                            title: "发布成功",
                            mask: !0
                        }), setTimeout(function() {
                            wx.reLaunch({
                                url: "index",
                                success: function(e) {},
                                fail: function(e) {},
                                complete: function(e) {}
                            });
                        }, 1e3);
                    }
                }); else {



                    console.log('支付'+t.data.System);
                    console.log(t.data.System);

                    if (t.data.isios && "1" == t.data.System.is_pgzf) return void wx.showModal({
                        title: "暂不支持",
                        content: "十分抱歉，由于相关规范，您暂时无法进行支付",
                        showCancel: !1,
                        confirmText: "好的",
                        confirmColor: "#666"
                    });
                    t.setData({
                        disabled: !0
                    }), console.log(t.data.money, t.data.money1, N, v, S, Number(t.data.money) + Number(t.data.money1)), 
                    app.util.request({
                        url: "app/Running/threadPay",
                        cachetime: "0",
                        data: {
                            openid: m,
                            type: 'thread',
                            user_id: b,
                            money: v
                        },
                        success: function(h) {
                            wx.requestPayment({
                                timeStamp: h.data.timeStamp,
                                nonceStr: h.data.nonceStr,
                                package: h.data.package,
                                signType: h.data.signType,
                                paySign: h.data.paySign,
                                success: function(e) {
                                    app.util.request({
                                        url: "app/Running/ThreadPosting",
                                        cachetime: "0",
                                        data: {
                                            schoolId: schoolId,
                                            log_id: h.data.log_id,
                                            lat: t.data.lat,
                                            lng: t.data.lng,
                                            details: y,
                                            img: O,
                                            user_id: b,
                                            user_name: g,
                                            user_tel: f,
                                            type2_id: x,
                                            type_id: _,
                                            money: v,
                                            type: c,
                                            sz: u,
                                            address: o,
                                            hb_money: N,
                                            hb_keyword: C,
                                            hb_num: q,
                                            hb_type: z,
                                            hb_random: P,
                                            city_id: s.city_id,
                                            cityname: a
                                        },
                                        success: function(e) {
                                            0 == S || null == S || "" == S || app.util.request({
                                                url: "app/Running/SaveTzPayLog",
                                                cachetime: "0",
                                                data: {
                                                    tz_id: e.data,
                                                    money: v,
                                                    money1: t.data.money,
                                                    money2: t.data.money1,
                                                    money3: N
                                                },
                                                success: function(e) {}
                                            }), wx.showToast({
                                                title: "发布成功",
                                                mask: !0
                                            }), setTimeout(function() {
                                                wx.reLaunch({
                                                    url: "index",
                                                    success: function(e) {},
                                                    fail: function(e) {},
                                                    complete: function(e) {}
                                                });
                                            }, 1e3);
                                        }
                                    });
                                },
                                fail: function(e) {
                                    wx.showToast({
                                        title: "支付失败",
                                        duration: 1e3
                                    });
                                },
                                complete: function(e) {
                                    console.log(e), "requestPayment:fail cancel" == e.errMsg && (wx.showToast({
                                        title: "取消支付",
                                        icon: "loading",
                                        duration: 1e3
                                    }), t.setData({
                                        disabled: !1
                                    }));
                                }
                            });
                        }
                    });
                }
            }
        } else wx.showModal({
            title: "提示",
            content: "请阅读并同意《发布须知》"
        });
    },
    cancel: function(e) {
        this.setData({
            money1: 0,
            type: 0,
            checked: !1,
            stick_none: !1,
            iszdchecked: !1
        });
    },
    onReady: function() {},
    onShow: function() {},
    onHide: function() {},
    onUnload: function() {
        console.log(this.data), _imgArray.splice(0, _imgArray.length);
    },
    onPullDownRefresh: function() {},
    onReachBottom: function() {}
});