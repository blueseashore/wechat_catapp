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
  suo: function (e) {

    wx.navigateTo({
      url: '../result/index?id=1',
    })
  }
})