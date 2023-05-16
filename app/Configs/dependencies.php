<?php

use banana\Container;
use banana\Controllers\AuthorController;
use banana\Controllers\MagazineController;
use banana\Service\AuthorsMagazineService;
use banana\Service\MagazineService;
use banana\FileLogger;
use banana\LoggerInterface;
use banana\Repository\AuthorMagazinesRepository;
use banana\Repository\AuthorRepository;
use banana\Repository\MagazineRepository;


return [
    AuthorController::class => function (Container $container) {
        $authorRepository = $container->get(AuthorRepository::class);

        return new AuthorController($authorRepository);
    },

    MagazineController::class => function (Container $container) {
        $magazineRepository = $container->get(MagazineRepository::class);
        $authorMagazineRepository = $container->get(AuthorMagazinesRepository::class);
        $authorRepository = $container->get(AuthorRepository::class);
        $magazineService = $container->get(MagazineService::class);
        $authorsMagazineService = $container->get(AuthorsMagazineService::class);

        return new MagazineController($magazineRepository, $authorMagazineRepository, $authorRepository, $magazineService, $authorsMagazineService);
    },

    MagazineRepository::class => function (Container $container){
        $connection = $container->get('db');

        return new MagazineRepository($connection);
    },

    AuthorRepository::class => function (Container $container){
        $connection = $container->get('db');

        return new AuthorRepository($connection);
    },

    AuthorMagazinesRepository::class => function (Container $container){
        $connection = $container->get('db');

        return new AuthorMagazinesRepository($connection);
    },

    MagazineService::class => function (){
        return new MagazineService();
    },

    AuthorsMagazineService::class => function (Container $container){
        $authorMagazineRepository = $container->get(AuthorMagazinesRepository::class);
        $authorRepository = $container->get(AuthorRepository::class);

        return new AuthorsMagazineService($authorMagazineRepository, $authorRepository);
    },

    LoggerInterface::class => function () {

        return new FileLogger();
    },

    'db' => function(Container $container) {
        $settings = $container->get('settings');
        $host = $settings['db']['host'];
        $name = $settings['db']['dbname'];
        $user = $settings['db']['username'];
        $password = $settings['db']['password'];

        return new PDO("pgsql:host={$host};dbname={$name}", "{$user}", "{$password}");
    }

];