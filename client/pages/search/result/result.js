// pages/index/index/index.js
import {
  wxRequest,
  checkNetwork,
  urls,
  showModal,
  userLogin,
  validateURL,
  getDateVisible,
  showToast
} from '../../../libs/wext/wext.js';

import {
  getResultList,
} from '../../../utils/common.js';

import { Tab, extend } from '../../../style/zan-ui/index.js';

Page({
  data: {
    articles: [],
    selectedId: '',
    selectedTabID: 0,
    scroll: false,
    height: 45,
    scrolling: false,  
    currentPage:1,
    searchValue:"",
  },
  onLoad: function (options) {
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          clientHeight: res.windowHeight
        });
      }
    });
    wx.request({
      url: "https://uckendo.com/Article/search?searchValue=" + options.searchValue,
      method: 'GET',
      header: {
        //设置参数内容类型为x-www-form-urlencoded
        'content-type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      },
      success: function (res) {
        that.setData({
          articles: res.data,
          searchValue: options.searchValue
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () { },


  /**
   * 页面上拉触底事件的处理函数
   */
  getNextPage: function () {
    var that = this;
    that.setData({ scrolling: true });
    let url = "/Article/search?searchValue=" + this.data.searchValue;
    var newCurrentPage = this.data.currentPage + 1;
    wxRequest({
      url: urls.host + url + "&page=" + newCurrentPage,
      success: (res) => {
        var newData = res.data;
        that.setData({articles:newData });
        that.setData({currentPage:newCurrentPage });
        setTimeout(function () { wx.hideLoading(); }, 1000);
      },
      fail: function (res) {
        console.log(res);
        setTimeout(function () { wx.hideLoading(); }, 1000);
      },
      complete: function (res) {
        that.setData({ scrolling: false });
      },
    });
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () { },
  refreshCurrentPage: function () {
    wx.showLoading({
      title: '正在刷新',
    })
    this.refresh({ singleTabRefresh: true });
    wx.stopPullDownRefresh();
  },
});