<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117112054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, INDEX IDX_818CC9D4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsorship_contract (id INT AUTO_INCREMENT NOT NULL, sponsor_id INT DEFAULT NULL, team_id INT DEFAULT NULL, tournament_id INT DEFAULT NULL, level VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', terms LONGTEXT NOT NULL, INDEX IDX_AEA479A12F7FB51 (sponsor_id), INDEX IDX_AEA479A296CD8AE (team_id), INDEX IDX_AEA479A33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sponsor ADD CONSTRAINT FK_818CC9D4A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE sponsorship_contract ADD CONSTRAINT FK_AEA479A12F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsor (id)');
        $this->addSql('ALTER TABLE sponsorship_contract ADD CONSTRAINT FK_AEA479A296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE sponsorship_contract ADD CONSTRAINT FK_AEA479A33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sponsor DROP FOREIGN KEY FK_818CC9D4A76ED395');
        $this->addSql('ALTER TABLE sponsorship_contract DROP FOREIGN KEY FK_AEA479A12F7FB51');
        $this->addSql('ALTER TABLE sponsorship_contract DROP FOREIGN KEY FK_AEA479A296CD8AE');
        $this->addSql('ALTER TABLE sponsorship_contract DROP FOREIGN KEY FK_AEA479A33D1A3E7');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('DROP TABLE sponsorship_contract');
    }
}
