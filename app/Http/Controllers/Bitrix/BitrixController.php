<?php

namespace App\Http\Controllers\Bitrix;

use App\Http\Controllers\Controller;
use CRest;


class BitrixController extends Controller
{
    public function index()
    {
        $result = CRest::call(
            'crm.lead.add',
            ['FIELDS' => ['TITLE' => 'Новый лид', 'NAME' => 'Иван', 'LAST_NAME' => 'Петров', 'EMAIL' => ['0' => ['VALUE' => 'mail@example.com', 'VALUE_TYPE' => 'WORK',],], 'PHONE' => ['0' => ['VALUE' => '555888', 'VALUE_TYPE' => 'WORK',],],],]
        );

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    public function checkserver()
    {
        CRest::checkServer();
    }

    public function install()
    {

        $result = CRest::installApp();
        if ($result['rest_only'] === false) : ?>

            <head>
                <script src="//api.bitrix24.com/api/v1/"></script>
                <? if ($result['install'] == true) : ?>
                    <script>
                        BX24.init(function() {
                            BX24.installFinish();
                        });
                    </script>
                <? endif; ?>
            </head>

            <body>
                <? if ($result['install'] == true) : ?>
                    installation has been finished
                <? else : ?>
                    installation error
                <? endif; ?>
            </body>
<? endif;
    }
}
