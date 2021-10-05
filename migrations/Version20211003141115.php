<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211003141115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE auteur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE genre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE livre_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE auteur (id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE genre (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE genre_livre (genre_id INT NOT NULL, livre_id INT NOT NULL, PRIMARY KEY(genre_id, livre_id))');
        $this->addSql('CREATE INDEX IDX_1165505C4296D31F ON genre_livre (genre_id)');
        $this->addSql('CREATE INDEX IDX_1165505C37D925CB ON genre_livre (livre_id)');
        $this->addSql('CREATE TABLE livre (id INT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, date_parution VARCHAR(255) NOT NULL, image BYTEA DEFAULT NULL, description VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC634F9960BB6FE6 ON livre (auteur_id)');
        $this->addSql('ALTER TABLE genre_livre ADD CONSTRAINT FK_1165505C4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genre_livre ADD CONSTRAINT FK_1165505C37D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F9960BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE livre DROP CONSTRAINT FK_AC634F9960BB6FE6');
        $this->addSql('ALTER TABLE genre_livre DROP CONSTRAINT FK_1165505C4296D31F');
        $this->addSql('ALTER TABLE genre_livre DROP CONSTRAINT FK_1165505C37D925CB');
        $this->addSql('DROP SEQUENCE auteur_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE genre_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE livre_id_seq CASCADE');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_livre');
        $this->addSql('DROP TABLE livre');
    }
}
