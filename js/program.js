$(function() {
  var type = getUrlParam('type') || 0;
  var voteType =  getUrlParam('voteType') || 0;
  console.log(type)
  initData()
  initAction()
  setShare()
  function initData() {
    getSysTime()
  }

  function initAction() {
    $("body").on("touchstart", function(e) {
      startX = e.originalEvent.changedTouches[0].pageX,
        startY = e.originalEvent.changedTouches[0].pageY;
    });
    $("body").on("touchend", function(e) {
      moveEndX = e.originalEvent.changedTouches[0].pageX,
        moveEndY = e.originalEvent.changedTouches[0].pageY,
        X = moveEndX - startX,
        Y = moveEndY - startY;
      //上滑
      console.log('y', Y);
      if (Y < -100) {
        location.replace('./vote.html?type=' + type + '&voteType=' + voteType)
      }
    });
  }

  function getSysTime () {
    ajaxRequest('getSysTime', {type: 'sign'}, function(data) {
      if (data.result != 0) {
        alert(data.desc || "未知错误")
        return false
      }
      var nowTime = data.content.nowTime
      var endTime = data.content.endTime
      var date = formateTime(endTime - nowTime)
      if (nowTime - endTime > 0){
        $('.info-title').hide()
      } else {
        $('.info-clock').text(date.day+'天')
      }
    })
  }
})
