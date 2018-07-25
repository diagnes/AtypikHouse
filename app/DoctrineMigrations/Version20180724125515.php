<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724125515 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE housing_notation DROP FOREIGN KEY FK_976E0B74A76ED395');
        $this->addSql('ALTER TABLE housing_notation DROP FOREIGN KEY FK_976E0B74AD5873E3');
        $this->addSql('DROP INDEX IDX_976E0B74AD5873E3 ON housing_notation');
        $this->addSql('DROP INDEX IDX_976E0B74A76ED395 ON housing_notation');
        $this->addSql('ALTER TABLE housing_notation ADD reservation_id INT DEFAULT NULL, DROP housing_id, DROP user_id');
        $this->addSql('ALTER TABLE housing_notation ADD CONSTRAINT FK_976E0B74B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_976E0B74B83297E7 ON housing_notation (reservation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE housing_notation DROP FOREIGN KEY FK_976E0B74B83297E7');
        $this->addSql('DROP INDEX UNIQ_976E0B74B83297E7 ON housing_notation');
        $this->addSql('ALTER TABLE housing_notation ADD user_id INT DEFAULT NULL, CHANGE reservation_id housing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE housing_notation ADD CONSTRAINT FK_976E0B74A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE housing_notation ADD CONSTRAINT FK_976E0B74AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('CREATE INDEX IDX_976E0B74AD5873E3 ON housing_notation (housing_id)');
        $this->addSql('CREATE INDEX IDX_976E0B74A76ED395 ON housing_notation (user_id)');
    }
}
