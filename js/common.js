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
            callBack(parseData);
        }
    });
};

var ajaxSyncRequest = function(cmdName, parment, callBack) {
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

var cssEl = document.createElement('style');
document.documentElement.firstElementChild.appendChild(cssEl);
var setPxPerRem = function () {
	var dpr = 1;
	var pxPerRem = document.documentElement.clientWidth*dpr/100;
	cssEl.innerHTML = 'html{font-size:'+ pxPerRem +'px!important;}';
}
setPxPerRem();