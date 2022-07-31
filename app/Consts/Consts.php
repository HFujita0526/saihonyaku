<?php

namespace App\Consts;

class Consts
{
    public const SENTENCES_PER_LOG = 5;
    public const FIRST_GET_FILE_NUM = 2;
    public const TRANSLATION_API_URL = "https://script.google.com/macros/s/AKfycbwSz28i-x6FP4aOuieqI7dud9h4LSaQ4DfR7SFdKxP5Mhn-lGFbDQ0BDmzcSywD7BFoVQ/exec";

    public const TRANSLATION_LANG_LIST_ID = [
        'en',
        'fr',
        'de',
        'es',
        'it',
        'zh',
        'ko',
        'cs',
        'nl',
        'la',
        'ru',
        'bg',
    ];

    public const TRANSLATION_LANG_LIST_NAME = [
        '英語',
        'フランス語',
        'ドイツ語',
        'スペイン語',
        'イタリア語',
        '中国語 (簡体)',
        '韓国語',
        'チェコ語',
        'オランダ語',
        'ラテン語',
        'ロシア語',
        'ブルガリア語',
    ];
}
