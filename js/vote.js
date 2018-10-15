$(function() {
  var type = getUrlParam('type') || 0;
  var voteType =  getUrlParam('voteType') || 0;
  var voteId = ""
  initData()
  initAction()
  setShare()
  function initData() {
    var wxInfo = localStorage['wxInfo'] || "{}";
    wxInfo = JSON.parse(wxInfo)
    if (wxInfo.subscribe != 1) {
      location.replace('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3d87ebb7df88b56c&redirect_uri=http%3A%2F%2Fzhgbdstxmjj.yilianservice.com%2Fvote%2Fhtml%2Findex.html&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect')
    }
    getVoteInfo()
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
      ajaxRequest('voteProgram', params, function(data) {
        if (data.result != 0) {
          alert(data.desc || "未知错误")
          return false
        }
        getScoreList()
        ajaxRequest('getUserInfo', {}, function (resp) {
          if (resp.result != 0) {
            alert(resp.desc || "未知错误")
            return false
          }
          $('.submit-wrap').hide();
          localStorage['userInfo'] = JSON.stringify(resp.content);
          // location.href = './result.html'
          alert("投票成功")
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
        alert(data.desc || "未知错误")
        return false
      }
      var list = data.content.list
      var totalCount = data.content.totalCount
      var html = ''
      $.each(list, function (index, item) {
        html += '<section class="vote-item" data-id="' + item.p_id + '"><pre>' + (index+1) + '</pre><div class="head-wrap">' + item.name + '</div><div class="process-wrap"><div class="process" style="right:' + ((totalCount - item.count)/totalCount).toFixed(2)*100 + '%;"></div></div><em>' + item.count + '票</em></section>'
      })
      html += '<div class="block"></div>'
      $('.vote-wrap').empty()
      $('.vote-wrap').html(html)
    })
  }
})

function getVoteInfo () {
  ajaxRequest('getUserInfo', {}, function(data) {
    if (data.result != 0) {
      alert(data.desc || "未知错误")
      return false
    }
    localStorage['userInfo'] = JSON.stringify(data.content);
  })
}
