$(function() {
  var type = getUrlParam('type') || 1;
  console.log(type)
  initData()
  initAction()

  function initData() {

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
        location.replace('./vote.html?type=' + type)
      }
    });
  }
})
