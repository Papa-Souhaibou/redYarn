<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125105446 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence ADD is_deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE groupe_competence ADD is_deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE niveau ADD is_deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE profil_sortie ADD is_deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE referentiel ADD is_deleted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence DROP is_deleted');
        $this->addSql('ALTER TABLE groupe_competence DROP is_deleted');
        $this->addSql('ALTER TABLE niveau DROP is_deleted');
        $this->addSql('ALTER TABLE profil_sortie DROP is_deleted');
        $this->addSql('ALTER TABLE referentiel DROP is_deleted');
    }
}
