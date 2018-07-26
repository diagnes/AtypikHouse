<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180726170547 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_notification ADD target_user_id INT DEFAULT NULL, ADD message LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE state state TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC86C066AFE FOREIGN KEY (target_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3F980AC86C066AFE ON user_notification (target_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_notification DROP FOREIGN KEY FK_3F980AC86C066AFE');
        $this->addSql('DROP INDEX IDX_3F980AC86C066AFE ON user_notification');
        $this->addSql('ALTER TABLE user_notification DROP target_user_id, DROP message, DROP created_at, DROP updated_at, CHANGE state state VARCHAR(45) NOT NULL COLLATE utf8_unicode_ci');
    }
}
