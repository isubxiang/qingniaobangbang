var n = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(n) {
    return typeof n;
} : function(n) {
    return n && "function" == typeof Symbol && n.constructor === Symbol && n !== Symbol.prototype ? "symbol" : typeof n;
};

!function(o, e) {
    "object" === ("undefined" == typeof module ? "undefined" : n(module)) && module.exports ? module.exports = e() : o.onfire = e();
}("undefined" != typeof window ? window : void 0, function() {
    function o(o, e, t, i) {
        if ((void 0 === o ? "undefined" : n(o)) !== f || (void 0 === e ? "undefined" : n(e)) !== c) throw new Error("args: " + f + ", " + c);
        return d(r, o) || (r[o] = {}), r[o][++u] = [ e, t, i ], [ o, u ];
    }
    function e(n, o) {
        for (var e in n) d(n, e) && o(e, n[e]);
    }
    function t(n, o) {
        d(r, n) && e(r[n], function(e, t) {
            t[0].apply(t[2], o), t[1] && delete r[n][e];
        });
    }
    function i(o) {
        if (!(arguments.length > 1)) {
            var t, u, l = !1, y = void 0 === o ? "undefined" : n(o);
            return y === f ? !!d(r, o) && (delete r[o], !0) : "object" === y ? (t = o[0], u = o[1], 
            !(!d(r, t) || !d(r[t], u)) && (delete r[t][u], !0)) : y !== c || (e(r, function(n, t) {
                e(t, function(e, t) {
                    t[0] === o && (delete r[n][e], l = !0);
                });
            }), l);
        }
        for (var p in arguments) i(arguments[p]);
    }
    var r = {}, u = 0, f = "string", c = "function", d = Function.call.bind(Object.hasOwnProperty), l = Function.call.bind(Array.prototype.slice);
    return {
        on: function(n, e, t) {
            return o(n, e, 0, t);
        },
        one: function(n, e, t) {
            return o(n, e, 1, t);
        },
        un: i,
        fire: function(n) {
            var o = l(arguments, 1);
            setTimeout(function() {
                t(n, o);
            });
        },
        fireSync: function(n) {
            t(n, l(arguments, 1));
        },
        clear: function() {
            r = {};
        }
    };
});