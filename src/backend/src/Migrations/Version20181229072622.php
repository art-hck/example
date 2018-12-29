<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181229072622 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE league ADD country_id INT DEFAULT NULL, ADD preview VARCHAR(255) DEFAULT NULL, CHANGE season tm_id INT NOT NULL');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_3EB4C318F92F3E70 ON league (country_id)');
        $this->addSql('DROP INDEX IDX_PLAY_TIME ON substitution');
        $this->addSql('CREATE INDEX IDX_GTP ON substitution (game_id, play_time, player_id)');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F58AFC4DE');
        $this->addSql('DROP INDEX IDX_C4E0A61F58AFC4DE ON team');
        $this->addSql('ALTER TABLE team DROP league_id');
        $this->addSql('CREATE INDEX IDX_GT ON team_game (game_id, team_id)');
        $this->addSql('CREATE INDEX IDX_TG ON team_game (team_id, game_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE league DROP FOREIGN KEY FK_3EB4C318F92F3E70');
        $this->addSql('DROP INDEX IDX_3EB4C318F92F3E70 ON league');
        $this->addSql('ALTER TABLE league DROP country_id, DROP preview, CHANGE tm_id season INT NOT NULL');
        $this->addSql('DROP INDEX IDX_GTP ON substitution');
        $this->addSql('CREATE INDEX IDX_PLAY_TIME ON substitution (play_time)');
        $this->addSql('ALTER TABLE team ADD league_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F58AFC4DE ON team (league_id)');
        $this->addSql('DROP INDEX IDX_GT ON team_game');
        $this->addSql('DROP INDEX IDX_TG ON team_game');
    }
}
