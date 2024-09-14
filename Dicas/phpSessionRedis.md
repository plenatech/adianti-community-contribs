## Configurando o PHP para usar as sessões no Redis

Por padrão o PHP salva os dados da sessão em arquivos, geralmente armazenados em `/var/lib/php/session/`. Isso geralmente atende a demanda e não é um problema para a maioria das aplicações e ambienbte.

Aconcete que eventualmente você pode precisar lidar com muitas requisições, precisar de mais performance ou ainda, precisar lidar com multiplos servidores balanceando as conexões de sua aplicação, de tal modo que a sessão do usuário o acompanhe por qualquer servior que o atenda, sendo esse último caso o mais crítico, pois as sessões e arquivos ficam disponiveis apenas no mesmo servidor.

Existe mais de uma forma de lidar com esse desafio, abaixo iremos demostrar como usar o Redis, um banco de dados em memória, do tipo chave/valor de alta performance, flexibilidade.

Para esse exemplo estamos usando um ambiente com container com:

* Imagem do SO: Rocky Linux EL9
* Servidor web: NGINX versão 1.26.2
* PHP: 8.3.11 (executando com PHP-FPM)
* Redis: 7.2.5

Os passos descritos abaixo foram executado em um EL9, mas com alguma facilidade devem ser portaveis para outras distros:

Instale o PHP com suporte ao Redis:

```
microdnf install php-fpm php-redis
systemctl restart php-fpm
```

Cheque se o módulo foi instalado e esta disponível:

```
php -m | grep redis
```

Verifique o backend de sessão atual:

```
php -i | grep session.save
# session.save_handler => files => files
# session.save_path => no value => no value
```

Instale o Redis:

```
microdnf install redis
```

Se você for usar um Redis centralizado para mais de um servidor (o que geralmente é o caso) ajuste o conf para aceitar conexões remotas:

```
vim /etc/redis/redis.conf
# Libere conexões remotas ajustando a opção bind para 0.0.0.0
# Se não for usar autenticação (NÃO RECOMENDADO, MAS VÁLIDO PARA TESTES) ajuste a opção protected-mode para no
# Se desejar autenticar os acessos remotos Ajuste a opção requirepass com a sua senha
```

Existem muitas opções que podem ser exploradas no Redis, recomendo a leitura da documentação.

Reinicie o Redis:

```
systemctl restart redis
```

Teste o acesso via command line

```
redis-cli -h 127.0.0.1 -p 6379 -a sua_senha_segura
# Uma vez dentro da conexão envie o comando ping e veja se recebe um PONG de retorno
```

Configurando o PHP para usar o Redis:

```
# Edite o arquivo /etc/php.ini e adicione as linhas abaixo ao final do arquivo:
# session.save_handler = redis
# session.save_path = "tcp://127.0.0.1:6379?auth=suasenha&timeout=5&persistent=1&weight=1&database=0&prefix=phpSession_&read_timeout=5&name=PHP_REDIS_SESSION"
```

Entendendo os parâmetros:

* auth: senha para autenticar no servidor Redis
* timeout: tempo limite para a conexão em segundos
* persistent: como usamos php-fpm ativa conexões persistentes com o Redis
* weight: peso dessa conexão, pois poderia usar um cluster com mais de um servidor
* database: número da database usada, por padrão o Redis vai da database 0 até 15
* prefix: prefixo a ser adicionado a cada uma das chaves inseridas
* read_timeout: tempo limite para leitura em segundos
* name: descrição da conexão, util para logs e debug


Ajuste o PHP-FPM:

```
vim /etc/php-fpm.d/www.conf
# No final do arquivo comente as linhas que setam o tipo de sessão, assim serão usadas as opções do php.ini
# Se usar mais de uma pool do PHP-FPM, você pode ajustar os parâmetros diretamenteo no conf da pool do PHP-FPM.
```


Reinicie o PHP-FPM:

```
systemctl restart php-fpm
```

Cheque se o backend de sessão foi atualizado:

```
php -i | grep session.save
session.save_handler => redis => redis
session.save_path => tcp://127.0.0.1:6379 => tcp://127.0.0.1:6379
```

Teste sua aplicação e veja se as chaves foram criadas no Redis:

```
redis-cli -h 127.0.0.1 -p 6379 -a sua_senha_segura
# Uma vez dentro da conexão liste as chaves com o comando abaixo:
SCAN 0
# Deve econtrar uma saida similar a:
# PHPREDIS_SESSION:pUFtS,kKwBPfJ0FcW-d0WhhHMLT2-6Q8K1LPYTVa2f9wmIkqUkcm2FThq7tjtgYE
```
