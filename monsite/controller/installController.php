<?php

class installController extends Controller
{
/* (FR)Genera les table de base Que le MVC a besoin  */
    function inittable()
    {

        $this->loadModel('User');

        $this->User->connectQuery("CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `login` varchar(255) DEFAULT NULL,
            `name` varchar(255) NOT NULL,
            `password` varchar(255) DEFAULT NULL,
            `email` varchar(255) NOT NULL,
            `validatekey` varchar(255) NOT NULL DEFAULT '0',
            `validate` int(1) NOT NULL DEFAULT 0,
            `avatar` varchar(255) NOT NULL,
            `role` varchar(255) NOT NULL DEFAULT 'user',
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;");

        $this->User->connectQuery("CREATE TABLE IF NOT EXISTS `medias` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) DEFAULT NULL,
        `file` varchar(255) DEFAULT NULL,
        `info` varchar(255) NOT NULL,
        `post_id` int(11) DEFAULT NULL,
        `mediascol` varchar(45) DEFAULT NULL,
        `type` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `fk_medias_posts_idx` (`post_id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
        COMMIT;");
        die();
    }
}
