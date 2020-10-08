<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Requests;
use App\Models\Backup;

class BackupController extends Controller
{
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->base['page_title'] = 'Backup & Restore';
    }
    
    public function index()
    {
        $this->base['sub_header'] = 'List';
        return view('base.backup.list')->with('base', $this->base);
    }
    
    public function store(Request $request)
    {
        $filename = date('Y-m-d_H-i-s') . '_backup.sql';
        $user = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        $host = Config::get('database.connections.mysql.host');
        $database_name = Config::get('database.connections.mysql.database');
        Backup::create(array(
            'date_of_entry' => \Carbon\Carbon::now()->toDateTimeString(),
            'remark' => $request['remark'],
            'location' => 'storage/backups/'.$filename,
        ));
        $root_f = storage_path().'/backups/'.$filename;
        // exec('"../../../mysql/bin/mysqldump" --opt --user='.$user.' --password='.$password.' --host='.$host.' capitol > "../storage/backups/'.$filename.'" 2>&1');
        exec('(mysqldump --opt  --skip-extended-insert --complete-insert --user="'.$user.'" --password="'.$password.'" '.$database_name.' > '.$root_f.' ) 2>&1', $output3, $result3);
        Session::flash('info', ['Backup created.']);
        return redirect()->route('backup.index');
    }
    
    public function show($id)
    {
        $this->base['backup'] = Backup::whereId($id)->first();
        $this->base['sub_header'] = 'List';
        return view('base.backup.view')->with('base', $this->base);
    }
    
    public function destroy($id)
    {
        $backup = Backup::whereId($id)->first();
        \File::delete('../'.$backup->location);
        $backup->delete();
        
        Session::flash('info', ['Backup has been deleted.']);
        return redirect()->route('backup.index');
    }
    
    public function restore($id)
    {
        # we make a file so that while restoration is ongoing:
        # we disallow users from transacting with system
        \File::put('ongoing_db_restore', '');
        $filename = date('Y-m-d_H-i-s') . '_backup_restore.sql';
        $user = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        $host = Config::get('database.connections.mysql.host');
        $database_name = Config::get('database.connections.mysql.database');
        
        $backup = Backup::whereId($id)->first();
        $backup = '../'.$backup->location;
        \DB::statement('drop database capitol');
        \DB::statement('create database capitol');
         $root_f = storage_path().'/backups/'.$filename;
        exec('(mysqldump --opt  --skip-extended-insert --complete-insert --user="'.$user.'" --password="'.$password.'" '.$database_name.' > '.$root_f.' ) 2>&1', $output3, $result3);
        // exec('"../../../mysql/bin/mysql" --user='.$user.' --password='.$password.' --host='.$host.' capitol < "'.$backup.'"');
        exec("(mysql -u$user -p$password $database_name < $backup ) 2>&1", $output3, $result3);
        \File::delete('ongoing_db_restore');
        
        Session::flash('info', ['Backup has been restored.']);
        return redirect()->route('backup.index');
    }
}
