function e(e) {
    return e && e.__esModule ? e : {
        default: e
    };
}

function t(e, t, a) {
    return t in e ? Object.defineProperty(e, t, {
        value: a,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[t] = a, e;
}

function a(e) {
    var t = this, a = e.target.dataset.src, i = e.target.dataset.from;
    void 0 !== i && i.length > 0 && (void 0 !== t.data[i] ? wx.previewImage({
        current: a,
        urls: t.data[i].imageUrls
    }) : wx.previewImage({
        current: a,
        urls: [ a ]
    }));
}

function i(e) {
    var t = this, a = e.target.dataset.from, i = e.target.dataset.idx;
    void 0 !== a && a.length > 0 && r(e, i, t, a);
}

function r(e, a, i, r) {
    var d, o = i.data[r];
    if (o && 0 != o.images.length) {
        var l = o.images, s = n(e.detail.width, e.detail.height, i, r), g = l[a].index, m = "" + r, v = !0, h = !1, u = void 0;
        try {
            for (var w, f = g.split(".")[Symbol.iterator](); !(v = (w = f.next()).done); v = !0) m += ".nodes[" + w.value + "]";
        } catch (e) {
            h = !0, u = e;
        } finally {
            try {
                !v && f.return && f.return();
            } finally {
                if (h) throw u;
            }
        }
        var c = m + ".width", x = m + ".height";
        i.setData((d = {}, t(d, c, s.imageWidth), t(d, x, s.imageheight), d));
    }
}

function n(e, t, a, i) {
    var r = 0, n = 0, d = 0, o = {}, g = a.data[i].view.imagePadding;
    return r = l - 2 * g, s, e > r ? (d = (n = r) * t / e, o.imageWidth = n, o.imageheight = d) : (o.imageWidth = e, 
    o.imageheight = t), o;
}

var d = e(require("./showdown.js")), o = e(require("./html2json.js")), l = 0, s = 0;

wx.getSystemInfo({
    success: function(e) {
        l = e.windowWidth, s = e.windowHeight;
    }
}), module.exports = {
    wxParse: function() {
        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "wxParseData", t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "html", r = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : '<div class="color:red;">数据不能为空</div>', n = arguments[3], l = arguments[4], s = n, g = {};
        if ("html" == t) g = o.default.html2json(r, e); else if ("md" == t || "markdown" == t) {
            var m = new d.default.Converter().makeHtml(r);
            g = o.default.html2json(m, e);
        }
        g.view = {}, g.view.imagePadding = 0, void 0 !== l && (g.view.imagePadding = l);
        var v = {};
        v[e] = g, s.setData(v), s.wxParseImgLoad = i, s.wxParseImgTap = a;
    },
    wxParseReturn: function() {
        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "wxParseData", t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "html", a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : '<div class="color:red;">数据不能为空</div>', i = (arguments[3], 
        arguments[4]), r = {};
        if ("html" == t) r = o.default.html2json(a, e); else if ("md" == t || "markdown" == t) {
            var n = new d.default.Converter().makeHtml(a);
            r = o.default.html2json(n, e);
        }
        return r.view = {}, r.view.imagePadding = 0, void 0 !== i && (r.view.imagePadding = i), 
        r;
    },
    wxParseImgLoad: i,
    wxParseImgTap: a,
    wxParseTemArray: function(e, t, a, i) {
        for (var r = [], n = i.data, d = null, o = 0; o < a; o++) {
            var l = n[t + o].nodes;
            r.push(l);
        }
        e = e || "wxParseTemArray", (d = JSON.parse('{"' + e + '":""}'))[e] = r, i.setData(d);
    },
    emojisInit: function() {
        var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : "", t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : "/wxParse/emojis/", a = arguments[2];
        o.default.emojisInit(e, t, a);
    }
};