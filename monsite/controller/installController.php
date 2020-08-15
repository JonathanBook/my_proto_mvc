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
            `name` varchar(255) NOT NULL,
            `url` varchar(255) NOT NULL,
            `isgalerie` int(1) NOT NULL DEFAULT 0,
            `type` varchar(255) NOT NULL,
            `id_theme` int(11) NOT NULL DEFAULT 0,
            `info` text NOT NULL DEFAULT '',
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;");
        die();
    }
}
