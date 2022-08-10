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

        Storage::put('translation/logs/log_LastExecutedDate.txt', Storage::get('translation/logs/log_UsedLastDate.txt'));

        Storage::copy('translation/origins/origin.txt', 'translation/origins/origin_to' . Storage::get('translation/logs/log_UsedLastDate.txt') . '.txt');
        Storage::delete('translation/origins/origin.txt');
        return 0;
    }

    public static function nextNotExists()
    {
        $lastExecutedDate = Storage::get('translation/logs/log_LastExecutedDate.txt');
        $cLastExecutedDate = new Carbon($lastExecutedDate);
        $lastDateRe = Storage::get('translation/logs/log_UsedLastDateRe.txt');
        $cLastDateRe = new Carbon($lastDateRe);

        $nextDateRe = '';
        $files = Storage::files('translation/origins');
        foreach ($files as $file) {
            $cFile = new Carbon(substr(str_replace('translation/origins/', '', $file), 9, 10));
            if ($cFile->gt($cLastDateRe)) {
                $nextDateRe = $cFile->toDateString();
                break;
            }
            if ($cFile->gte($cLastExecutedDate)) {
                $nextDateRe = (new Carbon(substr(str_replace('translation/origins/', '', $files[0]), 9, 10)))->toDateString();
                break;
            }
        }
        $arrayText = explode(",\n", Storage::get('translation/origins/origin_to' . $nextDateRe . '.txt'));
        if ($arrayText[0] === '') return 0;

        if (!Storage::exists('translation/logs/log_UsedLastDate.txt')) Storage::put('translation/logs/log_UsedLastDate.txt', '');

        foreach ($arrayText as $text) {
            Self::putJson($text);
        }

        Storage::put('translation/logs/log_UsedLastDateRe.txt', $nextDateRe);
        Storage::copy('translation/origins/origin_to' . $nextDateRe . '.txt', 'translation/origins/origin_to' . Storage::get('translation/logs/log_UsedLastDate.txt') . '.txt');
    }

    public static function putJson($text)
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
