# 第三方登录模块

此模块仅完成第三方登录信息获取，不涉及创建本地用户等业务逻辑操作。

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
 * `getType()` 获取登录方式类型，对应user_connects表的type字段。每个登录方式需要实现此方法。
- `StateManager` 用于管理state

> 总结：`Client`和`AccessToken`类用于实现第三方登录。`OauthService`和`User`类用于统一调用和返回数据格式。

扩展登录方式时，需要实现`AccessTokenAbstract`, `ClientAbstract`, `OauthService`, `UserAbstract`类。

> 完善：目前系统不支持同一个第三方配置多个app id，暂时用getType来区分一下。后期优化为支持多组app id配置后，UserAbstract::getType()方法可移除