# Sobre o Repositório

Bem-vindo ao repositório da **Plenatech** com **Contribuições para o Adianti Framework**! Este repositório é destinado a disponibilizar contribuições para a comunidade, nosso objetivo é compartilhar e colaborar com algumas melhorias que adicionam ou expandem as funcionalidades do Framework, que fizeram sentido para nossos projetos e que podem eventualmente serem úteis para outras pessoas ou empresas.

## Sobre os Diretórios

### TitansTemplate

**TitansTemplate** é um projeto interno da **Plenatech** que visa fornecer uma base unificada para todos os devs da empresa, a esse template foram adicionados diversos componentes e algumas modificações no framework para se adequar ao nosso cenário de utilização. Neste repositório esse diretório contém as alterações realizadas e disponibilizadas para a comunidade.

* Cada alteração será disponibilizada em um diretório com o nome do componente modificado
* Cada diretório de alteração terá um README com as informações pertinentes
* As alterações realizadas em componentes do framework sempre estão documentadas no código com um header e footer indicando o inicio e o fim da alteração
* Sempre que possível um exemplo ou teste será disponibilizado

### SiAdiante

O **SiAdiante** é uma iniciativa de disponibilizar um gerador de código Web para o Adianti Framework. Este projeto visa que o gerador de código seja dinâmico, permitindo criar Models e Controllers padrões e as customizadas pelo próprio dev. AINDA EM FASE DE DESENVOLVIMENTO.

### Dicas

O Diretório de dicas visa concentrar arquivos com dicas gerais de forma organizada.


## Como Contribuir

Estamos abertos a contribuições da comunidade! Siga os passos abaixo para contribuir:

1. **Fork o Repositório**: Clique no botão "Fork" no canto superior direito da página para criar uma cópia do repositório em sua conta do GitHub.
2. **Clone o Repositório**: Clone o repositório forkado para sua máquina local.

   ```bash
   git clone https://github.com/seu-usuario/adianti-community-contribs.git
   cd adianti-community-contribs
   ```
3. **Crie uma Branch**: Crie uma nova branch para sua contribuição.

   ```bash
   git checkout -b minha-contribuicao
   ```
4. **Faça as Modificações**: Faça suas modificações no código. Certifique-se de documentar todas as mudanças.
5. **Commit e Push**: Faça commit das suas mudanças e envie para o seu repositório forkado.

   ```bash
   git add .
   git commit -m "Descrição das mudanças"
   git push origin minha-contribuicao
   ```
6. **Abra um Pull Request**: Volte ao repositório original e abra um Pull Request com suas mudanças. Descreva detalhadamente o que foi alterado e como isso melhora ou contribui com o Adianti Framework.

## Documentação

Toda modificação enviada deve ser documentada no código. Isso ajuda a manter o código organizado e facilita a revisão das mudanças em atualizações futuras do framework.

Exemplo:

```php
###########################
### Inicio da alteração ###
###########################
function novaFuncionalidade() {
    // Código da nova funcionalidade
}
###########################
####  Fim da alteração ####
###########################
```
