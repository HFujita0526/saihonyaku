<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LogController extends Controller
{
    public function getLatest(Request $request)
    {
        $request->validate([
            'latestnum' => 'required|integer'
        ]);
        $latestnum = intval($request->latestnum);
        $arrayNext = json_decode(Storage::get('translation/logs/log_Next.json'), true);

        $arrayFilePath = [];
        if ($latestnum < 0) {
            $targetFileNum = floor(($arrayNext['id'] - 1) / \App\Consts\Consts::SENTENCES_PER_LOG);
            for ($i = 0; $i < \App\Consts\Consts::FIRST_GET_FILE_NUM; $i++) {
                if ($targetFileNum - (\App\Consts\Consts::FIRST_GET_FILE_NUM - 1) < 0) {
                    $targetFileNum++;
                    continue;
                }
                $arrayFilePath[$i] = 'translation/json/' . $arrayNext['date'] . '/' . $arrayNext['date'] . '-' . sprintf("%03d", $targetFileNum - (\App\Consts\Consts::FIRST_GET_FILE_NUM - 1)) . '.json';
                $targetFileNum++;
            }
        } else {
            for ($i = 0; $i < ceil((($arrayNext['id'] - 1) - $latestnum) / \App\Consts\Consts::SENTENCES_PER_LOG); $i++) {
                $arrayFilePath[$i] = 'translation/json/' . $arrayNext['date'] . '/' . $arrayNext['date'] . '-' . sprintf("%03d", floor(($latestnum + 1) / \App\Consts\Consts::SENTENCES_PER_LOG) + $i) . '.json';
            }
        }

        $countArrayResult = 0;
        foreach ($arrayFilePath as $filePath) {
            $arrayTranslation = json_decode(Storage::get($filePath), true);
            foreach ($arrayTranslation as $translation) {
                if ($translation['id'] <= $latestnum) continue;
                $result['content'][$countArrayResult + 1] = $translation;
                $result['content'][$countArrayResult + 1]['lang_name'] = \App\Consts\Consts::TRANSLATION_LANG_LIST_NAME[array_search($translation['lang'], \App\Consts\Consts::TRANSLATION_LANG_LIST_ID)];
                $countArrayResult++;
            }
        }
        $result['content'][0] = $countArrayResult;

        $result['status'] = 200;
        return response()->json($result);
    }

    public function getOlder(Request $request)
    {
        $request->validate([
            'lastnum' => 'required|integer|min:1'
        ]);
        $lastnum = intval($request->lastnum);

        $arrayNext = json_decode(Storage::get('translation/logs/log_Next.json'), true);
        $targetFileNum = floor(($lastnum - 1) / \App\Consts\Consts::SENTENCES_PER_LOG);
        $filePath = 'translation/json/' . $arrayNext['date'] . '/' . $arrayNext['date'] . '-' . sprintf("%03d", $targetFileNum) . '.json';

        $countArrayResult = 0;
        $arrayTranslation = json_decode(Storage::get($filePath), true);
        for ($i = \App\Consts\Consts::SENTENCES_PER_LOG - 1; $i >= 0; $i--) {
            if ($arrayTranslation[$i]['id'] >= $lastnum) continue;
            $result['content'][$countArrayResult + 1] = $arrayTranslation[$i];
            $result['content'][$countArrayResult + 1]['lang_name'] = \App\Consts\Consts::TRANSLATION_LANG_LIST_NAME[array_search($arrayTranslation[$i]['lang'], \App\Consts\Consts::TRANSLATION_LANG_LIST_ID)];
            $countArrayResult++;
        }
        $result['content'][0] = $countArrayResult;

        $result['status'] = 200;
        return response()->json($result);
    }
}
