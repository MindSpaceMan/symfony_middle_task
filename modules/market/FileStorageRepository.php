<?php
declare(strict_types=1);

namespace Market;

final class FileStorageRepository
{
    public function __construct(
        private string $basePath, // абсолютный путь к каталогу с legacy-картинками
        private string $baseUrl   // публичный базовый URL (CDN или /uploads)
    ) {}

    /** Абсолютный публичный URL для файла или null, если имя пустое */
    public function getUrl(string $fileName): ?string
    {
        $fileName = trim($fileName);
        if ($fileName === '') {
            return null;
        }
        return rtrim($this->baseUrl, '/').'/'.ltrim($fileName, '/');
    }

    /** Проверка существования файла в локальном сторадже */
    public function fileExists(string $fileName): bool
    {
        $path = $this->path($fileName);
        return is_file($path);
    }

    /** Удаление файла (используется только для legacy-сценариев) */
    public function deleteFile(string $fileName): void
    {
        $path = $this->path($fileName);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Сохранение файла: ожидаем, что $fileName уже лежит во временной директории проекта
     * и его нужно «ввести в эксплуатацию» (переместить в $basePath).
     * Если у тебя другой поток загрузки — адаптируй под него.
     */
    public function saveFile(string $fileName): void
    {
        $src = $this->pathTemp($fileName);
        $dst = $this->path($fileName);

        if (!is_dir(dirname($dst)) && !@mkdir(dirname($dst), 0775, true) && !is_dir(dirname($dst))) {
            throw new \RuntimeException('Cannot create directory for '.$dst);
        }

        if (!@rename($src, $dst)) {
            // запасной вариант — копирование
            if (!@copy($src, $dst)) {
                throw new \RuntimeException('Cannot move file to storage: '.$fileName);
            }
            @unlink($src);
        }
    }

    private function path(string $fileName): string
    {
        return rtrim($this->basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.ltrim($fileName, DIRECTORY_SEPARATOR);
    }

    private function pathTemp(string $fileName): string
    {
        // Подстрой под свой tmp-каталог (или передавай путь извне)
        return rtrim($this->basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.ltrim($fileName, DIRECTORY_SEPARATOR);
    }
}