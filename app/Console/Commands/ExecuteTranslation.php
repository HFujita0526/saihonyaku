<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ExecuteTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'executetranslation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute Translation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!Storage::exists('translation/logs/log_Next.json')) {
            $arrayNext['date'] = (new Carbon())->toDateString();
            $arrayNext['id'] = 1;
            Storage::put('translation/logs/log_Next.json', json_encode($arrayNext));
        }

        $arrayNext = json_decode(Storage::get('translation/logs/log_Next.json'), true);
        $arrayTranslation = json_decode(Storage::get('translation/json/' . $arrayNext['date'] . '/' . $arrayNext['date'] . '-' . sprintf("%03d", floor(($arrayNext['id'] - 1) / \App\Consts\Consts::SENTENCES_PER_LOG)) . '.json'), true);

        $currentIndex = ($arrayNext['id'] - 1) % \App\Consts\Consts::SENTENCES_PER_LOG;
        $nextLangId = (array_search($arrayTranslation[$currentIndex]['lang'], \App\Consts\Consts::TRANSLATION_LANG_LIST_ID) + 1) % 12;
        $nextLangCode = \App\Consts\Consts::TRANSLATION_LANG_LIST_ID[$nextLangId];

        $responseJaToFo = json_decode(Http::post(\App\Consts\Consts::TRANSLATION_API_URL, [
            'text' => $arrayTranslation[$currentIndex]['text_ja'],
            'source' => 'ja',
            'target' => $nextLangCode,
        ]), true);

        if ($responseJaToFo['code'] != 200) {
            return 0;
        }

        $responseFoToJa = json_decode(Http::post(\App\Consts\Consts::TRANSLATION_API_URL, [
            'text' => $responseJaToFo['text'],
            'source' => $nextLangCode,
            'target' => 'ja',
        ]), true);

        if ($responseFoToJa['code'] != 200) {
            return 0;
        }

        if ($currentIndex + 1 >= \App\Consts\Consts::SENTENCES_PER_LOG) {
            $arrayResult[0]['id'] = $arrayNext['id'];
            $arrayResult[0]['lang'] = $nextLangCode;
            $arrayResult[0]['text_fo'] = $responseJaToFo['text'];
            $arrayResult[0]['text_ja'] = $responseFoToJa['text'];
        } else {
            $arrayResult = $arrayTranslation;
            $arrayResult[$currentIndex + 1]['id'] = $arrayNext['id'];
            $arrayResult[$currentIndex + 1]['lang'] = $nextLangCode;
            $arrayResult[$currentIndex + 1]['text_fo'] = $responseJaToFo['text'];
            $arrayResult[$currentIndex + 1]['text_ja'] = $responseFoToJa['text'];
        }

        Storage::put('translation/json/' . $arrayNext['date'] . '/' . $arrayNext['date'] . '-' . sprintf("%03d", floor($arrayNext['id'] / \App\Consts\Consts::SENTENCES_PER_LOG)) . '.json', json_encode($arrayResult));

        $arrayNext['id']++;
        Storage::put('translation/logs/log_Next.json', json_encode($arrayNext));

        return 0;
    }
}
