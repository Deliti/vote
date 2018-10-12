var code = getUrlParam('code')
var timeText = "0"
var time = 0
var timeInterval = null
var startTime
$(function () {
    initData()
    initAction()

    function initData () {
      var params = {
        code: code
      }
      ajaxRequest('login', params, function (data) {
        if (data.result != 0) {
          console.log(data.desc || "未知错误")
          return false
        }
        localStorage['userInfo'] = JSON.stringify(data.content);
        getSysTime()
        $.get('../images/rule.jpeg');
      })
    }

    function initAction () {

    }

    function getSysTime () {
      ajaxRequest('getSysTime', {type: 'vote'}, function(data) {
        if (data.result != 0) {
          console.log(data.desc || "未知错误")
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
