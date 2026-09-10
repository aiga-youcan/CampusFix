<?php

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// 💡 Storage w bootstrap f Temp folder
$storagePath = sys_get_temp_dir() . '/campusfix_storage';
if (!file_exists($storagePath)) {
    mkdir($storagePath, 0777, true);
}
$app->useStoragePath($storagePath);

$bootstrapPath = sys_get_temp_dir() . '/campusfix_bootstrap';
if (!file_exists($bootstrapPath . '/cache')) {
    mkdir($bootstrapPath . '/cache', 0777, true);
}
$app->useBootstrapPath($bootstrapPath);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

// 💡 Hna rddinaha t-pointi 3la Laravel Core Console Kernel mashi l-fichier l-maḥalli
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    \Illuminate\Foundation\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    \Illuminate\Foundation\Exceptions\Handler::class
);

return $app;