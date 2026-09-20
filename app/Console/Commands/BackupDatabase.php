<?php
namespace App\Console\Commands; use Illuminate\Console\Command; use Illuminate\Support\Facades\File;
class BackupDatabase extends Command {protected $signature='cinema:backup';protected $description='Create a database backup';public function handle():int{$dir=config('backup.path');File::ensureDirectoryExists($dir);$file=$dir.'/cinema-'.now()->format('Ymd-His').'.sqlite';$source=database_path('database.sqlite');if(is_file($source))copy($source,$file);$this->info($file);return self::SUCCESS;}}
