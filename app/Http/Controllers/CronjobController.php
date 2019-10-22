<?php

namespace App\Http\Controllers;

use App\Cronjob;
use App\Download;
use Illuminate\Http\Request;
use DB;
//use App\Http\Controllers\Controller;

class CronjobController extends Controller
{
    const var1 = '';
    const filePath = '`crontab /tmp/crontab.txt`';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
//        $cronjobs = $cronjob::all();
        return view('auth.login');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $cronjobs = $cronjob::all();
        //return view('pages.index',compact('index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){

             return view('pages.setjob');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'query'=>'required',
            'time'=>'required',
            'routineTime'=>'required',
            'file_type'=>'required'
        ]);
        
        $cronjob = new Cronjob();
        
        // saving logic
        $cronjob->query = request('query');
        $cronjob->time = request('time');
        $cronjob->routine_gap = request('routineTime');
        $cronjob->routine_day = count(request('routineDay')) > 0 ? implode(',', request('routineDay')) : 0;
        $cronjob->file_type = request('file_type');
        $cronjob->user_id = auth()->user()->id;
        $cronjob->save();
        
        // after save actions
        if ($this->schedule_cron_to_crontab($cronjob)) {
            $request->session()->flash('alert-success', 'Cron job scheduled!!');
        
            return redirect('cronjob/create');
        }      
        $request->session()->flash('alert-error', 'Cron scheduling failed!!');
        
        return view('pages.setjob', $request);
    }
    
    /**
     * 
     */
    private function schedule_cron_to_crontab() {
        //get data from db to schedule a cron
        $valueArray = Cronjob::all(); 
//        dd($valueArray->);        
        // creating a crontab string
        
        $cronCommand = $this->create_command_for_crontab($valueArray);
        
        // writing cron command to txt file and setting cron
        if ($this->set_to_file($cronCommand)) {
            if ($this->set_to_crontab_from_file(self::filePath)) {
                // output
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    /**
     * 
     */
    private function create_command_for_crontab($valueArray = []) {
        // putting actions on db fetched values
        $cronStr = '';
        $dataArray = [];
        
        foreach ($valueArray as $cronData) {
            $dataArray[] = [
                'id' => $cronData->id,
                'routine_gap' => $cronData->routine_gap,
                'routine_day' => $cronData->routine_day,
            ];
        }

        foreach ($dataArray as $cronParam) {
            $daily = FALSE;
            foreach ($cronParam as $dataKey => $dataValue) {
                print_r($dataKey);
                if($dataKey == 'routine_gap') {
                    if ($dataValue == 'hourly') {
                        $cronStr .= '* * * * ';
                    }
                    else if ($dataValue =='daily') {
                        $cronStr .= '1 1 * * *';//0 0 could be relaced with time value in db                    
                        $daily = TRUE;
                    }
                    else if ($dataValue == 'monthly') {
                        $cronStr .= '1 1 1 *';//0 0 could be relaced with time value in db
                    }
                    else if ($dataValue == 'weekly') {
                        $cronStr .= '1 1 * *';//0 0 could be relaced with time value in db
                    }
                }
                
                if ($dataKey == 'routine_day' && !$daily) {
                    $cronStr .= $dataValue;
                }

                
                $id = $cronParam['id'];
                
            }
            // finalizing cron command with providing the file and env path
            $phpPath = ' /Applications/MAMP/bin/php/php7.3.1/bin/php';//exec('which php');
            $scriptPath = " /Applications/MAMP/htdocs/PayuCron/scripts/test.php";
            $cronStr .= " $phpPath $scriptPath id=". $id;

            // setting log file
            $cronStr .= " >> /var/logs/crontab_payu.log;\n";
        }
        return $cronStr;
    }
    
    /**
     * 
     */
    private function set_to_file($cronStr = null) {
//        echo $cronStr;die;
        unlink('/tmp/crontab.txt');
        if (file_put_contents('/tmp/crontab.txt', $cronStr, FILE_APPEND)) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * 
     */
    private function set_to_crontab_from_file($filePath = null) {
        $output = exec('crontab -l');
//        exec('crontab -r');
        echo exec("`crontab /tmp/crontab.txt`");
        
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cronjob  $cronjob
     * @return \Illuminate\Http\Response
     */
    public function show(Cronjob $cronjob)
    {
        $dataArray = [];

        $downloadsData = Download::all(); 
        foreach ($downloadsData as $data) {
            $dataArray[] = [
                'id' => $data->id,
                'date_added' => $data->date_added,
                'download_path' => $data->download_path,
                'merchant_id' => $data->merchant_id,
            ];
        }
        
        return view('pages.downloads')->with('dataArray',$dataArray);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cronjob  $cronjob
     * @return \Illuminate\Http\Response
     */
    public function edit(Cronjob $cronjob)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cronjob  $cronjob
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cronjob $cronjob)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cronjob  $cronjob
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cronjob $cronjob)
    {
        //
    }
}
