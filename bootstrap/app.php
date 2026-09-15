<?php

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// توجيه الكاش لـ Temp ديال الويندوز باش يبقى المشروع نقي
$tempDir = sys_get_temp_dir() . '/campusfix_temp';

if (!is_dir($tempDir . '/cache')) {
    @mkdir($tempDir . '/cache', 0777, true);
}
if (!is_dir($tempDir . '/storage/framework/views')) {
    @mkdir($tempDir . '/storage/framework/views', 0777, true);
    @mkdir($tempDir . '/storage/framework/sessions', 0777, true);
}

$app->useBootstrapPath($tempDir);
$app->useStoragePath($tempDir . '/storage');

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Illuminate\Foundation\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Illuminate\Foundation\Exceptions\Handler::class
);

return $app;