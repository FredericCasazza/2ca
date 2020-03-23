<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200119174645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration ADD orders_retention_days INT NOT NULL, CHANGE recaptcha_site_key recaptcha_site_key VARCHAR(255) DEFAULT NULL, CHANGE recaptcha_secret_key recaptcha_secret_key VARCHAR(255) DEFAULT NULL, CHANGE mailer_host mailer_host VARCHAR(255) DEFAULT NULL, CHANGE mailer_port mailer_port INT DEFAULT NULL, CHANGE mailer_encryption mailer_encryption VARCHAR(25) DEFAULT NULL, CHANGE mailer_auth_mode mailer_auth_mode VARCHAR(25) DEFAULT NULL, CHANGE mailer_username mailer_username VARCHAR(255) DEFAULT NULL, CHANGE mailer_password mailer_password VARCHAR(255) DEFAULT NULL, CHANGE mailer_timeout mailer_timeout INT DEFAULT NULL, CHANGE mailer_subject_prefix mailer_subject_prefix VARCHAR(25) DEFAULT NULL, CHANGE mailer_from mailer_from VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration DROP orders_retention_days, CHANGE recaptcha_site_key recaptcha_site_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE recaptcha_secret_key recaptcha_secret_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_host mailer_host VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_port mailer_port INT DEFAULT NULL, CHANGE mailer_encryption mailer_encryption VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_auth_mode mailer_auth_mode VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_username mailer_username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_password mailer_password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_timeout mailer_timeout INT DEFAULT NULL, CHANGE mailer_subject_prefix mailer_subject_prefix VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE mailer_from mailer_from VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
