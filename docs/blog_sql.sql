CREATE SCHEMA schema_blog;

CREATE SEQUENCE schema_blog.categorie_id_seq;

CREATE TABLE schema_blog.categorie (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.categorie_id_seq'),
                description VARCHAR(255) NOT NULL,
                CONSTRAINT id_categorie PRIMARY KEY (id)
);


ALTER SEQUENCE schema_blog.categorie_id_seq OWNED BY schema_blog.categorie.id;

CREATE SEQUENCE schema_blog.user_id_seq;

CREATE TABLE schema_blog.user (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.user_id_seq'),
                login VARCHAR(100) NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(100) NOT NULL,
                CONSTRAINT id_user PRIMARY KEY (id)
);


ALTER SEQUENCE schema_blog.user_id_seq OWNED BY schema_blog.user.id;

CREATE SEQUENCE schema_blog.post_id_seq;

CREATE TABLE schema_blog.post (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.post_id_seq'),
                id_user INTEGER NOT NULL,
                title VARCHAR(255) NOT NULL,
                description VARCHAR(400) NOT NULL,
                text VARCHAR(4000) NOT NULL,
                date_post TIMESTAMP NOT NULL,
                CONSTRAINT id_post PRIMARY KEY (id)
);


ALTER SEQUENCE schema_blog.post_id_seq OWNED BY schema_blog.post.id;

CREATE SEQUENCE schema_blog.comment_id_seq;

CREATE TABLE schema_blog.comment (
                id INTEGER NOT NULL DEFAULT nextval('schema_blog.comment_id_seq'),
                id_post INTEGER NOT NULL,
                description VARCHAR(4000) NOT NULL,
                email VARCHAR(255) NOT NULL,
                date TIMESTAMP NOT NULL,
                CONSTRAINT id_comment PRIMARY KEY (id)
);


ALTER SEQUENCE schema_blog.comment_id_seq OWNED BY schema_blog.comment.id;

CREATE TABLE schema_blog.post_categories (
                id_post INTEGER NOT NULL,
                id_categorie INTEGER NOT NULL,
                CONSTRAINT id_postg_categories PRIMARY KEY (id_post, id_categorie)
);


ALTER TABLE schema_blog.post_categories ADD CONSTRAINT categorie_post_categories_fk
FOREIGN KEY (id_categorie)
REFERENCES schema_blog.categorie (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE schema_blog.post ADD CONSTRAINT user_post_fk
FOREIGN KEY (id_user)
REFERENCES schema_blog.user (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE schema_blog.post_categories ADD CONSTRAINT post_post_categories_fk
FOREIGN KEY (id_post)
REFERENCES schema_blog.post (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE schema_blog.comment ADD CONSTRAINT post_comment_fk
FOREIGN KEY (id_post)
REFERENCES schema_blog.post (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;
