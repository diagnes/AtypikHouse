<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724112641 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE housing_notation_value (id INT AUTO_INCREMENT NOT NULL, notation_id INT DEFAULT NULL, notation_type_id INT DEFAULT NULL, value INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5389E42B9680B7F7 (notation_id), INDEX IDX_5389E42BAFD62CF8 (notation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE housing_notation_value ADD CONSTRAINT FK_5389E42B9680B7F7 FOREIGN KEY (notation_id) REFERENCES housing_notation (id)');
        $this->addSql('ALTER TABLE housing_notation_value ADD CONSTRAINT FK_5389E42BAFD62CF8 FOREIGN KEY (notation_type_id) REFERENCES housing_notation_type (id)');
        $this->addSql('ALTER TABLE housing_notation DROP FOREIGN KEY FK_976E0B74AFD62CF8');
        $this->addSql('DROP INDEX IDX_976E0B74AFD62CF8 ON housing_notation');
        $this->addSql('ALTER TABLE housing_notation DROP notation_type_id, DROP value');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE housing_notation_value');
        $this->addSql('ALTER TABLE housing_notation ADD notation_type_id INT DEFAULT NULL, ADD value INT NOT NULL');
        $this->addSql('ALTER TABLE housing_notation ADD CONSTRAINT FK_976E0B74AFD62CF8 FOREIGN KEY (notation_type_id) REFERENCES housing_notation_type (id)');
        $this->addSql('CREATE INDEX IDX_976E0B74AFD62CF8 ON housing_notation (notation_type_id)');
    }
}
