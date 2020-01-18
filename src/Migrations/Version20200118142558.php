<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200118142558 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration CHANGE recaptcha_site_key recaptcha_site_key VARCHAR(255) DEFAULT NULL, CHANGE recaptcha_secret_key recaptcha_secret_key VARCHAR(255) DEFAULT NULL, CHANGE mailer_host mailer_host VARCHAR(255) DEFAULT NULL, CHANGE mailer_port mailer_port INT DEFAULT NULL, CHANGE mailer_encryption mailer_encryption VARCHAR(25) DEFAULT NULL, CHANGE mailer_auth_mode mailer_auth_mode VARCHAR(25) DEFAULT NULL, CHANGE mailer_username mailer_username VARCHAR(255) DEFAULT NULL, CHANGE mailer_password mailer_password VARCHAR(255) DEFAULT NULL, CHANGE mailer_timeout mailer_timeout INT DEFAULT NULL, CHANGE mailer_subject_prefix mailer_subject_prefix VARCHAR(25) DEFAULT NULL, CHANGE mailer_from mailer_from VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dish CHANGE category_id category_id INT DEFAULT NULL, CHANGE meal_id meal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dish_category CHANGE dishes dishes JSON NOT NULL');
        $this->addSql('ALTER TABLE meal CHANGE period_id period_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE user user INT DEFAULT NULL, CHANGE role role VARCHAR(25) DEFAULT NULL, CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders CHANGE user_id user_id INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL, CHANGE validation_date validation_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE establishment_id establishment_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE reinit_token reinit_token VARCHAR(60) DEFAULT NULL, CHANGE reinit_expiration_date reinit_expiration_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration CHANGE recaptcha_site_key recaptcha_site_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE recaptcha_secret_key recaptcha_secret_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_host mailer_host VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_port mailer_port INT DEFAULT NULL, CHANGE mailer_encryption mailer_encryption VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_auth_mode mailer_auth_mode VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_username mailer_username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_password mailer_password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_timeout mailer_timeout INT DEFAULT NULL, CHANGE mailer_subject_prefix mailer_subject_prefix VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_from mailer_from VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE dish CHANGE category_id category_id INT DEFAULT NULL, CHANGE meal_id meal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dish_category CHANGE dishes dishes LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE meal CHANGE period_id period_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE user user INT DEFAULT NULL, CHANGE role role VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE orders CHANGE user_id user_id INT DEFAULT NULL, CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE validation_date validation_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE establishment_id establishment_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE reinit_token reinit_token VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE reinit_expiration_date reinit_expiration_date DATETIME DEFAULT \'NULL\'');
    }
}
