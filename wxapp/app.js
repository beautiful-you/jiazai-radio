var code = '';
App({
    onLaunch: function() {
        var that = this;
        wx.login({
        success: function (mes) {
            if (mes.code) {
                code = mes.code;
                wx.getUserInfo({
                    withCredentials: true,
                    success: function (res) {
                        wx.request({
                            url: that.globalData.tzBase + 'api/user/applet',
                            data: {
                                id: that.globalData.id,
                                code: code,
                                iv: res.iv,
                                rawData: res.rawData,
                                signature: res.signature,
                                encryptedData: res.encryptedData
                            },
                            method: 'GET',
                            header: {
                            "Accept": "application/vnd.myapp.v2+json"
                            },
                            success: function (res) {
                                console.log(res);
                                that.globalData.userInfo = res.data.data;
                                console.log(that.globalData.userInfo)
                            },
                            fail: function () {
                                console.log("请确认网络正常！");
                            }
                        })
                    },
                    fail: function (res) {
                        that.authorization();
                    }
                });
            } else {
                console.log('获取用户登录态失败！' + res.errMsg)
            }
        }
        });
    },

    authorization: function () {
        var that = this;
        wx.showModal({
            content: '请允许授权',
            showCancel: false,
            success: function (res) {
                wx.openSetting({
                    success: function (res) {
                        if (!res.authSetting['scope.userInfo']) {
                            that.authorization();
                        } else {
                            wx.getUserInfo({
                                withCredentials: true,
                                success: function (res) {
                                    wx.request({
                                        url: that.globalData.tzBase + 'api/user/applet',
                                        data: {
                                            id: that.globalData.id,
                                            code: code,
                                            iv: res.iv,
                                            rawData: res.rawData,
                                            signature: res.signature,
                                            encryptedData: res.encryptedData
                                        },
                                        method: 'GET',
                                        header: {
                                            "Accept": "application/vnd.myapp.v2+json"
                                        },
                                        success: function (res) {
                                            that.globalData.userInfo = res.data.data;
                                        },
                                        fail: function () {
                                            console.log("请确认网络正常！");
                                        }
                                    })
                                },
                                fail: function (res) {
                                    console.log(res);
                                }
                            })
                        }
                    }
                });
                // wx.getSetting({
                //     success: function (res) {
                //         if (!res.authSetting['scope.userInfo']) {
                            
                //         }
                //     }
                // });
            }
        })
    },

    globalData: {
        id : 44,
        userInfo: null,
        tzBase: 'https://mi.wx.oovmi.com/',
      fileHost: 'https://jiazaidata.oss-cn-shanghai.aliyuncs.com/'
    }
})
