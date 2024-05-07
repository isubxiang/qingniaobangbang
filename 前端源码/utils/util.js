var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
    return typeof e;
} : function(e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
};

String.prototype.toUpperFirstChar = function() {
    return this.replace(/^[a-z]/, function(e) {
        return e.toUpperCase();
    });
};




var getDecimalPlace = function(e) {
    var t = "" + (e || 0);
    return t.indexOf(".") < 0 ? 0 : t.length - t.indexOf(".") - 1;
}, decimalAdd = function(e, t) {
    var a = Math.max(getDecimalPlace(e), getDecimalPlace(t));
    return (Math.round(e * Math.pow(10, a)) + Math.round(t * Math.pow(10, a))) / Math.pow(10, a);
}, decimalSubtract = function(e, t) {
    var a = Math.max(getDecimalPlace(e), getDecimalPlace(t));
    return (Math.round(e * Math.pow(10, a)) - Math.round(t * Math.pow(10, a))) / Math.pow(10, a);
}, decimalMultiply = function(e, t) {
    var a = Math.max(getDecimalPlace(e), getDecimalPlace(t)), o = "" + Math.round(e * Math.pow(10, a)) * Math.round(t * Math.pow(10, a)) / Math.pow(10, 2 * a);
    return parseFloat(o.substr(0, o.indexOf(".") + 1 + a || o.length));
}, decimalDivide = function(e, t) {
    var a = Math.max(getDecimalPlace(e), getDecimalPlace(t)), o = "" + Math.round(e * Math.pow(10, a)) / Math.round(t * Math.pow(10, a));
    return parseFloat(o.substr(0, o.indexOf(".") + 1 + a || o.length));
}, toast = function(e) {
    wx.showToast({
        icon: "none",
        title: e,
        duration: 3e3
    });
}, extend = function e(t, a) {
    if (t || (t = {}), arguments.length > 1) for (var o = 1; o < arguments.length; o++) {
        var n = arguments[o];
        for (var r in n) t[r] && "object" == _typeof(t[r]) ? e(t[r], n[r]) : t[r] = n[r];
    }
    return t;
}, clone = function(e) {
    return JSON.parse(JSON.stringify(e));
}, makeArray = function(e, t, a) {
    for (var o = [], n = 0; n < t; n += a || 1) o.push(e + n);
    return o;
}, sortArray = function(e, t) {
    var a, o, n = e.length;
    "string" == typeof t && (o = t, t = null);
    for (var r = function(e, t) {
        if (i > 0) {
            var o = e[a];
            e[t] = e[t + 1], e[t + 1] = o;
        }
    }; n > 0; ) {
        for (a = 0; a < n - 1; a++) {
            var i;
            o ? (i = e[a][o] - e[a + 1][o]) > 0 && r(e, a) : (i = function(e, a) {
                return t ? t(e, a) : e - a;
            }(e[a], e[a + 1])) > 0 && r(e, a);
        }
        n--;
    }
    return e;
}, joinArray = function(e, t, a) {
    var o = [];
    for (var n in e) o.push(a(e[n]));
    return o.join(t);
}, indexOfArray = function(e, t) {
    for (var a = 0; a < e.length; a++) if (t(e[a], a)) return a;
    return -1;
}, getTimeStamp = function(e) {
    return Date.parse(e || new Date()).toString().substr(0, 10);
}, fromTimeStamp = function(e) {
    return e += "000", e = parseInt(e), new Date(e);
}, getDate = function getDate(date) {
    if ("string" == typeof date) if (date = date.replace("T", " ").replace(/-/g, "/").split(".")[0], 
    isNaN(date - 0)) {
        var result = /^\/Date\(([\d]+(?:\+[\d]+)?)\)\/$/.exec(date);
        date = result && result.length > 0 ? eval("new Date(" + result[1] + ")") : new Date(date);
    } else date = new Date(date.match(/\d+/g)[0] - 0);
    return date;
}, formatDate = function(e, t, a) {
    if (e) {
        var o = function(e) {
            return (e = e.toString())[1] ? e : "0" + e;
        }, n = (e = getDate(e)).getFullYear(), r = e.getMonth() + 1, i = e.getDate(), s = e.getHours(), u = e.getMinutes(), l = e.getSeconds(), d = [], c = [];
        switch (t || "day") {
          case "s":
          case "second":
            c.push(l);

          case "m":
          case "minute":
            c.push(u);

          case "h":
          case "H":
          case "hour":
            c.push(s);

          case "d":
          case "day":
            d.push(i);

          case "M":
          case "month":
            d.push(r);

          case "y":
          case "Y":
          case "year":
            d.push(n);
        }
        var f = d.reverse().map(o).join("-"), p = " ";
        if (a && a.length) {
            var h = "," + a + ",";
            if (h.indexOf(",time,") < 0) {
                if (h.indexOf(",cn,") >= 0) {
                    f = "";
                    for (var g = 0; g < d.length; ++g) f += d[g] + (0 == g ? "年" : 1 == g ? "月" : "日");
                }
            } else f = "", p = "";
        }
        return f + (c.length ? p + c.reverse().map(o).join(":") : "");
    }
    return "";
}, addDate = function(e, t, a) {
    var o = new Date(e);
    if (t) switch (a) {
      case "y":
      case "Y":
        o.setFullYear(o.getFullYear() + t);
        break;

      case "M":
        o.setMonth(o.getMonth() + t);
        break;

      case "h":
      case "H":
        o.setHours(o.getHours() + t);
        break;

      case "m":
        o.setMinutes(o.getMinutes() + t);
        break;

      case "s":
        o.setSeconds(o.getSeconds() + t);
        break;

      default:
        o.setDate(o.getDate() + t);
    }
    return o;
}, buildComponent = function(e) {
    if (e && e.data) {
        e.properties || (e.properties = {});
        for (var t in e.data) if (0 != t.indexOf("_")) {
            _typeof(e.data[t]);
            e.properties[t] = {
                type: null !== e.data[t] ? e.data[t].constructor : Object,
                value: e.data[t],
                observer: function(e, t) {
                    var a = arguments[2][0], o = "_onSet" + a.toUpperFirstChar();
                    if (this[o]) this[o](e, t); else {
                        var n = {};
                        n[a] = e, this.setData(n);
                    }
                }
            };
        }
    }
    Component(e);
}, headerSessionKey = "SP-Session-Id", headerSchoolKey = "SP-School-Id", requestLimitCount = 4, uploadLimitCount = 2, downloadLimitCount = 2, requestLoadingVisible = 0, doRequest = function(e, t, a) {
    var o = getApp();
    o.globalData.disableLoading || "download" == a || (++requestLoadingVisible, setTimeout(function() {
        requestLoadingVisible > 0 && ("upload" == a ? wx.showLoading({
            title: "正在上传",
            mask: !0
        }) : wx.showNavigationBarLoading());
    }, 400));
    var n = function() {
        requestLoadingVisible > 0 && --requestLoadingVisible, requestLoadingVisible || (wx.hideLoading(), 
        wx.hideNavigationBarLoading());
    }, r = function(e) {
        return e.indexOf("://") < 0 ? o.globalData.siteUrl + e : e;
    }, i = {};
    if (o.globalData.userInfo) i[headerSessionKey] = o.globalData.userInfo.SessionId; else {
        var s = o.getSettings();
        i[headerSessionKey] = s.SessionId;
    }
    var u = null;
    if (o.globalData.schoolInfo) u = o.globalData.schoolInfo.Id; else {
        var l = o.getStorage("homeData");
        null != l && (u = l.schoolId);
    }
    if (u && (i[headerSchoolKey] = u), "upload" == a) {
        var d = t[0], c = t[1], f = t[2], p = t[3];
        e._uploadCounter = (e._uploadCounter || 0) + 1, wx.uploadFile({
            url: o.globalData.siteUrl + "/app/Running/UploadFile",
            filePath: d,
            header: i,
            name: "file",
            formData: c || {},
            success: function(t) {
                t.data && (t.data = JSON.parse(t.data)), onRequestSuccess(e, t, f, p, a), n();
            },
            fail: function(t) {
                onRequestFail(e, p, null, t && t.errMsg, a), n();
            }
        });
    } else if ("download" == a) {
        var h = t[0], f = t[1], p = t[2];
        e._downloadCounter = (e._downloadCounter || 0) + 1, wx.downloadFile({
            url: r(h),
            header: i,
            success: function(t) {
                200 === t.statusCode && wx.saveFile({
                    tempFilePath: t.tempFilePath,
                    success: function(t) {
                        onRequestSuccess(e, {
                            statusCode: 200,
                            data: t.savedFilePath
                        }, f, p, a);
                    }
                });
            },
            fail: function(t) {
                onRequestFail(e, p, null, t && t.errMsg, a);
            }
        });
    } else {
        var h = t[0], c = t[1], g = t[2], f = t[3], p = t[4];
        e._requestCounter = (e._requestCounter || 0) + 1, wx.request({
            url: r(h),
            data: c || {},
            method: g || "GET",
            header: i,
            success: function(t) {
                onRequestSuccess(e, t, f, p, a), n();
            },
            fail: function(t) {
                n(), onRequestFail(e, p, null, t && t.errMsg, a);
            }
        });
    }
}, onRequestFail = function(e, t, a, o, n) {
    var r = e["_" + (n || "request") + "Counter"], i = e["_" + (n || "request") + "Queue"];
    if (e["_" + (n || "request") + "Counter"] = (r || 0) - 1, !t || !1 !== t(a, o)) {

        if ("E03001" == a) return void getApp().login();

        if ("E03030" == a) {
          toast(o);
          setTimeout(function () {
            wx.navigateTo({
              url: "/pages/errand/_/list",
              success: function (t) {
                console.log('success', t);
              },
              fail: function (t) {
                console.log('fail', t);
              },
            });
          }, 2000);
        }


        var o = (o || "请求服务器异常") ;


        console.log('onRequestFail-e',e), 
        console.log('onRequestFail-t',t), 
        console.log('onRequestFail-a',a), 
        console.log('onRequestFail-o',o), 
        console.log('onRequestFail-n',n), 

        
        setTimeout(function() {
            toast(o);
        }, 200);
    }
    if (i && i.length) {
        var s = i.shift(0, 1);
        doRequest(e, s, n);
    }
}, onRequestSuccess = function(e, t, a, o, n) {
    var r = e["_" + (n || "request") + "Counter"], i = e["_" + (n || "request") + "Queue"];
    if (e["_" + (n || "request") + "Counter"] = (r || 0) - 1, t.statusCode >= 200 || t.statusCode < 300) {
        getApp();
        if (!0 === t.data.IsSuccess) {
            var s = JSON.parse(t.data.Data);
            if (a && a(s), i && i.length) {
                u = i.shift(0, 1);
                doRequest(e, u);
            }
        } else if (!1 === t.data.IsSuccess) onRequestFail(e, o, t.data.ErrorCode, t.data.ErrorMessage, n); else if (a && a(t.data), 
        i && i.length) {
            var u = i.shift(0, 1);
            doRequest(e, u);
        }
    } else onRequestFail(e, o, t.statusCode, null, n);
}, request = function(e, t, a, o, n) {
    var r = this;
    (r._requestCounter || 0) <= requestLimitCount ? doRequest(r, arguments) : (r._requestQueue || []).push(arguments);
}, upload = function(e, t, a, o) {
    var n = this;
    (n._uploadCounter || 0) <= uploadLimitCount ? doRequest(n, arguments, "upload") : (n._uploadQueue || []).push(arguments);
}, download = function(e, t, a) {
    var o = this;
    (o._downloadCounter || 0) <= downloadLimitCount ? doRequest(o, arguments, "download") : (o._downloadQueue || []).push(arguments);
};

module.exports = {
    decimal: {
        add: decimalAdd,
        subtract: decimalSubtract,
        multiply: decimalMultiply,
        divide: decimalDivide
    },
    toast: toast,
    getDate: getDate,
    formatDate: formatDate,
    getTimeStamp: getTimeStamp,
    fromTimeStamp: fromTimeStamp,
    addDate: addDate,
    extend: extend,
    clone: clone,
    makeArray: makeArray,
    sortArray: sortArray,
    joinArray: joinArray,
    indexOfArray: indexOfArray,
    buildComponent: buildComponent,
    request: request,
    upload: upload,
    download: download
};