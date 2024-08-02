## Projeto usando Vue e Laravel com Docker e MySQL

## Get Started
Configurar as envs (src/backend/.env) de acordo com o .env.example

Configurado a env, para rodar o projeto digite

```sh
source aliases.sh
```

Após, instale os pacotes do node e suba o servidor com o comando:

```sh
start
```


Ao ser concluido, os seguintes comandos podem ser utilizados:

```sh
up

down
```

Up - Liga o servidor docker

Down - Desliga o servidor docker

OBS: o sistema possui RBAC, onde a role 'admin' tem permissões adicionais, quanto outras roles (user) não, para testar a visão admin é necessário configurar o user que deseja pelo banco de dados