<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725162346 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog ADD image_id INT DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C01551433DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C01551433DA5256D ON blog (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C01551433DA5256D');
        $this->addSql('DROP INDEX UNIQ_C01551433DA5256D ON blog');
        $this->addSql('ALTER TABLE blog DROP image_id, DROP deleted_at');
    }
}
