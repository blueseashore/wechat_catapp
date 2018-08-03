/**
 * 网络请求接口模块
 * @module urls
 * @author uckendo <455019825@qq.com>
 */
const HOST = 'https://uckendo.com';
const urls = {
    host: HOST,
    index: HOST + '/Index/index',
    UserLogin: HOST + '/User/login', //小程序用户登陆模块
    UserUpdateInfo: HOST + '/User/UserUpdateInfo',
    BulletType: HOST + '/Index/category', //获取动弹类型列
    blogDetail: HOST + '/Article/detail', //获取文章内容
    mineList: HOST + '/User/mineList', //我的列表
};
module.exports = {
    urls
};