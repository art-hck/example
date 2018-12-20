<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181219040947 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE transfer (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, leftЕуteam_id INT DEFAULT NULL, join_team_id INT DEFAULT NULL, date DATE NOT NULL, price INT DEFAULT NULL, INDEX IDX_4034A3C099E6F5DF (player_id), INDEX IDX_4034A3C01329CA29 (leftЕуteam_id), INDEX IDX_4034A3C0FBC9B3CE (join_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C099E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C01329CA29 FOREIGN KEY (leftЕуteam_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE transfer ADD CONSTRAINT FK_4034A3C0FBC9B3CE FOREIGN KEY (join_team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE transfer');
    }
}
