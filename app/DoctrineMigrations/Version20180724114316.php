<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724114316 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE housing_notation ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD review_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553E2E969B FOREIGN KEY (review_id) REFERENCES housing_notation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_42C849553E2E969B ON reservation (review_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE housing_notation DROP description');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553E2E969B');
        $this->addSql('DROP INDEX UNIQ_42C849553E2E969B ON reservation');
        $this->addSql('ALTER TABLE reservation DROP review_id');
    }
}
