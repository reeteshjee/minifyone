# MinifyOne

**MinifyOne** is a simple and lightweight PHP library to **combine** and **minify** multiple CSS or JavaScript files into a **single optimized file**.  
It helps reduce the number of HTTP requests, improving page load speed and performance.

---

## 🚀 Features
- ✅ Combine multiple CSS or JS files into one.
- ✅ Basic minification to reduce file size.
- ✅ Caching based on file hash — no unnecessary reprocessing.
- ✅ Option to directly serve the file with correct headers.
- ✅ Easy to integrate in any PHP project.
- ✅ Works with Composer for easy installation.

---

## 📦 Installation

Install via Composer:

```bash
composer require reeteshjee/minifyone
```

Or manually include the `src/MinifyOne.php` file in your project.

---

## 🛠 Usage

### 1️⃣ Combine Files and Get File Path
```php
require 'vendor/autoload.php';

use MinifyOne\MinifyOne;

// Create instance (output directory, enable/disable minify)
$minify = new MinifyOne(__DIR__ . '/cache', true);

// Combine JavaScript files
$combinedJs = $minify->combine(['assets/js/file1.js', 'assets/js/file2.js'], 'js');
echo "Combined JS: $combinedJs";

// Combine CSS files
$combinedCss = $minify->combine(['assets/css/style1.css', 'assets/css/style2.css'], 'css');
echo "Combined CSS: $combinedCss";
```

This will:
1. Combine the files.
2. Minify content (if enabled).
3. Store them in the specified cache folder.
4. Return the path to the combined file.

---

### 2️⃣ Combine and Serve Directly
If you want to serve the combined file immediately in a request:

```php
require 'vendor/autoload.php';

use MinifyOne\MinifyOne;

$minify = new MinifyOne(__DIR__ . '/cache');

// Output combined CSS directly to the browser
$minify->combineAndServe([
    'assets/css/style1.css',
    'assets/css/style2.css'
], 'css');
```

This will:
- Send the correct `Content-Type` header.
- Output the minified content.
- Send long cache headers (`max-age=31536000`).

---

### 3️⃣ Example with HTML
You can generate a combined file and use it in HTML:

```php
require 'vendor/autoload.php';

use MinifyOne\MinifyOne;

$minify = new MinifyOne(__DIR__ . '/cache');

// Generate combined JS file
$combinedJs = $minify->combine([
    'assets/js/jquery.js',
    'assets/js/app.js'
], 'js');

// Generate combined CSS file
$combinedCss = $minify->combine([
    'assets/css/reset.css',
    'assets/css/main.css'
], 'css');
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?= str_replace(__DIR__, '', $combinedCss) ?>">
</head>
<body>
    <script src="<?= str_replace(__DIR__, '', $combinedJs) ?>"></script>
</body>
</html>
```

---

## ⚙ Constructor Options

```php
new MinifyOne($outputDir = __DIR__ . '/../cache', $minify = true);
```
- **$outputDir** → Directory to save the combined files.
- **$minify** → Enable or disable minification.

---

## 📌 Methods

| Method | Description | Example |
|--------|-------------|---------|
| `combine(array $files, string $type)` | Combines files and returns output file path. | `$minify->combine(['a.css', 'b.css'], 'css');` |
| `combineAndServe(array $files, string $type)` | Combines files and outputs directly with proper headers. | `$minify->combineAndServe(['a.js', 'b.js'], 'js');` |

---

## 🛡 Notes & Recommendations
- This is a **basic minifier**. For advanced compression, integrate with [matthiasmullie/minify](https://github.com/matthiasmullie/minify).
- Ensure your `$outputDir` is **writable** by the web server.
- File hash ensures that if files change, a new combined file is generated automatically.

---

