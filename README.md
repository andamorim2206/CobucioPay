Cobucio Pay
---------------------------------------------------------

Cobucio Pay é um sistema de transações financeiras que permite aos usuários gerenciar seus saldos, visualizar extratos detalhados e realizar transferências de forma simples e segura. O sistema oferece funcionalidades de estorno para transferências, mantendo o controle e a transparência das operações, tudo integrado com autenticação via token JWT.

Setup Docker Laravel 11 com PHP 8.3
------------------------------------------------------------
Clone o Repositorio

       git@github.com:andamorim2206/CobucioPay.git
Instala o . env

        cp .env.example .env
Suba os container

        docker-compose up -d --build
Acessar o container

        docker exec -it laravel_app bash
Instalar as Dependencias(dentro container)

        composer install

Chave do Laravel(ainda dentro do container)

        php artisan key:generate

Rodar as permissoes (ainda dentro do container)

    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
Rodar a migrate e seeder (Dentro do Container)

        php artisan migrate --seed

Apos isso saia do container e acesse:

        localhost:8080 (aplicação)
        localhost8081 (phpmyadmin)



