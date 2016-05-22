<?php

if(!is_dir('output')){
  mkdir('output',777);
}

function unaccent($string){
    return preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml|caron);~i', '$1', htmlentities($string, ENT_COMPAT, 'UTF-8'));
}

$input = null;

if(file_exists('input.mp3')){
  $input = 'input.mp3';
} else if(file_exists('input.wav')) {
  $input = 'input.wav';
} else {
  die('input.mp3 or input.wav file missing!');
}

if(!file_exists('input.txt')){
  die('input.txt file missing!');
}

if(!file_exists('tmp.wav')){
  shell_exec("sox $input tmp.wav");
}
if(!is_dir('output')){
  mkdir('output',777);
}

$index = count(scandir('output'))-1;

$step = .5;
$length = 1;
$start = 0;

$stdin = fopen("php://stdin","r");

$lines = file('input.txt');
foreach($lines as $i => $line){
  $line = trim($line);
  if(empty($line)){
    unset($lines[$i]);
  } else {
    $lines[$i] = $line;
  }
}

function getLine(){
  global $lines;
  $text = $lines[0];
  $text = str_replace(array('"',':'),"",$text);
  return unaccent($text);
}

$text = getLine();

while(true){

  $cmd = trim(fgets($stdin));
  $args = explode(" ",$cmd);
  $cmd = $args[0];
  $args = array_slice($args, 1);

  if($cmd=='c'){
    $start = $args[0];
  }
  if($cmd=='j'){
    $length += !empty($args[0]) ? $args[0] : $step;
  }
  else if($cmd=='b') {
    $length -= !empty($args[0]) ? $args[0] : $step;
  }
  else if($cmd=='s') {
    $_index = $index <= 9 ? "0$index" : $index;
    shell_exec("sox tmp.wav 'output/$_index-$text.wav' trim $start $length");
    $from = $length + $start;
    shell_exec("sox tmp.wav tmp2.wav trim $from =".shell_exec("soxi -d tmp.wav"));
    shell_exec("mv tmp2.wav tmp.wav");
    $length = 1;
    $start = 0;
    $index++;
    $lines = array_slice($lines, 1);
    file_put_contents('input.txt', implode("\n",$lines));
    $text = getLine();
  } else if($cmd==''){

  }

  echo $text;
  echo PHP_EOL;
  shell_exec("play tmp.wav trim $start $length");

}

