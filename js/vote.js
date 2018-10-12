$(function() {
  var type = getUrlParam('type') || 0;
  var voteType =  getUrlParam('voteType') || 0;
  var voteId = ""
  initData()
  initAction()

  function initData() {
    getScoreList()
  }

  function initAction() {
    $('.vote-wrap').on('click', '.vote-item', function () {
      var userInfo = localStorage['userInfo'] || "{}";
      userInfo = JSON.parse(userInfo);
      if (userInfo.vote_id) {
        return false;
      }
      var pId = $(this).attr('data-id')
      var pName = $(this).find('.head-wrap').text()
      voteId = pId
      $('.vote-item').removeClass('active')
      $(this).addClass('active')
      $('.submit-wrap').show().find('.mark').text(pName)
    })

    $('#submit').on('click', function () {
      if (voteId == '') {
        return false
      }
      var params = {
        voteId: voteId,
        voteType: voteType
      }
      // todo 投票接口
      ajaxRequest('voteProgram', params, function(data) {
        if (data.result != 0) {
          console.log(data.desc || "未知错误")
          return false
        }
        ajaxRequest('getUserInfo', {}, function (resp) {
          if (resp.result != 0) {
            console.log(resp.desc || "未知错误")
            return false
          }
          localStorage['userInfo'] = JSON.stringify(resp.content);
          location.href = './result.html'
        })
      })
    })
  }

  // 私有函数
  function getScoreList() {
    var params = {
      type: type,
      voteType: voteType
    }
    ajaxRequest('getScore', params, function(data) {
      if (data.result != 0) {
        console.log(data.desc || "未知错误")
        return false
      }
      var list = data.content.list
      var totalCount = data.content.totalCount
      var html = ''
      $.each(list, function (index, item) {
        html += '<section class="vote-item" data-id="' + item.p_id + '"><pre>' + index + '</pre><div class="head-wrap">' + item.name + '</div><div class="process-wrap"><div class="process" style="right:' + ((totalCount - item.count)/totalCount).toFixed(2)*100 + '%;"></div></div><em>' + item.count + '票</em></section>'
      })
      html += '<div class="block"></div>'
      $('.vote-wrap').empty()
      $('.vote-wrap').html(html)
    })
  }

  function getSysTime () {
    ajaxRequest('getSysTime', {type: 'vote'}, function(data) {
      if (data.result != 0) {
        console.log(data.desc || "未知错误")
        return false
      }
      console.log(data)
    })
  }
})
