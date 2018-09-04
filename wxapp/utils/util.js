function http(url, method, data, callBack) {
  wx.request({
    url: url,
    data: data,
    method: method,
    header: {
      "Accept": "application/vnd.myapp.v2+json"
    },
    success: function (res) {
      callBack(res.data);
    },
    fail: function () {
      console.log("请确认网络正常！");
    }
  })
}
module.exports = {
  http: http
}
