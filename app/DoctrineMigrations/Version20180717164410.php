<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180717164410 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP deleted_at');
        $this->addSql('ALTER TABLE user_personal_infos DROP INDEX IDX_DD161B223DA5256D, ADD UNIQUE INDEX UNIQ_DD161B223DA5256D (image_id)');
        $this->addSql('ALTER TABLE user_personal_infos CHANGE birth_date birth_date DATE DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_personal_infos DROP INDEX UNIQ_DD161B223DA5256D, ADD INDEX IDX_DD161B223DA5256D (image_id)');
        $this->addSql('ALTER TABLE user_personal_infos CHANGE birth_date birth_date DATETIME DEFAULT NULL');
    }
}
