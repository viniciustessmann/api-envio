## API Melhor Transportadora

## Configuração inicial
- rename .env.example para .env
- Defina as configurações de banco de dados no arquivo .env

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=nome_database
- DB_USERNAME=user
- DB_PASSWORD=password

## Comandos

- Execute o "composer install" para obter as dependencias
- Execute o comando no terminal "php artisan key:generate"
- Execute o comando no terminal "php artisan serve" para inicial o servidor do Laravel
- Execute o comando no terminal "php artisan migrate" para importar as tables do banco de dados


## API
Para acessar a API basta fazer uma requisição POST com os paramentros abaixo:
- origem  (CEP, exempo 96020360)
- destino (CEP, exempo 96020360)
- largura (largura em centimetos, exemplo 40)
- altura (altura em centimetros, exemplo 20.5)
- comprimento (comprimento em centimetros, exemplo 35.2)
- peso (peso em piso, exemplo 7.76, 0.25)
- valor (exemplo 19.99)
- ar (exemplo true/false)
- mao (exemplo true/false)
- seguro (exemplo true/false)

Para testes utilizei o software POSTMAN para enviar as requisições.

## Observações
Foi convertido o arquilo XLS para 3 arquivos CSV 
- codes.csv
- economico.csv
- expresso.csv