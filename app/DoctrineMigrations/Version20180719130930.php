<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180719130930 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE housing_detail ADD icon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE housing_detail ADD CONSTRAINT FK_B5B1BEE554B9D732 FOREIGN KEY (icon_id) REFERENCES media__media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B5B1BEE554B9D732 ON housing_detail (icon_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE housing_detail DROP FOREIGN KEY FK_B5B1BEE554B9D732');
        $this->addSql('DROP INDEX UNIQ_B5B1BEE554B9D732 ON housing_detail');
        $this->addSql('ALTER TABLE housing_detail DROP icon_id');
    }
}
