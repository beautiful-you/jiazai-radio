var util = require('../../utils/util.js');
var app = getApp();

Page({
    data: {
    
    },

    onLoad: function (options) {
        var id = options.id;
        this.setData({
            type: id
        })
        var that = this;
        wx.showLoading({
            title: '加载中'
        });
        util.http(app.globalData.tzBase + 'api/radio/column/report', 'GET', {id: id}, function (res) {
            var data = res.data;
            wx.setNavigationBarTitle({
                title: data.navigation.column_navigation
            });
            that.setData({
                radio: data.reports
            });
            wx.hideLoading();
        });
    },

    // 跳转详情页
    toListen: function (e) {
        wx.navigateTo({
            url: '../listen/listen?id=' + e.currentTarget.dataset.id + '&type=' + this.data.type + '&way=0'
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