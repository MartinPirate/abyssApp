<?php

    namespace App\Console\Commands;

    use App\Models\Post;
    use Illuminate\Console\Command;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;

    class DeleteOldRecordsCommand extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'delete:records';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Delete any record older than 30 days';

        /**
         * Execute the console command.
         *
         *
         */
        public function handle()
        {

            // Delete records that are 30 days old
            $old_records = DB::table('posts')
             ->where('created_at', '<', Carbon::now()->subDays(30))
             -> where('deleted_at', NULL)->get();

            //delete files
            foreach ($old_records as $record) {
                $file_path = "files/" . $record->file;
                if (Storage::exists($file_path)) {
                    Storage::delete($file_path);
                }
                //delete the record
                $record = Post::whereId($record->id)->first();
                $record->delete();
            }


            $this->info('All Old Records and Files Deleted Successfully');


        }
    }
