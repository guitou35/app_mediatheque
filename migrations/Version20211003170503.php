<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211003170503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_personne DROP CONSTRAINT fk_251d2fded60322ac');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_personne');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_personne (role_id INT NOT NULL, personne_id INT NOT NULL, PRIMARY KEY(role_id, personne_id))');
        $this->addSql('CREATE INDEX idx_251d2fdea21bd112 ON role_personne (personne_id)');
        $this->addSql('CREATE INDEX idx_251d2fded60322ac ON role_personne (role_id)');
        $this->addSql('ALTER TABLE role_personne ADD CONSTRAINT fk_251d2fded60322ac FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_personne ADD CONSTRAINT fk_251d2fdea21bd112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personne ALTER roles TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE personne ALTER roles DROP DEFAULT');
    }
}
