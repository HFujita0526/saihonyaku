<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ConvertTxtToJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'converttxttojson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert .txt to JSON';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arrayText = explode(",\n", Storage::get('translation/origins/origin.txt'));
        if ($arrayText[0] === '') return 0;

        if (!Storage::exists('translation/logs/log_UsedLastDate.txt')) Storage::put('translation/logs/log_UsedLastDate.txt', '');

        foreach ($arrayText as $text) {
            $this->putJson($text);
        }

        Storage::copy('translation/origins/origin.txt', 'translation/origins/origin_to' . Storage::get('translation/logs/log_UsedLastDate.txt') . '.txt');
        Storage::delete('translation/origins/origin.txt');
        return 0;
    }

    public function putJson($text)
    {
        $lastDate = Storage::get('translation/logs/log_UsedLastDate.txt');
        if ($lastDate === '') {
            $carbon = Carbon::yesterday();
        } else {
            $carbon = new Carbon($lastDate);
        }
        $carbon->addDay();

        $array[0]['id'] = 0;
        $array[0]['lang'] = 'en';
        $array[0]['text_fo'] = '';
        $array[0]['text_ja'] = $text;

        Storage::makeDirectory('translation/json/' . $carbon->toDateString());
        Storage::put('translation/json/' . $carbon->toDateString() . '/' . $carbon->toDateString() . '-000.json', json_encode($array));
        Storage::put('translation/logs/log_UsedLastDate.txt', $carbon->toDateString());
    }
}
