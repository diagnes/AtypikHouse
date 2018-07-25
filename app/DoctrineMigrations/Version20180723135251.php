<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180723135251 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payment_token (hash VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', after_url LONGTEXT DEFAULT NULL, target_url LONGTEXT NOT NULL, gateway_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(hash)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_infos DROP FOREIGN KEY FK_B983B4DF8789B572');
        $this->addSql('DROP INDEX UNIQ_B983B4DF8789B572 ON payment_infos');
        $this->addSql('ALTER TABLE payment_infos ADD number VARCHAR(255) DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD client_email VARCHAR(255) DEFAULT NULL, ADD client_id VARCHAR(255) DEFAULT NULL, ADD currency_code VARCHAR(255) DEFAULT NULL, ADD details LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', CHANGE payment_instruction_id total_amount INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE payment_token');
        $this->addSql('ALTER TABLE payment_infos DROP number, DROP description, DROP client_email, DROP client_id, DROP currency_code, DROP details, CHANGE total_amount payment_instruction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_infos ADD CONSTRAINT FK_B983B4DF8789B572 FOREIGN KEY (payment_instruction_id) REFERENCES payment_instructions (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B983B4DF8789B572 ON payment_infos (payment_instruction_id)');
    }
}
