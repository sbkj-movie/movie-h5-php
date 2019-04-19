is_weixin_qq();
//browserRedirect();
//判断是否是手机
function browserRedirect() {
    var sUserAgent = navigator.userAgent.toLowerCase();
    var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
    var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
    var bIsMidp = sUserAgent.match(/midp/i) == "midp";
    var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
    var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
    var bIsAndroid = sUserAgent.match(/android/i) == "android";
    var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
    var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
    if (!(bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM)) {
       window.location.href ="/error.html"
       
    }
}

//判断是否是微信或QQ
function is_weixin_qq() {
    var ua = navigator.userAgent.toLowerCase();
    var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight; //兼容IOS，不需要的可以去掉
    if (ua.match(/MicroMessenger\/[0-9]/i)) {
        document.write("<div id='wx-tip' class='weixin-tip'><p><img src='/images/live_weixin.png' /></p></div>");
        var tip = document.getElementById('wx-tip');
        tip.onclick = function () {
            tip.style.display = 'none';
        }
    } else if (ua.match(/QQ\/[0-9]/i)) {
        document.write("<div id='wx-tip' class='weixin-tip'><p><img src='/images/live_weixin.png' /></p></div>");
        var tip = document.getElementById('wx-tip');
        tip.onclick = function () {
            tip.style.display = 'none';
        }
    } else {
        return false;
    }
}

var domain = document.domain;
var site = "http://" + domain+"/"
var globalData ={
    siteadd: "/",
    apiurl: '/index.php/App'
}
var rootDocment = globalData.apiurl;

//获取URL参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return 0;
}

//Get请求
function axget(url, params = {}) {
    return new Promise((resolve, reject) => {
        axios.get(rootDocment+url, {
            params: params
        })
        .then(response => {
                resolve(response.data);
        })
        .catch(err => {
                reject(err)
        })
    })
}
//Post
function axpost(url, data = {}) {
    return new Promise((resolve, reject) => {
        axios.post(rootDocment +url, data)
            .then(response => {
                resolve(response.data);
            }), err => {
                reject(err)
            }
    })
}
// 去前后空格  
function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

//判断是否为空
function isNull(str) {
    if (str == null || str == "" || str == undefined || str == "null") {
        return true;
    } else {
        return false;
    }
};