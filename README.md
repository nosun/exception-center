# Exception Center

这是一个 Laravel 13 异常处理中心，同时提供可嵌入 `tradeExpress`、`tradeManager` 和 `console-new` 的 Composer 客户端。业务项目只负责标准化、脱敏并写入 `trade_common.exception_events`；本项目负责归并、通知记录、人工升级和后续 agent 处理。

## 开发与测试

```bash
composer install
php artisan list | grep exception-center
php artisan migrate --env=testing
php tests/run.php
```

本地没有业务数据库时，可以使用 SQLite 验证迁移：

```bash
DB_CONNECTION=sqlite DB_DATABASE=':memory:' php artisan migrate --force
```

核心代码位于 `src/`；Laravel 应用入口、命令和迁移分别位于 `artisan`、`app/Console/Commands` 和 `database/migrations`。当前命令包括：

```text
exception-center:aggregate
exception-center:notify
exception-center:dispatch
exception-center:retry
exception-center:prune
exception-center:health-check
```

通知和 agent runner 的外部适配器仍需配置，未配置时命令会安全地记录待处理状态或升级人工队列，不会自动发送外部消息或执行生产修改。

## 发布客户端

先在本项目完成测试，再执行：

```bash
php tests/run.php
./scripts/publish-client.sh
```

脚本将客户端复制到三个同级业务项目的 `packages/laravel-exception-center-client`。业务项目随后分别运行 `composer install`、提交并推送自己的代码。

生产部署前应先在 `trade_common` 执行 Laravel migration，配置异常中心数据库账号，再发布业务项目并设置 `EXCEPTION_CENTER_ENABLED=true`。业务项目的异常上报失败保持 fail-open，不会覆盖原始业务异常。
