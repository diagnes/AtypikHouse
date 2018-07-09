<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180703131427 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street_number VARCHAR(10) DEFAULT NULL, address VARCHAR(100) NOT NULL, city VARCHAR(45) NOT NULL, state VARCHAR(45) NOT NULL, postal_code VARCHAR(8) NOT NULL, country VARCHAR(35) NOT NULL, additional_address VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, personal_info INT DEFAULT NULL, professional_info INT DEFAULT NULL, score_card INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, google_access_token VARCHAR(255) DEFAULT NULL, user_access_token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_8D93D649FA83366A (personal_info), UNIQUE INDEX UNIQ_8D93D649D9CC0889 (professional_info), UNIQUE INDEX UNIQ_8D93D6493C0C19A5 (score_card), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, housing_id INT DEFAULT NULL, state VARCHAR(45) NOT NULL, type VARCHAR(45) NOT NULL, INDEX IDX_3F980AC8A76ED395 (user_id), INDEX IDX_3F980AC8AD5873E3 (housing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_personal_infos (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, address_id INT DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, firstname VARCHAR(45) NOT NULL, lastname VARCHAR(45) NOT NULL, birth_date DATETIME DEFAULT NULL, birth_location VARCHAR(45) DEFAULT NULL, description LONGTEXT DEFAULT NULL, phone_number VARCHAR(10) DEFAULT NULL, profession VARCHAR(45) DEFAULT NULL, nationality VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_DD161B22A76ED395 (user_id), UNIQUE INDEX UNIQ_DD161B22F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_professional_infos (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, siret VARCHAR(14) DEFAULT NULL, entreprise VARCHAR(45) DEFAULT NULL, work_number VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_23F7DE7CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_score_card (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, score INT NOT NULL, grade VARCHAR(45) NOT NULL, UNIQUE INDEX UNIQ_9B6BDF49A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_wishlist (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(125) NOT NULL, INDEX IDX_7C6CCE31A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_wich_list_value (id INT AUTO_INCREMENT NOT NULL, wish_list_id INT DEFAULT NULL, housing_id INT DEFAULT NULL, INDEX IDX_2B96EB72D69F3311 (wish_list_id), INDEX IDX_2B96EB72AD5873E3 (housing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE money_movement (id INT AUTO_INCREMENT NOT NULL, payment_infos_id INT DEFAULT NULL, state VARCHAR(45) NOT NULL, action VARCHAR(45) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_15B766D9148C0EA2 (payment_infos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_infos (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B983B4DFB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing (id INT AUTO_INCREMENT NOT NULL, proprietary INT DEFAULT NULL, address_id INT DEFAULT NULL, type_id INT DEFAULT NULL, title VARCHAR(45) NOT NULL, price DOUBLE PRECISION NOT NULL, slug VARCHAR(45) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FB8142C3989D9B62 (slug), INDEX IDX_FB8142C3D4D54AC2 (proprietary), UNIQUE INDEX UNIQ_FB8142C3F5B7AF75 (address_id), INDEX IDX_FB8142C3C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_detail (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, details LONGTEXT NOT NULL, title VARCHAR(45) NOT NULL, type VARCHAR(45) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_detail_value (id INT AUTO_INCREMENT NOT NULL, detail_id INT DEFAULT NULL, housing_id INT DEFAULT NULL, value LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_186A9DE8D8D003BB (detail_id), INDEX IDX_186A9DE8AD5873E3 (housing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_document (id INT AUTO_INCREMENT NOT NULL, housing_id INT DEFAULT NULL, file_id INT DEFAULT NULL, name VARCHAR(45) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4D6F3D97AD5873E3 (housing_id), UNIQUE INDEX UNIQ_4D6F3D9793CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_images (id INT AUTO_INCREMENT NOT NULL, housing_id INT DEFAULT NULL, file_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7BA87F1CAD5873E3 (housing_id), UNIQUE INDEX UNIQ_7BA87F1C93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_notation (id INT AUTO_INCREMENT NOT NULL, notation_type_id INT DEFAULT NULL, housing_id INT DEFAULT NULL, value INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_976E0B74AFD62CF8 (notation_type_id), INDEX IDX_976E0B74AD5873E3 (housing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_notation_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, slug VARCHAR(45) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE housing_undisponibility (id INT AUTO_INCREMENT NOT NULL, housing_id INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_354703D3AD5873E3 (housing_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(45) NOT NULL, slug VARCHAR(45) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, slug VARCHAR(45) NOT NULL, score_max INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, housing_id INT DEFAULT NULL, user_id INT DEFAULT NULL, state VARCHAR(45) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_42C84955AD5873E3 (housing_id), INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_infos (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, resident INT NOT NULL, startDate DATETIME NOT NULL, endDate DATETIME NOT NULL, message LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_ADB552CAB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE media__gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media__gallery_media (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, media_id INT DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_80D4C5414E7AF8F (gallery_id), INDEX IDX_80D4C541EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media__media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable TINYINT(1) DEFAULT NULL, cdn_flush_identifier VARCHAR(64) DEFAULT NULL, cdn_flush_at DATETIME DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FA83366A FOREIGN KEY (personal_info) REFERENCES user_personal_infos (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D9CC0889 FOREIGN KEY (professional_info) REFERENCES user_professional_infos (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493C0C19A5 FOREIGN KEY (score_card) REFERENCES user_score_card (id)');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC8AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE user_personal_infos ADD CONSTRAINT FK_DD161B22A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_personal_infos ADD CONSTRAINT FK_DD161B22F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE user_professional_infos ADD CONSTRAINT FK_23F7DE7CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_score_card ADD CONSTRAINT FK_9B6BDF49A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_wishlist ADD CONSTRAINT FK_7C6CCE31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_wich_list_value ADD CONSTRAINT FK_2B96EB72D69F3311 FOREIGN KEY (wish_list_id) REFERENCES user_wishlist (id)');
        $this->addSql('ALTER TABLE user_wich_list_value ADD CONSTRAINT FK_2B96EB72AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE money_movement ADD CONSTRAINT FK_15B766D9148C0EA2 FOREIGN KEY (payment_infos_id) REFERENCES payment_infos (id)');
        $this->addSql('ALTER TABLE payment_infos ADD CONSTRAINT FK_B983B4DFB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE housing ADD CONSTRAINT FK_FB8142C3D4D54AC2 FOREIGN KEY (proprietary) REFERENCES user (id)');
        $this->addSql('ALTER TABLE housing ADD CONSTRAINT FK_FB8142C3F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE housing ADD CONSTRAINT FK_FB8142C3C54C8C93 FOREIGN KEY (type_id) REFERENCES housing_type (id)');
        $this->addSql('ALTER TABLE housing_detail_value ADD CONSTRAINT FK_186A9DE8D8D003BB FOREIGN KEY (detail_id) REFERENCES housing_detail (id)');
        $this->addSql('ALTER TABLE housing_detail_value ADD CONSTRAINT FK_186A9DE8AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE housing_document ADD CONSTRAINT FK_4D6F3D97AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE housing_document ADD CONSTRAINT FK_4D6F3D9793CB796C FOREIGN KEY (file_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE housing_images ADD CONSTRAINT FK_7BA87F1CAD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE housing_images ADD CONSTRAINT FK_7BA87F1C93CB796C FOREIGN KEY (file_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE housing_notation ADD CONSTRAINT FK_976E0B74AFD62CF8 FOREIGN KEY (notation_type_id) REFERENCES housing_notation_type (id)');
        $this->addSql('ALTER TABLE housing_notation ADD CONSTRAINT FK_976E0B74AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE housing_undisponibility ADD CONSTRAINT FK_354703D3AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955AD5873E3 FOREIGN KEY (housing_id) REFERENCES housing (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_infos ADD CONSTRAINT FK_ADB552CAB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C5414E7AF8F FOREIGN KEY (gallery_id) REFERENCES media__gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media__gallery_media ADD CONSTRAINT FK_80D4C541EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_personal_infos DROP FOREIGN KEY FK_DD161B22F5B7AF75');
        $this->addSql('ALTER TABLE housing DROP FOREIGN KEY FK_FB8142C3F5B7AF75');
        $this->addSql('ALTER TABLE user_notification DROP FOREIGN KEY FK_3F980AC8A76ED395');
        $this->addSql('ALTER TABLE user_personal_infos DROP FOREIGN KEY FK_DD161B22A76ED395');
        $this->addSql('ALTER TABLE user_professional_infos DROP FOREIGN KEY FK_23F7DE7CA76ED395');
        $this->addSql('ALTER TABLE user_score_card DROP FOREIGN KEY FK_9B6BDF49A76ED395');
        $this->addSql('ALTER TABLE user_wishlist DROP FOREIGN KEY FK_7C6CCE31A76ED395');
        $this->addSql('ALTER TABLE housing DROP FOREIGN KEY FK_FB8142C3D4D54AC2');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FA83366A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D9CC0889');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493C0C19A5');
        $this->addSql('ALTER TABLE user_wich_list_value DROP FOREIGN KEY FK_2B96EB72D69F3311');
        $this->addSql('ALTER TABLE money_movement DROP FOREIGN KEY FK_15B766D9148C0EA2');
        $this->addSql('ALTER TABLE user_notification DROP FOREIGN KEY FK_3F980AC8AD5873E3');
        $this->addSql('ALTER TABLE user_wich_list_value DROP FOREIGN KEY FK_2B96EB72AD5873E3');
        $this->addSql('ALTER TABLE housing_detail_value DROP FOREIGN KEY FK_186A9DE8AD5873E3');
        $this->addSql('ALTER TABLE housing_document DROP FOREIGN KEY FK_4D6F3D97AD5873E3');
        $this->addSql('ALTER TABLE housing_images DROP FOREIGN KEY FK_7BA87F1CAD5873E3');
        $this->addSql('ALTER TABLE housing_notation DROP FOREIGN KEY FK_976E0B74AD5873E3');
        $this->addSql('ALTER TABLE housing_undisponibility DROP FOREIGN KEY FK_354703D3AD5873E3');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955AD5873E3');
        $this->addSql('ALTER TABLE housing_detail_value DROP FOREIGN KEY FK_186A9DE8D8D003BB');
        $this->addSql('ALTER TABLE housing_notation DROP FOREIGN KEY FK_976E0B74AFD62CF8');
        $this->addSql('ALTER TABLE housing DROP FOREIGN KEY FK_FB8142C3C54C8C93');
        $this->addSql('ALTER TABLE payment_infos DROP FOREIGN KEY FK_B983B4DFB83297E7');
        $this->addSql('ALTER TABLE reservation_infos DROP FOREIGN KEY FK_ADB552CAB83297E7');
        $this->addSql('ALTER TABLE media__gallery_media DROP FOREIGN KEY FK_80D4C5414E7AF8F');
        $this->addSql('ALTER TABLE housing_document DROP FOREIGN KEY FK_4D6F3D9793CB796C');
        $this->addSql('ALTER TABLE housing_images DROP FOREIGN KEY FK_7BA87F1C93CB796C');
        $this->addSql('ALTER TABLE media__gallery_media DROP FOREIGN KEY FK_80D4C541EA9FDD75');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_notification');
        $this->addSql('DROP TABLE user_personal_infos');
        $this->addSql('DROP TABLE user_professional_infos');
        $this->addSql('DROP TABLE user_score_card');
        $this->addSql('DROP TABLE user_wishlist');
        $this->addSql('DROP TABLE user_wich_list_value');
        $this->addSql('DROP TABLE money_movement');
        $this->addSql('DROP TABLE payment_infos');
        $this->addSql('DROP TABLE housing');
        $this->addSql('DROP TABLE housing_detail');
        $this->addSql('DROP TABLE housing_detail_value');
        $this->addSql('DROP TABLE housing_document');
        $this->addSql('DROP TABLE housing_images');
        $this->addSql('DROP TABLE housing_notation');
        $this->addSql('DROP TABLE housing_notation_type');
        $this->addSql('DROP TABLE housing_type');
        $this->addSql('DROP TABLE housing_undisponibility');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_infos');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE media__gallery');
        $this->addSql('DROP TABLE media__gallery_media');
        $this->addSql('DROP TABLE media__media');
    }
}
