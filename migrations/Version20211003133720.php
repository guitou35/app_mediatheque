<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211003133720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE adresse_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE personne_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE adresse (id INT NOT NULL, rue VARCHAR(255) NOT NULL, code_postal INT NOT NULL, ville VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE personne (id INT NOT NULL, adresse_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance VARCHAR(255) NOT NULL, compte_actived BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCEC9EFE7927C74 ON personne (email)');
        $this->addSql('CREATE INDEX IDX_FCEC9EF4DE7DC5C ON personne (adresse_id)');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_personne (role_id INT NOT NULL, personne_id INT NOT NULL, PRIMARY KEY(role_id, personne_id))');
        $this->addSql('CREATE INDEX IDX_251D2FDED60322AC ON role_personne (role_id)');
        $this->addSql('CREATE INDEX IDX_251D2FDEA21BD112 ON role_personne (personne_id)');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EF4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_personne ADD CONSTRAINT FK_251D2FDED60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_personne ADD CONSTRAINT FK_251D2FDEA21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE personne DROP CONSTRAINT FK_FCEC9EF4DE7DC5C');
        $this->addSql('ALTER TABLE role_personne DROP CONSTRAINT FK_251D2FDEA21BD112');
        $this->addSql('ALTER TABLE role_personne DROP CONSTRAINT FK_251D2FDED60322AC');
        $this->addSql('DROP SEQUENCE adresse_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE personne_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_personne');
    }
}
