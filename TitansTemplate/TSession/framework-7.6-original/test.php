<?php

use Adianti\Registry\TSession;

require_once 'init.php';

// tempo de inicio da execução
$start = microtime(true);

// inicia a sessão
new TSession();

// escreve 1000 variáveis na sessão
for ($n=0; $n<1000; $n++)
{
    TSession::setValue('var'.$n, str_repeat('x', 1000));
}

// recupera 1000 variáveis da sessão
for ($n=0; $n<1000; $n++)
{
    TSession::getValue('var'.$n);
}

// tempo de execução da lógica da chamada
sleep(5);

// fim da execução
$totalTime = microtime(true) - $start;

echo $totalTime;
echo "\n";