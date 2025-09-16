# PHPUnit 測試指南

本專案已整合 PHPUnit 測試框架，以下是使用說明：

## 測試結構

```
tests/
├── Unit/           # 單元測試
│   └── OpenapiTest.php
├── Feature/        # 功能測試
│   └── ExampleFeatureTest.php
└── bootstrap.php   # 測試啟動檔案
```

## 可用的測試命令

### 執行所有測試
```bash
composer test
```

### 執行單元測試
```bash
composer test-unit
```

### 執行功能測試
```bash
composer test-feature
```

### 生成覆蓋率報告

#### HTML 格式覆蓋率報告
```bash
composer test-coverage
```
報告將生成在 `coverage-html/` 目錄中

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

## 編寫測試

### 單元測試範例
```php
<?php

namespace Cometcast\Openapi\Tests\Unit;

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

### 功能測試範例
```php
<?php

namespace Cometcast\Openapi\Tests\Feature;

use PHPUnit\Framework\TestCase;

class YourFeatureTest extends TestCase
{
    public function testFeature(): void
    {
        // 測試整合功能
        $this->assertTrue(true);
    }
}
```

## 注意事項

1. 測試檔案必須以 `Test.php` 結尾
2. 測試類別必須繼承 `PHPUnit\Framework\TestCase`
3. 測試方法必須以 `test` 開頭或使用 `@test` 註解
4. 所有測試相關的檔案都已加入 `.gitignore`