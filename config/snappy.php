<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  =>  'vendor/wemersonjanuario/wkhtmltopdf-windows/bin/wkhtmltopdf-windows',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  =>  base_path('vendor\h4cc\wkhtmltoimage-amd64\bin\wkhtmltoimage-amd64'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
