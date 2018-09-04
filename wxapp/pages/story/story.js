var arr = ['#fec100', '#58a051', '#00dbe2', '#af80cb', '#ffa09f', '#596efe', '#85e300', '#ff77bf'];
var util = require('../../utils/util.js');
var app = getApp();
var that;

Page({

    data: {
        topColor: '#fec100',
        play: false, // 当前播放状态
        audioUrl: '', // 音频路径
        playStory: {}, // 播放故事
        timeAdd: 0, // 播放时长
        story: [] // 全部故事
    },

    onLoad: function (options) {
        that = this;
        wx.showLoading({
            title: '加载中'
        });
        util.http(app.globalData.tzBase + 'api/radio/story/get', 'GET', {}, function (data) {
            console.log(data);
            wx.hideLoading();
            var story = data.data.story;
            for (var key in story) {
                story[key]['type'] = 0;
            }
            that.setData({
                story: data.data.story,
                audioId: data.data.story[0].id,
                playStory: data.data.story[0],
                audioUrl: 'https://jiazaidata.oss-cn-shanghai.aliyuncs.com/' + data.data.story[0].story_audio
            });
        });
    },

    onShow: function () {
        this.audioPause();
    },

    onHide: function () {
        // this.audioPause();
        wx.getBackgroundAudioPlayerState({
            success: function (res) {
                var status = res.status;
                if (status == 1) {
                    that.sChangeTwo();
                }
            }
        });
    },

    // 播放音频
    audioPlay: function () {
        this.setData({
            play: true
        })
        var audioUrl = this.data.audioUrl;
        wx.playBackgroundAudio({
            dataUrl: audioUrl
        });
        this.songPlay();
        wx.onBackgroundAudioPlay(function () {
            that.songPlay();
        });
        wx.onBackgroundAudioStop(function () {
            that.sChangeTwo();
        });
    },

    // 暂停状态
    audioPause: function () {
        this.setData({
            play: false
        });
        wx.getBackgroundAudioPlayerState({
            success: function (res) {
                var status = res.status;
                if (status == 1) {
                    wx.pauseBackgroundAudio();
                }
            }
        });
    },

    // 音频处理
    songPlay: function () {
        clearInterval(timer);
        var timer = setInterval(function () {
            var timeAdd = that.data.timeAdd;
            
            wx.getBackgroundAudioPlayerState({
                success: function (res) {
                    if (res.status == 1) {
                        if (that.data.play == false) {
                            that.setData({
                                play: true
                            });
                        }
                        // TODO 音频时长处理
                        // that.setData({
                        //     songState: {
                        //         duration: that.timeToString(res.duration), // 总时长
                        //     }
                        // });
                        // timeAdd++;
                        // that.setData({
                        //     timeAdd: timeAdd
                        // });
                    } else {
                        that.setData({
                            play: false
                        });
                        clearInterval(timer);
                    }
                }
            });
        }, 1000);
    },

    // 音频时间格式
    timeToString: function (duration) {
        var str = '';
        var minute = parseInt(duration / 60) < 10
            ? ('0' + parseInt(duration / 60))
            : (parseInt(duration / 60));
        var second = duration % 60 < 10
            ? ('0' + duration % 60)
            : (duration % 60);
        str = minute + ':' + second;
        return str;
    },

    sChange: function (e) {
        var arrType = this.data.arrType;
        var id = Number(e.currentTarget.dataset.id);
        this.setData({
            audioId: id
        })
        this.sChangeTwo();
    },

    sChangeTwo: function () {
        var id = this.data.audioId;
        var story = this.data.story;
        for (var key in story) {
            if (id == story[key]['id']) {
                if (story[key]['type'] == 0) {
                    story[key]['type'] = 1;
                    that.setData({
                        playStory: story[key],
                        audioUrl: 'https://jiazaidata.oss-cn-shanghai.aliyuncs.com/' + story[key].story_audio,
                        plau: true
                    });
                    that.audioPlay();
                } else if (story[key]['type'] == 1) {
                    story[key]['type'] = 2;
                    this.setData({
                        play: false
                    });
                    that.audioPause();
                } else if (story[key]['type'] == 2) {
                    story[key]['type'] = 1;
                    this.setData({
                        play: true
                    });
                    that.audioPlay();
                }
                that.setTopColor(story[key].background_color);
            } else {
                story[key]['type'] = 0;
            }
        }
        this.setData({
            story: story
        })
    },
    
    setTopColor: function (color) {
        wx.setNavigationBarColor({
            frontColor: '#ffffff',
            backgroundColor: color,
            animation: {
                duration: 1000,
                timingFunc: 'linear'
            }
        })
        this.setData({
            topColor: color
        })
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
