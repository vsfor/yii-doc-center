<?php

$this->backupSubsFont = 'sun-exta';

$this->backupSIPFont = 'sun-extb';

$this->fonttrans = array(
	'times' => 'timesnewroman',
	'courier' => 'couriernew',
	'trebuchet' => 'trebuchetms',
	'comic' => 'comicsansms',
	'franklin' => 'franklingothicbook',
	'ocr-b' => 'ocrb',
	'ocr-b10bt' => 'ocrb',
	'damase' => 'mph2bdamase',
);

$this->fontdata = array(
/* CJK fonts */
	"sun-exta" => [
		'R' => "SimSun.ttf",
		'B' => "MicrosoftYahei.ttf",
		'sip-ext' => 'sun-extb',		/* SIP=Plane2 Unicode (extension B) */
	],
	"sun-extb" => [
		'R' => "SimSun-ExtB.ttf",
	],
);


// Add fonts to this array if they contain characters in the SIP or SMP Unicode planes
// but you do not require them. This allows a more efficient form of subsetting to be used.
$this->BMPonly = ['sun-exta'];

$this->sans_fonts = ['sun-exta'];

$this->serif_fonts = ['sun-exta'];

$this->mono_fonts = ['sun-exta'];

