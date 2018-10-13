var code = getUrlParam('code')
var timeText = "0"
var time = 0
var timeInterval = null
var startTime
$(function () {
    initData()
    initAction()
    setShare()
    
    function initData () {
      var params = {
        code: code
      }
      var wxInfo = localStorage['wxInfo'] || "{}";
      wxInfo = JSON.parse(wxInfo)
      if (wxInfo.subscribe != 1) {
        ajaxRequest('login', params, function (data) {
          if (data.result != 0) {
            alert(data.desc || "未知错误")
            return false
          }
          if (data.content.wxUserInfo) {
            var wxInfo = data.content.wxUserInfo
            localStorage['userInfo'] = JSON.stringify(data.content.recordInfo);
            if (wxInfo.subscribe != 1) { // 跳转关注公众号
              // 这里会有偏差，所以跳转前重新确认一下
              location.replace('https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU0NTE3Njg4OA==#wechat_redirect')
            } else {
              localStorage['wxInfo'] = JSON.stringify(data.content.wxUserInfo);
            }
          } else {
            location.replace('https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU0NTE3Njg4OA==#wechat_redirect')
          }
          $.get('../images/rule.jpeg');
        })
      }
      getSysTime()
      
    }

    function initAction () {

    }

    function getSysTime () {
      ajaxRequest('getSysTime', {type: 'vote'}, function(data) {
        if (data.result != 0) {
          alert(data.desc || "未知错误")
          return false
        }
        var nowTime = data.content.nowTime
        startTime = data.content.startTime
        var endTime = data.content.endTime
        if (startTime - nowTime > 0) {
          time = startTime-nowTime
          interval(time)
        } else if (nowTime - endTime > 0){
          $('.clock-wrap .clock-title').text("投票已结束")
          $('.clock-wrap .clock-time').hide()
        } else {
          $('.clock-wrap .clock-title').text("投票进行中")
          $('.clock-wrap .clock-time').hide()
        }
      })
    }

    function getTimeText (time, flag) {
      return time>0?time+flag:''
    }

    function interval (time) {
      var date = formateTime(time)
      var dateText = getTimeText(date.day, '天')+getTimeText(date.hour, '时')+getTimeText(date.minute, '分')+getTimeText(date.second, '秒')
      $('.clock-wrap .clock-time').text(dateText)
      clearTimeout(timeInterval)
      --time
      timeInterval = setTimeout(function () {
        interval(time)
      }, 1000)
    }
})
