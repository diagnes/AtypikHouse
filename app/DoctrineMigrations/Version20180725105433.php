<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725105433 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493C0C19A5');
        $this->addSql('ALTER TABLE user_wich_list_value DROP FOREIGN KEY FK_2B96EB72D69F3311');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE user_score_card');
        $this->addSql('DROP TABLE user_wich_list_value');
        $this->addSql('DROP TABLE user_wishlist');
        $this->addSql('DROP INDEX UNIQ_8D93D6493C0C19A5 ON user');
        $this->addSql('ALTER TABLE user DROP score_card');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL COLLATE utf8_unicode_ci, slug VARCHAR(45) NOT NULL COLLATE utf8_unicode_ci, score_max INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_score_card (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, score INT NOT NULL, grade VARCHAR(45) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_9B6BDF49A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_wich_list_value (id INT AUTO_INCREMENT NOT NULL, wish_list_id INT DEFAULT NULL, housing_id INT DEFAULT NULL, INDEX IDX_2B96EB72D69F3311 (wish_list_id), INDEX IDX_2B96EB72AD5873E3 (housing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_wishlist (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(125) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_7C6CCE31A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_score_card ADD CONSTRAINT FK_9B6BDF49A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_wich_list_value ADD CONSTRAINT FK_2B96EB72AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE user_wich_list_value ADD CONSTRAINT FK_2B96EB72D69F3311 FOREIGN KEY (wish_list_id) REFERENCES user_wishlist (id)');
        $this->addSql('ALTER TABLE user_wishlist ADD CONSTRAINT FK_7C6CCE31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD score_card INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493C0C19A5 FOREIGN KEY (score_card) REFERENCES user_score_card (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493C0C19A5 ON user (score_card)');
    }
}
