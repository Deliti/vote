$(function () {
    var type = getUrlParam('type') || 1;
    initData()
    initAction()

    function initData () {
        getScoreList()
    }

    function initAction () {
        
    }

    // 私有函数
    function getScoreList () {
        var params = {
            type: type
        }
        ajaxRequest('getScore', params, function (data) {
            if (data.result != 0) {
                console.log(data.desc || "未知错误")
                return false
            }
            
        })
    }
})