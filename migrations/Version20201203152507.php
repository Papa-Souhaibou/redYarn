<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203152507 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief DROP FOREIGN KEY FK_1FBB1007B3E9C81');
        $this->addSql('DROP INDEX IDX_1FBB1007B3E9C81 ON brief');
        $this->addSql('ALTER TABLE brief DROP niveau_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief ADD niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brief ADD CONSTRAINT FK_1FBB1007B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1FBB1007B3E9C81 ON brief (niveau_id)');
    }
}
