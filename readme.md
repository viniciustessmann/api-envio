## API Melhor Transportadora

## Configuração inicial
- rename .env.example para .env
- Defina as configurações de banco de dados no arquivo .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_database
DB_USERNAME=user
DB_PASSWORD=password

Execute o "composer install" para obter as dependencias
Execute o comando no terminal "php artisan key:generate"
Execute o comando no terminal "php artisan serve" para inicial o servidor do Laravel
Execute o comando no terminal "php artisan migrate" para importar as tables do banco de dados


## API
Para acessar a API basta fazer uma requisição POST com os paramentros abaixo:
- origem 
- destino
- largura
- altura
- comprimento
- peso
- valor
- ar
- mao
- seguro