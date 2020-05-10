# ⚛ HyperScript.php :elephant:

[HyperScript](https://github.com/hyperhype/hyperscript) like syntax for PHP,
so we can code with ⚛️ React style in PHP :elephant:.

## Usage

```php
require_once "./HyperScript.php";

use function HyperScript\createElement as h;

$SecureAnchor = fn ($props) => h("a", array_merge(["rel" => "noopener noreferrer"], $props));

// <a rel="noopener noreferrer" href="https://lshi.ru">My Website</a>
echo h($SecureAnchor, ["href" => "https://lshi.ru"], "My Website");
```

Check `demo.php` for a more complex usage example.
