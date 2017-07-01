# 第三方登录模块
此模块仅完成第三方登录信息获取，不涉及创建本地用户等业务逻辑操作。

## 调用代码
```php
//获取OAuth实例
$oauth = OauthService::getInstance(
    'weixin',//登录方式代码，对应services\oauth下的文件夹名
    {$app_id},//App Id
    {$app_secret}//App Secret
);
//获取openId
$open_id = $oauth->getOpenId();
//获取第三方用户
$user = $oauth->getUser();
//获取第三方用户字段
$user->getNickName();//获取第三方昵称
$user->getAvatar();//获取第三方头像链接
$user->getOpenId();//获取第三方对外id（Open Id）
$user->getUnionId();//获取第三方Union Id（微信登录可能会有这个值）
$user->getParam('city');//获取指定字段，根据第三方登录方式不同，字段有所差异
$user->getParams();//获取所有第三方返回的用户字段
```

## 代码介绍

- `ClientAbstract` 用于获取Access Token，state值校验等操作
- `AccessTokenAbstract` Access Token实例，可通过此实例获取用户信息
- `OauthService` 由于不同第三方登录参数略有区别，用OauthService来统一调用入口
  * `getAccessToken()` 获取Access Token（AccessTokenAbstract实例）
  * `getUser()` 获取第三方用户信息（返回UserAbstract实例）
  * `getOpenId()` 获取用户openId
- `UserAbstract` 第三方返回的用户信息各有不同，用此类来统一用户信息获取方法
  * `getAccessToken()` 获取Access Token值（字符串）
  * `getNickName()` 获取用户昵称
  * `getOpenId()` 获取用户openId
  * `getUnionId()` 获取用户UnionId。目前好像就微信有这个值
  * `getAvatar()` 获取用户头像
  * `getParams()` 获取所有原生第三方用户信息
  * `getParam($name)` 根据原生第三方用户信息字段获取用户信息
  * `__get()` 可以通过魔术方法调用`getParam()`
  * `ArrayAccess` 可以通过数组的方式调用`getParam()`
- `StateManager` 用于管理state

> 总结：`Client`和`AccessToken`类用于实现第三方登录。`OauthService`和`User`类用于统一调用和返回数据格式。

扩展登录方式时，需要实现`AccessTokenAbstract`, `ClientAbstract`, `OauthService`, `UserAbstract`类。