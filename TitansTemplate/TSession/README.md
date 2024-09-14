# Alteração em TSession

A alteração deste componente, foi inicialmente documentada em: https://adiantiframework.com.br/forum/view_5828?multiplas-abas-e-carregamento-assincrono-de-pagina

Basicamente a alteração consiste em manter a sessão sempre fechada para escrita e aberta para leitura, de tal modo que não fique bloqueando outras execuções do PHP na mesma sessão, a sessão é reaberta para escrita na mesma execução sempre que TSession for gravar um valor.

O arquivo TSession.php detalha as alterações realizadas.

O diretório framework-7.6-original contém o Framework original.

O diretório framework-7.6-modificado contém o Framework com o arquivo TSession alterado.

## Arquivo de teste

Aos dois diretórios foi adicionado um arquivo chamado test.php para similar uma requisição de aplicação. Esse arquivo escreve e recupera 1000 valores na sessão, depois aguarda 5 segundos simulando o tempo de execução de alguma lógica da aplicação, ao final ele mostra o calculo do tempo de execução da chamada.

Conteúdo do test.php

```
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
```

## Diferenças dos dois diretórios

Abaixo a saída do diff dos dois diretórios:

```
diff -qr framework-7.6-original/ framework-7.6-modificado/
Os arquivos framework-7.6-original/lib/adianti/registry/TSession.php e framework-7.6-modificado/lib/adianti/registry/TSession.php são diferentes
```

## Resultado

Para testar a alteração, foi criado um simples script que realizada 5 requisições simultâneas para o framework original e para o modificado.

Teste realizado em ambiente com container usando:

* Imagem do SO: Rocky Linux EL9
* Servidor web: NGINX versão 1.26.2
* PHP: 8.3.11 (executando com PHP-FPM)

Resultado do teste:

```
./simpleTest.sh
Teste no framework original
ID da sessão: Dw7HJmXDzBNlxoGIEt7kQZ-s0wcdLSIVNuGHV8mqcpmVBe66%2CpqJTUBXcvxfP%2CVo
5.0006880760193
10.004546165466
15.009219884872
20.014985084534
25.018766880035
Tempo do teste no path TitansTemplate/TSession/framework-7.6-original: 25.038483724 segundos


Teste no framework modificado
ID da sessão: Ji2%2CjHpVdW-lxT%2Cg5Jye3f132FSacZXv%2CKJFCX6pbVY1o8nLLOQ4m0C6Im2dTDtU
5.9595668315887
5.9680571556091
6.0187909603119
6.0280878543854
6.0346930027008
Tempo do teste no path TitansTemplate/TSession/framework-7.6-modificado: 6.051308654 segundos

```

Notem quem ao final do teste temos um tempo total de **25 segs** no framework original, pois cada uma das chamas web tem de esperar a anterior ser finalizada, já no framework modificado o tempo de execução é de pouco mais de **6 segs** onde as requisições modificam e leem a sessão de forma simultânea, mas respeitando eventual lock.

## Atenção

Há alguns pontos que demandam atenção:

* Se sua aplicação tem muitas requests o load do servidor pode aumentar, pois anteriormente o lock de sessão era um freio para request simultâneas de um mesmo usuário.
* Deve-se estar atento para eventuais manipulações da sessão fora do contexto do framework, manipular `$_SESSION` diretamente não é recomendado e irá falhar, pois não será persistido na sessão. Se for manipular diretamente, necessário usar start e commit.
* Deve-se estar atento manipulação concorrente de uma mesma variável de sessão (se ela for pertinente para mais de uma execução). Imagine o seguinte:
  * Request 1 seta a var1 com o valor 100
  * Request 2 recupera a var1 com o valor de 100 e atualiza o valor de var1 para 1000
  * Se a Request1 ainda estiver em execução, e for novamente recuperar var1, ela terá o valor de 100 ao invés de 1000, pois em TSession não estamos dando um session_start() antes de recuperar uma variável (isso popularia `$_SESSION` com valores atualizados, mas demandaria uma carga que julgamos desnecessária, se esse for seu cenário, só implementar em `getValue`.
