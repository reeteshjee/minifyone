<?php
namespace MinifyOne;

class MinifyOne
{
    protected string $outputDir;
    protected bool $minify;

    public function __construct(string $outputDir = __DIR__ . '/../cache', bool $minify = true)
    {
        $this->outputDir = rtrim($outputDir, '/');
        $this->minify = $minify;

        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }

    /**
     * Combine and minify files into a single file.
     *
     * @param array $files Array of file paths.
     * @param string $type 'css' or 'js'.
     * @return string Path to combined file.
     */
    public function combine(array $files, string $type): string
    {
        $this->validateType($type);

        $hash = md5(json_encode($files));
        $outputFile = "{$this->outputDir}/minifyone_{$hash}.{$type}";

        if (!file_exists($outputFile)) {
            $content = '';
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $fileContent = file_get_contents($file);
                    if ($this->minify) {
                        $fileContent = $this->minifyContent($fileContent, $type);
                    }
                    $content .= $fileContent . "\n";
                }
            }
            file_put_contents($outputFile, $content);
        }

        return $outputFile;
    }

    /**
     * Combine, minify and directly output the file with correct headers.
     *
     * @param array $files Array of file paths.
     * @param string $type 'css' or 'js'.
     */
    public function combineAndServe(array $files, string $type): void
    {
        $outputFile = $this->combine($files, $type);

        header("Content-Type: " . $this->mimeType($type));
        header("Cache-Control: max-age=31536000, immutable");
        readfile($outputFile);
        exit;
    }

    protected function minifyContent(string $content, string $type): string
    {
        if ($type === 'js') {
            // Remove single-line comments
            $content = preg_replace('/\/\/[^\n]*/', '', $content);
            // Remove multi-line comments
            $content = preg_replace('/\/\*.*?\*\//s', '', $content);
            // Remove excess whitespace
            $content = preg_replace('/\s+/', ' ', $content);
        } elseif ($type === 'css') {
            // Remove comments
            $content = preg_replace('/\/\*.*?\*\//s', '', $content);
            // Remove excess whitespace
            $content = preg_replace('/\s+/', ' ', $content);
            // Remove spaces around : and ;
            $content = str_replace(['; ', ': '], [';', ':'], $content);
        }
        return trim($content);
    }

    protected function validateType(string $type): void
    {
        if (!in_array($type, ['css', 'js'])) {
            throw new \InvalidArgumentException("Type must be 'css' or 'js'.");
        }
    }

    protected function mimeType(string $type): string
    {
        return $type === 'js' ? 'application/javascript' : 'text/css';
    }
}
