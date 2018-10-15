var apiPath = '../index.php'
function Cmd(cmd, params) {
    this.cmd = cmd;
    this.params = params;
}

var ajaxRequest = function(cmdName, params, callBack) {
    var paramsData = new Cmd(cmdName, params);
    $.ajax({
        url: apiPath,  //+';JSESSIONID='+$.cookie('JSESSIONID')
        timeout: 10*60*1000,
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        data: paramsData,
        success: function(data){
            //处理 解析ajax 服务器端返回的  null对象
            var  dataStr = JSON.stringify(data);
            dataStr=dataStr.replace(new RegExp(":null","gm"),':""');
            var  parseData = $.parseJSON(dataStr) ;
            if (parseData.result == 10) {
                localStorage.clear()
                location.replace('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3d87ebb7df88b56c&redirect_uri=http%3A%2F%2Fzhgbdstxmjj.yilianservice.com%2Fvote%2Fhtml%2Findex.html&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect')
            } else {
                callBack(parseData);
            }
        }
    });
};

var ajaxSyncRequest = function(cmdName, params, callBack) {
    var paramsData = new Cmd(cmdName, params);
    $.ajax({
        url: apiPath,
        timeout: 10*60*1000,   //响应超时时间设置为10分钟
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        data: paramsData,
        async: false,
        success: function(data){
            //处理 解析ajax 服务器端返回的  null对象
            var  dataStr = JSON.stringify(data);
            dataStr=dataStr.replace(new RegExp(":null","gm"),':""');
            var  parseData = $.parseJSON(dataStr) ;
            callBack(parseData);
        }
    });
};


/**
	 * @获取URL传入的key
	 */
var getUrlParam = function(key) {
    var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)"); // 构造一个含有目标参数的正则表达式对象

    var r = window.location.search.substr(1).match(reg); // 匹配目标参数
    if (r != null)
        return unescape(r[2]);
    return null; // 返回参数值

};

function formateTime (time) {
  var date = {
    day: 0,
    hour: 0,
    minute: 0,
    second: 0
  }
  date.day = Math.floor(time / 86400);
  date.hour = Math.floor(time % 86400 / 3600);
  date.minute = Math.floor(time % 86400 % 3600 / 60);
  date.second = Math.floor(time % 86400 % 3600 % 60);
  return date
}

// var cssEl = document.createElement('style');
// document.documentElement.firstElementChild.appendChild(cssEl);
// var setPxPerRem = function () {
// 	var dpr = 1;
// 	var pxPerRem = document.documentElement.clientWidth*dpr/100;
// 	cssEl.innerHTML = 'html{font-size:'+ pxPerRem +'px!important;}';
// }
// setPxPerRem();

function setShare () {
  var url = encodeURIComponent(location.href.split('#')[0])
  ajaxRequest('getShare', {
      url: url
  }, function (data) {
    if (data.result != 0) {
        alert(data.desc || "未知错误")
        return false
    }
    var wxInfo = data.content
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: wxInfo.appId, // 必填，公众号的唯一标识
        timestamp: wxInfo.timestamp, // 必填，生成签名的时间戳
        nonceStr: wxInfo.nonceStr, // 必填，生成签名的随机串
        signature: wxInfo.signature,// 必填，签名
        jsApiList: [
            "onMenuShareTimeline",
            "onMenuShareAppMessage"
        ] // 必填，需要使用的JS接口列表
    });
    wx.ready(function(){
        wx.onMenuShareTimeline({
            title:"舞动星球给你喜欢的节目投票", // 分享标题(展示分享码)
            link:  location.href, // 分享链接
            imgUrl: "http://zhgbdstxmjj.yilianservice.com/vote/images/logo.jpeg", // 分享图标
            success: function () { 
                // 用户确认分享后执行的回调函数
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        
        //分享好友
        wx.onMenuShareAppMessage({
            title: '舞动星球给你喜欢的节目投票', // 分享标题
            desc: '舞动星球给你喜欢的节目投票！', // 分享描述
            link: location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "http://zhgbdstxmjj.yilianservice.com/vote/images/logo.jpeg", // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
            // 用户点击了分享后执行的回调函数
            }
        });
        // wx.onMenuShareAppMessage({
        //     title:"舞动星球给你喜欢的节目投票", // 分享标题(展示分享码)
        //     desc:"舞动星球给你喜欢的节目投票！", // 分享描述(展示分享码)
        //     link:  "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3d87ebb7df88b56c&redirect_uri=http%3A%2F%2Fzhgbdstxmjj.yilianservice.com%2Fvote%2Fhtml%2Findex.html&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect", // 分享链接
        //     imgUrl: "https://www.baidu.com/img/bd_logo1.png", 
        //     type: 'link', // 分享类型,music、video或link，不填默认为link
        //     success: function () { 
        //         // 用户确认分享后执行的回调函数
        //         alert('分享成功')
        //     },
        //     cancel: function () { 
        //         // 用户取消分享后执行的回调函数
        //     }
        // });
    });
  })
}