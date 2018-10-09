
$(function () {
    var type = getUrlParam('type') || 1;
    console.log(type)
    initData()
    initAction()

    function initData () {

    }

    function initAction () {
        $("body").on("touchstart", function(e) {
            // 判断默认行为是否可以被禁用
            if (e.cancelable) {
                // 判断默认行为是否已经被禁用
                if (!e.defaultPrevented) {
                    e.preventDefault();
                }
            }   
            startX = e.originalEvent.changedTouches[0].pageX,
            startY = e.originalEvent.changedTouches[0].pageY;
        });
        $("body").on("touchend", function(e) {         
            // 判断默认行为是否可以被禁用
            if (e.cancelable) {
                // 判断默认行为是否已经被禁用
                if (!e.defaultPrevented) {
                    e.preventDefault();
                }
            }               
            moveEndX = e.originalEvent.changedTouches[0].pageX,
            moveEndY = e.originalEvent.changedTouches[0].pageY,
            X = moveEndX - startX,
            Y = moveEndY - startY;
            //上滑
            console.log('y',Y );
            if ( Y < -100 ) {
                location.href = './vote.html?type='+type  
            }
        });
    }
})