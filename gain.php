<?php

if(!is_dir('tmp')){
	mkdir('tmp',777);
}

$dir = $argv[1];
$gain = $argv[2];

$files = scandir($dir);

foreach($files as $file){
  if(stristr($file,'.mp3')||stristr($file,'.wav')){
    shell_exec("sox '$dir/$file' 'tmp/$file' gain $gain");
  }
}

