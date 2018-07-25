<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180723123435 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE money_movement');
        $this->addSql('ALTER TABLE payment_infos ADD payment_instruction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_infos ADD CONSTRAINT FK_B983B4DF8789B572 FOREIGN KEY (payment_instruction_id) REFERENCES payment_instructions (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B983B4DF8789B572 ON payment_infos (payment_instruction_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE money_movement (id INT AUTO_INCREMENT NOT NULL, payment_infos_id INT DEFAULT NULL, state VARCHAR(45) NOT NULL COLLATE utf8_unicode_ci, action VARCHAR(45) NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_15B766D9148C0EA2 (payment_infos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE money_movement ADD CONSTRAINT FK_15B766D9148C0EA2 FOREIGN KEY (payment_infos_id) REFERENCES payment_infos (id)');
        $this->addSql('ALTER TABLE payment_infos DROP FOREIGN KEY FK_B983B4DF8789B572');
        $this->addSql('DROP INDEX UNIQ_B983B4DF8789B572 ON payment_infos');
        $this->addSql('ALTER TABLE payment_infos DROP payment_instruction_id');
    }
}
