<?php

namespace App\Console\Commands;

use App\Models\Backup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DatabaseDailyBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily backup command ti database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = date('Y-m-d_H-i-s') . '_backup.sql';
        $user = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        $host = Config::get('database.connections.mysql.host');
        $database_name = Config::get('database.connections.mysql.database');
        Backup::create(array(
            'date_of_entry' => \Carbon\Carbon::now()->toDateTimeString(),
            'remark' => "Automated daily backup on " . \Carbon\Carbon::now()->toDateString(),
            'location' => 'storage/backups/'.$filename,
        ));
        $root_f = storage_path().'/backups/'.$filename;
        // exec('"../../../mysql/bin/mysqldump" --opt --user='.$user.' --password='.$password.' --host='.$host.' capitol > "../storage/backups/'.$filename.'" 2>&1');
        exec('(mysqldump --opt  --skip-extended-insert --complete-insert --user="'.$user.'" --password="'.$password.'" '.$database_name.' > '.$root_f.' ) 2>&1', $output3, $result3);
    }
}
