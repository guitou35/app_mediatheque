<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211009192102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE reservation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, personne_id INT NOT NULL, livre_id INT NOT NULL, date_reservation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_emprunt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_retour TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_42C84955A21BD112 ON reservation (personne_id)');
        $this->addSql('CREATE INDEX IDX_42C8495537D925CB ON reservation (livre_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495537D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE reservation_id_seq CASCADE');
        $this->addSql('DROP TABLE reservation');
    }
}
