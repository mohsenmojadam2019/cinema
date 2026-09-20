<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
class BackupDatabase extends Command {protected $signature='cinema:backup';protected $description='Create a database backup and prune expired files';public function handle():int{$dir=config('backup.path');File::ensureDirectoryExists($dir);$source=database_path('database.sqlite');if(!is_file($source)){$this->error('SQLite database not found.');return self::FAILURE;}$file=$dir.'/cinema-'.now()->format('Ymd-His').'.sqlite';if(!copy($source,$file)){$this->error('Backup copy failed.');return self::FAILURE;}$cutoff=now()->subDays((int)config('backup.retention_days',14));foreach(File::files($dir) as $old)if($old->getExtension()==='sqlite'&&$old->getMTime()<$cutoff->timestamp)File::delete($old->getPathname());$this->info($file);return self::SUCCESS;}}
