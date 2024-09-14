#!/bin/bash

# IP do Container
ipContainer="10.88.0.2"

# Função para realizar o teste
function testTSession()
{
    # Define um arquivo temporário para armazenar os cookies de cada teste individualmente
    local cookieFile=$(mktemp)

    # recebe o Path
    local testPath=$1

    # Realiza a primeira requisição para obter o cookie de sessão
    curl -k -c $cookieFile "https://${ipContainer}/${testPath}/index.php" 1>/dev/null 2>&1

    # Recupera o PHPSESSID
    local sessionID=$(cat $cookieFile | grep PHPSESSID | awk '{print $7}')
    echo "ID da sessão: ${sessionID}"

    # Recupera o tempo de inicio
    timeStart=$(date +%s.%N)

    # Realiza 5 request simultâneas para a página de teste usando o cookie de sessão
    curl -k -b $cookieFile "https://${ipContainer}/${testPath}/test.php" &
    curl -k -b $cookieFile "https://${ipContainer}/${testPath}/test.php" &
    curl -k -b $cookieFile "https://${ipContainer}/${testPath}/test.php" &
    curl -k -b $cookieFile "https://${ipContainer}/${testPath}/test.php" &
    curl -k -b $cookieFile "https://${ipContainer}/${testPath}/test.php" &

    # Aguarda todas as requisições terminarem
    wait

    # Recupera o tempo de fim
    timeEnd=$(date +%s.%N)

    # Calcula o tempo total
    timeTotal=$(echo "$timeEnd - $timeStart" | bc | sed 's/^\./0./')

    echo "Tempo do teste no path ${testPath}: ${timeTotal} segundos"
}

# Teste no framework original
echo "Teste no framework original"
testTSession "TitansTemplate/TSession/framework-7.6-original"

# Teste no framework modificado
echo -e "\n\nTeste no framework modificado"
testTSession "TitansTemplate/TSession/framework-7.6-modificado"
