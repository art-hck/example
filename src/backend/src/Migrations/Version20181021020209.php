<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181021020209 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assist (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, goal_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_A1EE878E99E6F5DF (player_id), UNIQUE INDEX UNIQ_A1EE878E667D1AFE (goal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, game_id INT DEFAULT NULL, time INT NOT NULL, reason VARCHAR(255) NOT NULL, type INT NOT NULL, INDEX IDX_161498D399E6F5DF (player_id), INDEX IDX_161498D3E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, stadium_id INT NOT NULL, referee_id INT NOT NULL, home_team_id INT NOT NULL, guest_team_id INT DEFAULT NULL, day INT NOT NULL, date DATETIME NOT NULL, duration INT NOT NULL, score VARCHAR(6) DEFAULT NULL, status INT NOT NULL, updated DATETIME NOT NULL, INDEX IDX_232B318C58AFC4DE (league_id), INDEX IDX_232B318C7E860E36 (stadium_id), INDEX IDX_232B318C4A087CA2 (referee_id), INDEX IDX_232B318C9C4C13F6 (home_team_id), INDEX IDX_232B318C69A91CE2 (guest_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goal (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, game_id INT NOT NULL, time INT NOT NULL, score VARCHAR(6) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_FCDCEB2E99E6F5DF (player_id), INDEX IDX_FCDCEB2EE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, season INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tm_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stadium (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, attendance DOUBLE PRECISION NOT NULL, tm_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assist ADD CONSTRAINT FK_A1EE878E99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE assist ADD CONSTRAINT FK_A1EE878E667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C7E860E36 FOREIGN KEY (stadium_id) REFERENCES stadium (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C4A087CA2 FOREIGN KEY (referee_id) REFERENCES referee (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C69A91CE2 FOREIGN KEY (guest_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2E99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3E48FD905');
        $this->addSql('ALTER TABLE goal DROP FOREIGN KEY FK_FCDCEB2EE48FD905');
        $this->addSql('ALTER TABLE assist DROP FOREIGN KEY FK_A1EE878E667D1AFE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C58AFC4DE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C4A087CA2');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C7E860E36');
        $this->addSql('DROP TABLE assist');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE referee');
        $this->addSql('DROP TABLE stadium');
    }
}
