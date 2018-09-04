var util = require('../../utils/util.js');
var app = getApp();

Page({
    data: {
    
    },

    onLoad: function (options) {
        var that = this;
        wx.showLoading({
            title: '加载中'
        });
        util.http(app.globalData.tzBase + 'api/radio/info', 'GET',{},function (data) {
            console.log(data);
            wx.hideLoading();
            that.setData({ 
                info: data.data
            }); 
        });
    },

    // 跳转详情页
    toListen: function (e) {
        wx.navigateTo({
            url: '../listen/listen?id=' + e.currentTarget.dataset.id + '&type=0&way=0' // way=0表示首页进入，way=1表示个人页进入
        });
    },

    // 跳转分类页
    toClassify: function (e) {
        wx.navigateTo({
            url: '../classify/classify?id=' + e.currentTarget.dataset.id
        });
    },

    onShareAppMessage: function () {
        return {
            title: '嘉仔',
            path: '/pages/radio/radio',
            success: function (res) {
            // 转发成功
            console.log(res);
            },
            fail: function (res) {
            // 转发失败
            }
        }
    }
})