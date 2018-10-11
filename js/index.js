var code = getUrlParam('code')
var timeText = "0"
var time = 0
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
        var startTime = data.content.startTime
        var endTime = data.content.endTime
        if (startTime - nowTime > 0) {
          time = startTime-nowTime
          // timeText = formateTime(time)
        } else {
          $('.clock-wrap').hide()
        }
      })
    }
})
