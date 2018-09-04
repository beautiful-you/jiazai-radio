# Vaptcha Sdk for PHP

### Step1.环境准备

- Vaptcha SDK PHP版本适用于  php5.3及以上版本，且需要开启`curl`。
- 要使用Vaptcha SDK，您需要一个Vaptcha账号、一个验证单元以及一对VID和Key。请在Vaptcha验证管理后台查看。

### Step2.SDK 获取和安装

- 使用命令从Github获取:

  ```shell
  git clone https://github.com/vaptcha/vaptcha-php-sdk.git
  ```

  [github下载地址](https://github.com/vaptcha/vaptcha-php-sdk)手动下载获取。

- 推荐使用composer安装

  > composer 是php的包管理工具， 通过composer.json里的配置管理依赖的包，同时可以在使用类时自动加载对应的包, 在你的composer.json中添加如下依赖

  ```shell
  composer require Vaptcha/vaptcha-sdk;
  ```

- 运行demo
  > demo的vid和key由vaptcha官方免费提供，只可在localhost:4396下使用，缺少一些限制，可能存在安全隐患。在实际生产环境中，我们建议你登陆vaptcha管理后台，在验证管理中添加对应的验证单元，并把domain参数设置为实际环境中的域名。

  ```shell
  git clone https://github.com/vaptcha/vaptcha-php-sdk.git
  cd vaptcha-php-sdk
  composer install
  php -S localhost:4396
  ```

  打开[http://localhost:4396/demo](http://localhost:4396/demo)即可访问

### Step3.SDK接口

使用接口前需先实例化`Vaptcha`

```php
use Vaptcha\Vaptcha;

$v = new Vaptcha($vid, $key); // 实例化sdk，$vid 和 $key 对应验证单元中的Vid和Key
```

SDK提供以下三个接口：

- 获取流水号接口 `getChallenge($sceneId)` ，返回`json`字符串

  参数说明:

  `$sceneId`： 选填，场景id，请在vaptcha管理后台查看

- 宕机模式接口 `downTime($data)`，返回`json`字符串

  参数说明:

  `$data`： GET请求返回的数据，`$_GET['data']`;

- 二次验证接口 `validate($challenge, $token[, $sceneId])`，返回`Boolen`值

  参数说明: 

  `$challenge`： 必填，客户端验证通过后返回的流水号

  `$token`： 必填， 客户端验证通过后返回的令牌

  `$sceneId`： 选填，场景id，与`getChallenge($sceneId)`的场景id保持一致

