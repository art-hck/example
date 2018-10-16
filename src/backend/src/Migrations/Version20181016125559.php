<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016125559 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player ADD team_id INT DEFAULT NULL, DROP team_uid, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE native_name native_name VARCHAR(255) DEFAULT NULL, CHANGE is_left_handed is_left_handed TINYINT(1) DEFAULT NULL, CHANGE role role VARCHAR(255) DEFAULT NULL, CHANGE number number INT DEFAULT NULL, CHANGE contract_ext contract_ext DATETIME DEFAULT NULL, CHANGE twitter twitter VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player ADD team_uid INT NOT NULL, DROP team_id, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE native_name native_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE is_left_handed is_left_handed TINYINT(1) NOT NULL, CHANGE role role VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE number number INT NOT NULL, CHANGE contract_ext contract_ext DATETIME NOT NULL, CHANGE twitter twitter VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
