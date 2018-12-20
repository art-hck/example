<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181219184806 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C01329CA29');
        $this->addSql('DROP INDEX IDX_4034A3C01329CA29 ON transfer');
        $this->addSql('ALTER TABLE transfer CHANGE leftЕуteam_id left_team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C03EBEDDC8 FOREIGN KEY (left_team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_4034A3C03EBEDDC8 ON transfer (left_team_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transfer DROP FOREIGN KEY FK_4034A3C03EBEDDC8');
        $this->addSql('DROP INDEX IDX_4034A3C03EBEDDC8 ON transfer');
        $this->addSql('ALTER TABLE transfer CHANGE left_team_id leftЕуteam_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C01329CA29 FOREIGN KEY (leftЕуteam_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_4034A3C01329CA29 ON transfer (leftЕуteam_id)');
    }
}
