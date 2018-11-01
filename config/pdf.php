<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('../temp/')
];
return [
	// ...
	'font_path' => resource_path('fonts/'),
	'font_data' => [
		'DanaYadAlefAlefAlef-Normal' => [
			'R'  => 'DanaYadAlefAlefAlef-Normal.woff',    // regular font
			'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
			'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
			'BI' => 'ExampleFont-Bold-Italic.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		],
		'SBL_Hbrw' => [
			'R'  => 'SBL_Hbrw.ttf',    // regular font
			'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
			'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
			'BI' => 'ExampleFont-Bold-Italic.ttf', // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		]
	]
	// ...
];