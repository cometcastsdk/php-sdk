# PHPUnit 測試指南

本專案已整合 PHPUnit 測試框架，以下是使用說明：

## 測試結構

```
tests/
├── OpenapiTest.php           # Openapi 類別測試
├── OpenIdProviderTest.php    # OpenIdProvider 類別測試
├── OpenIdResourceOwnerTest.php # OpenIdResourceOwner 類別測試
└── bootstrap.php             # 測試啟動檔案
```

## 可用的測試命令

### 執行所有測試
```bash
composer test
```

### 生成覆蓋率報告

#### HTML 格式覆蓋率報告
```bash
composer test-coverage
```
報告將生成在 `coverage/` 目錄中

#### 文字格式覆蓋率報告
```bash
composer test-coverage-text
```

#### Clover XML 格式覆蓋率報告
```bash
composer test-coverage-clover
```

## 設定檔案

- `phpunit.xml.dist` - PHPUnit 主要設定檔案
- 測試結果會輸出到 `test-results.xml`
- 覆蓋率報告會生成多種格式：HTML、文字、Clover XML

## 測試涵蓋範圍

### OpenIdProvider 測試
- 建構函式參數處理
- 授權 URL 生成
- 存取權杖 URL 生成
- 資源擁有者詳細資訊 URL 生成
- 預設範圍設定
- PKCE 方法處理
- 錯誤回應檢查
- 資源擁有者建立

### OpenIdResourceOwner 測試
- 所有屬性的取得方法
- 空值和部分資料處理
- 陣列轉換功能

### Openapi 測試
- 基本功能測試
- 版本資訊取得

## 編寫測試

### 單元測試範例
```php
<?php

namespace Cometcast\Openapi\Tests;

use Cometcast\Openapi\Openapi;
use PHPUnit\Framework\TestCase;

class YourClassTest extends TestCase
{
    public function testSomething(): void
    {
        $instance = new Openapi();
        $this->assertInstanceOf(Openapi::class, $instance);
    }
}
```

## 注意事項

1. 測試檔案必須以 `Test.php` 結尾
2. 測試類別必須繼承 `PHPUnit\Framework\TestCase`
3. 測試方法必須以 `test` 開頭或使用 `@test` 註解
4. 所有測試相關的檔案都已加入 `.gitignore`
5. 使用 `@covers` 註解指定測試涵蓋的類別