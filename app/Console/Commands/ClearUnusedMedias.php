<?php

namespace App\Console\Commands;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearUnusedMedias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-unused-medias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused temp medias and unassociated files on storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysToMediaExpireCount = 2;

        $bar = $this->output->createProgressBar(Media::count());
        $bar->setFormatDefinition('custom', ' %current%/%max% [%bar%] %message% %percent:3s%% %elapsed:6s%/%estimated:-6s%');
        $bar->setFormat('custom');
        $bar->start();

        $deletedRecords = 0;
        $deletedFolders = 0;

        Media::withoutGlobalScopes()->whereDate('created_at', '<=', now()->subDays($daysToMediaExpireCount))->chunk(100, function ($media, $key) use ($bar, &$deletedRecords) {
            foreach ($media as $key => $item) {
                $bar->setMessage($deletedRecords);
                if (str()->contains($item->collection_name, ['tmp', 'temp'])) {
                    $deletedRecords++;
                    $item->delete();
                }
                $bar->advance();
            }
        });

        $this->info('Deleted Records: ' . $deletedRecords);
        $bar->finish();

        //Теперь ищем файлы на диске для которых в базе данных нет записей и удаляем
        $folders = Storage::disk('media')->directories();

        $bar = $this->output->createProgressBar(count($folders));
        $bar->setFormatDefinition('custom', ' %current%/%max% [%bar%] %message% %percent:3s%% %elapsed:6s%/%estimated:-6s%');
        $bar->setFormat('custom');
        $bar->start();

        foreach ($folders as $folderName) {
            $subfolders = Storage::disk('media')->directories($folderName);
            foreach ($subfolders as $subFolderName) {
                $uuidFolders = Storage::disk('media')->directories($subFolderName);
                foreach ($uuidFolders as $uuid) {
                    $mediaCount = Media::withoutGlobalScopes()->where('uuid', explode('/', $uuid)[2])->count();
                    if ($mediaCount == 0) {
                        Storage::disk('media')->deleteDirectory($uuid);
                        $bar->setMessage($deletedFolders);
                        $deletedFolders++;
                    }
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->info('Deleted Records: ' . $deletedRecords);
        $this->info('Deleted Folders: ' . $deletedFolders);
        return 0;
    }
}
