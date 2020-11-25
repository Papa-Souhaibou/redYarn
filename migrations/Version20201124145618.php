<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124145618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_competence_referentiel (groupe_competence_id INT NOT NULL, referentiel_id INT NOT NULL, INDEX IDX_1569CFDB89034830 (groupe_competence_id), INDEX IDX_1569CFDB805DB139 (referentiel_id), PRIMARY KEY(groupe_competence_id, referentiel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, programme VARCHAR(255) NOT NULL, critere_admission VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_competence_referentiel ADD CONSTRAINT FK_1569CFDB89034830 FOREIGN KEY (groupe_competence_id) REFERENCES groupe_competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_competence_referentiel ADD CONSTRAINT FK_1569CFDB805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_competence_referentiel DROP FOREIGN KEY FK_1569CFDB805DB139');
        $this->addSql('DROP TABLE groupe_competence_referentiel');
        $this->addSql('DROP TABLE referentiel');
    }
}
