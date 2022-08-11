<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * @method static getMessage($codeMsg)
 */
class ErrorCode extends AbstractConstants
{
    /* -------------4xx------------ */

    /** @Message("请求含有语义错误") */
    public const BAD_REQUEST = 400000;

    /** @Message("未授权的访问") */
    public const UNAUTHORIZED = 401000;

    /** @Message("请求被拒绝") */
    public const FORBIDDEN = 403000;

    /** @Message("资源未找到") */
    public const NOT_FOUND = 404000;

    /** @Message("记录未找到") */
    public const MODEL_NOT_FOUND = 404001;

    /** @Message("路由未找到") */
    public const ROUTE_NOT_FOUND = 404002;

    /** @Message("请求方法不允许") */
    public const METHOD_NOT_ALLOWED = 405000;

    /** @Message("参数错误") */
    public const UNPROCESSABLE_ENTITY = 422000;

    /* -------------5xx------------ */

    /** @Message("服务器错误") */
    public const SERVER_ERROR = 500000;

    /** @Message("上游无响应") */
    public const BAD_GATEWAY = 502000;

    /** @Message("请求超时") */
    public const GATEWAY_TIMEOUT = 504000;
}
