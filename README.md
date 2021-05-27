<h1 align="left"><a href="#">KuaiShou SDK</a></h1>

📦 快手PHP SDK 快手小程序开发组件。PHP SDK for kuaishou


## Requirement

1. PHP >= 7.1
2. **[Composer](https://getcomposer.org/)**
3. openssl 拓展


## Installation

```shell
$ composer require "surpaimb/kuaishou" -vvv
```

## Usage

基本使用（以服务端为例）:

```php
<?php

use Surpaimb\KuaiShou\Factory;

$options = [
    'app_id'    => 'wx3cf01239eb0exxx',
    'secret'    => 'f1c242f4f28f735d4687abb469072xxx',
    // ...
];

$app = Factory::make($options);
// 接口调用凭证
//  $token = $app->access_token->getToken(true); 
// 登录：code2Session 三方小程序使用 js_code 置换 session_key 和 open_id。
$session = $app->auth->session($code);
// 解密敏感信息
$user = $app->encryptor->decryptData($session->session_key, $iv, $encryptedData);

```


## Documentation

Coming soon

## Integration



## Contributors


## License

MIT

## Special Thanks
[@overtrue](https://github.com/overtrue)

