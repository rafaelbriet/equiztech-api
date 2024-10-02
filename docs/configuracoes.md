# Configurações

Para que o aplicativo funcione é necessário fazer algumas configurações dependendo do ambiente em que ele está rodando (ex: desenvolvimento e produção).

O aplicativo depende de dois arquivos diferentes. 

O primeiro, `.env` contém configurações utilizadas pelo backend e pela API. 

O segundo, `config.js` contém configurações utilizadas pelo dashboard.

Ambos os arquivo devem existir na raiz do projeto para que ele funcione corretamente.

## Exemplo do `.env`

```Dotenv
JWT_KEY=CHAVE_SECRETA
BASE_URL=http://localhost/equiztech
DB_HOSTNAME=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_NAME=equiztech_api
```

`JWT_KEY`: Chave secreta utilizada para encriptar senhas antes de serem salvas no banco de dados.

`BASE_URL`: URL base do ambiente em o aplicativo está rodando (ex: http:localhost/equiztech).

`DB_HOSTNAME`: Servidor em que o banco de dados está hospedado.

`DB_USERNAME`: Nome do usuário do banco de dados.

`DB_PASSWORD`: Senha do banco de dados.

`DB_NAME`: Nome do banco de dados.

## Exemplo do `config.js`

```JS
const CONFIG = {
	base_url: 'http://localhost/equiztech',
};
```

`base_url`: URL base do ambiente em o aplicativo está rodando (ex: http:localhost/equiztech).