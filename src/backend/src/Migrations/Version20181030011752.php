<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181030011752 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C69A91CE2');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C9C4C13F6');
        $this->addSql('DROP INDEX IDX_232B318C9C4C13F6 ON game');
        $this->addSql('DROP INDEX IDX_232B318C69A91CE2 ON game');
        $this->addSql('ALTER TABLE game DROP home_team_id, DROP guest_team_id');
        $this->addSql('CREATE INDEX IDX_LEAGUE_NAME ON league (name)');
        $this->addSql('CREATE INDEX IDX_PLAY_TIME ON substitution (play_time)');
        $this->addSql('CREATE INDEX IDX_TEAM_NAME ON team (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game ADD home_team_id INT DEFAULT NULL, ADD guest_team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C69A91CE2 FOREIGN KEY (guest_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_232B318C9C4C13F6 ON game (home_team_id)');
        $this->addSql('CREATE INDEX IDX_232B318C69A91CE2 ON game (guest_team_id)');
        $this->addSql('DROP INDEX IDX_LEAGUE_NAME ON league');
        $this->addSql('DROP INDEX IDX_PLAY_TIME ON substitution');
        $this->addSql('DROP INDEX IDX_TEAM_NAME ON team');
    }
}
