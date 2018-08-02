<?php

/**
 * 自定义函数
 *
 * User: kendo
 */
defined('BASEPATH') OR exit('No direct script access allowed');
// REST允许输出的资源类型列表
$allowOutputType = array(
    'xml' => 'application/xml',
    'json' => 'application/json',
    'html' => 'text/html',
);

// 发送Http状态信息
function sendHttpStatus($code)
{
    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ',  // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    if (isset($_status[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:' . $code . ' ' . $_status[$code]);
    }
}

/**
 * 输出返回数据
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type 返回类型 JSON XML
 * @param integer $code HTTP状态
 * @return void
 */
function response($data, $type = '', $code = 200)
{
    sendHttpStatus($code);
    exit(encodeData($data, strtolower($type)));
}

/**
 * 编码数据
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type 返回类型 JSON XML
 * @return string
 */
function encodeData($data, $type = '')
{
    if (empty($data)) return '';
    if ('json' == $type) {
        // 返回JSON数据格式到客户端 包含状态信息
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    } elseif ('xml' == $type) {
        // 返回xml格式数据
        $data = xml_encode($data);
    } elseif ('php' == $type) {
        $data = serialize($data);
    }// 默认直接输出
    setContentType($type);
    return $data;
}

/**
 * 设置页面输出的CONTENT_TYPE和编码
 * @access public
 * @param string $type content_type 类型对应的扩展名
 * @param string $charset 页面输出编码
 * @return void
 */
function setContentType($type, $charset = 'UTF-8')
{
    global $allowOutputType;
    if (headers_sent()) return;
    $type = strtolower($type);
    if (isset($allowOutputType[$type])) //过滤content_type
        header('Content-Type: ' . $allowOutputType[$type] . '; charset=' . $charset);
}

/**
 * 返回JSON
 *
 * @param bool $status
 * @param array $info
 */
function send_json($status = TRUE, $info = [])
{
    $data['ret'] = intval($status);
    $data['message'] = '';
    if (empty($info)) {
        if ($status) {
            $data['message'] = 'Success';
        } else {
            $data['message'] = 'Failure';
        }
    } else {
        if (is_string($info)) {
            $data['message'] = $info;
        } elseif (is_array($info) && !empty($info)) {
            $data['data'] = $info;
        }
    }
    response($data, "json");
}

/**
 * 发送列表页需要的JSON数据
 *
 * @param array $list
 * @param int $total
 * @return string
 */
function send_list_json($list = [], $total = 0)
{
    $data_list['code'] = 0;
    $data_list['rel'] = true;
    $data_list['msg'] = '获取成功';
    $data_list['count'] = $total;
    $data_list['data'] = empty($list) ? [] : $list;
    echo json_encode($data_list);
}

/**
 * 过滤掉 limit 后面的字符
 *
 * @param string $sql
 * @return bool|int|string
 */
function filter_limit_sql($sql = '')
{
    $sql = strtoupper($sql);
    $pos = strpos($sql, 'LIMIT');
    if ($pos) {
        $sql = substr($sql, 0, $pos);
    }
    return $sql;
}