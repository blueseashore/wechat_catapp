import {
    wxRequest,
    checkNetwork,
    urls,
    showModal
} from '../../../libs/wext/wext.js';

import {
    getMineList
} from '../../../utils/common.js';
Page({
  data: {
    searchValue: ''
  },
  //获取用户输入的内容
  searcheValueInput: function (e) {
    this.setData({
      searchValue: e.detail.value
    })
  },

  suo: function (e) {
    wx.navigateTo({
      url: '../result/result?searchValue=' + this.data.searchValue,
    })
  }
})