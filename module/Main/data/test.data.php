<?php

return array(
    'image_id_seq' => array(
        'create' => 'CREATE SEQUENCE schema_blog.image_id_seq;',
        'drop' => 'DROP SEQUENCE schema_blog.image_id_seq CASCADE;'
    ),
    'image' => array(
        'create' => "CREATE TABLE schema_blog.image (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.image_id_seq'),
                url VARCHAR(255) NOT NULL,
                CONSTRAINT id_image PRIMARY KEY (id));",
        'drop' => 'DROP TABLE schema_blog.image CASCADE;',
    ),
    'user_id_seq' => array(
        'create' => 'CREATE SEQUENCE schema_blog.user_id_seq;',
        'drop' => 'DROP SEQUENCE schema_blog.user_id_seq CASCADE;'
    ),
    'user' => array(
        'create' => "CREATE TABLE schema_blog.user (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.user_id_seq'),
                login VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(100) NOT NULL,
                CONSTRAINT id_user PRIMARY KEY (id)
				);",
        'drop' => 'DROP TABLE schema_blog.user CASCADE;',
    ),
    'categorie_id_seq' => array(
        'create' => 'CREATE SEQUENCE schema_blog.categorie_id_seq;',
        'drop' => 'DROP SEQUENCE schema_blog.categorie_id_seq CASCADE;'
    ),
    'categorie' => array(
        'create' => "CREATE TABLE schema_blog.categorie (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.categorie_id_seq'),
                description VARCHAR(255) NOT NULL,
                CONSTRAINT id_categorie PRIMARY KEY (id));",
        'drop' => 'DROP TABLE schema_blog.categorie CASCADE;',
    ),
    'post_id_seq' => array(
        'create' => 'CREATE SEQUENCE schema_blog.post_id_seq;',
        'drop' => 'DROP SEQUENCE schema_blog.post_id_seq CASCADE;'
    ),
    'post' => array(
        'create' => "CREATE TABLE schema_blog.post (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.post_id_seq'),
                id_user INTEGER NOT NULL,
                title VARCHAR(255) NOT NULL,
                description VARCHAR(400) NOT NULL,
                text VARCHAR(4000) NOT NULL,
                date_post TIMESTAMP NOT NULL,
                CONSTRAINT id_post PRIMARY KEY (id));",
        'drop' => 'DROP TABLE schema_blog.post CASCADE'
    ),
    'comment_id_seq' => array(
        'create' => 'CREATE SEQUENCE schema_blog.comment_id_seq;',
        'drop' => 'DROP SEQUENCE schema_blog.comment_id_seq CASCADE;'
    ),
    'comment' => array(
        'create' => "CREATE TABLE schema_blog.comment (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.comment_id_seq'),
                id_post INTEGER NOT NULL,
                description VARCHAR(4000) NOT NULL,
                email VARCHAR(255) NOT NULL,
                date TIMESTAMP NOT NULL,
                CONSTRAINT id_comment PRIMARY KEY (id));",
        'drop' => 'DROP TABLE schema_blog.comment CASCADE;'
        
    ),
    'post_categories' => array(
        'create' => 'CREATE TABLE schema_blog.post_categories (
                id_post INTEGER NOT NULL,
                id_categorie INTEGER NOT NULL,
                CONSTRAINT id_postg_categories PRIMARY KEY (id_post, id_categorie));',
        'drop' => 'DROP TABLE schema_blog.post_categories;'
    ),
     
);
