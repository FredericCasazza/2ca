<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116170646 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE configuration (id INT AUTO_INCREMENT NOT NULL, env VARCHAR(25) NOT NULL, session_max_idle_time INT NOT NULL, recaptcha_enable TINYINT(1) NOT NULL, recaptcha_site_key VARCHAR(255) DEFAULT NULL, recaptcha_secret_key VARCHAR(255) DEFAULT NULL, mailer_host VARCHAR(255) DEFAULT NULL, mailer_port INT DEFAULT NULL, mailer_encryption VARCHAR(25) DEFAULT NULL, mailer_auth_mode VARCHAR(25) DEFAULT NULL, mailer_username VARCHAR(255) DEFAULT NULL, mailer_password VARCHAR(255) DEFAULT NULL, mailer_timeout INT DEFAULT NULL, mailer_subject_prefix VARCHAR(25) DEFAULT NULL, mailer_from VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_A5E2A5D7F34542F9 (env), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, establishment_id INT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_274A2B21A76ED395 (user_id), INDEX IDX_274A2B218565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, meal_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, INDEX IDX_957D8CB812469DE2 (category_id), INDEX IDX_957D8CB8639666D6 (meal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_dish (dish_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_D88CB6AF148EB0CB (dish_id), INDEX IDX_D88CB6AF8D9F6D38 (order_id), PRIMARY KEY(dish_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dish_category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(35) NOT NULL, position INT NOT NULL, dish_limit INT NOT NULL, enable TINYINT(1) NOT NULL, dishes JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establishment (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, period_id INT DEFAULT NULL, date DATE NOT NULL, book_date_limit DATETIME NOT NULL, published TINYINT(1) NOT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME NOT NULL, INDEX IDX_9EF68E9CEC8B7ADE (period_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal_establishment (meal_id INT NOT NULL, establishement_id INT NOT NULL, INDEX IDX_C1D60B62639666D6 (meal_id), INDEX IDX_C1D60B62C65F9894 (establishement_id), PRIMARY KEY(meal_id, establishement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, role VARCHAR(25) DEFAULT NULL, type VARCHAR(50) NOT NULL, message LONGTEXT NOT NULL, action VARCHAR(75) NOT NULL, url VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, checked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, meal_id INT NOT NULL, user_id INT DEFAULT NULL, establishment_id INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, modification_date DATETIME NOT NULL, validation_date DATETIME DEFAULT NULL, INDEX IDX_E52FFDEE639666D6 (meal_id), INDEX IDX_E52FFDEEA76ED395 (user_id), INDEX IDX_E52FFDEE8565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(25) NOT NULL, start_time TIME NOT NULL, book_time_limit INT NOT NULL, position INT NOT NULL, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, establishment_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, lastname VARCHAR(60) NOT NULL, firstname VARCHAR(60) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, reinit_token VARCHAR(60) DEFAULT NULL, reinit_expiration_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6498565851 (establishment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_request ADD CONSTRAINT FK_274A2B21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE customer_request ADD CONSTRAINT FK_274A2B218565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB812469DE2 FOREIGN KEY (category_id) REFERENCES dish_category (id)');
        $this->addSql('ALTER TABLE dish ADD CONSTRAINT FK_957D8CB8639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE order_dish ADD CONSTRAINT FK_D88CB6AF148EB0CB FOREIGN KEY (dish_id) REFERENCES dish (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_dish ADD CONSTRAINT FK_D88CB6AF8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('ALTER TABLE meal_establishment ADD CONSTRAINT FK_C1D60B62639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE meal_establishment ADD CONSTRAINT FK_C1D60B62C65F9894 FOREIGN KEY (establishement_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE8565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_dish DROP FOREIGN KEY FK_D88CB6AF148EB0CB');
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB812469DE2');
        $this->addSql('ALTER TABLE customer_request DROP FOREIGN KEY FK_274A2B218565851');
        $this->addSql('ALTER TABLE meal_establishment DROP FOREIGN KEY FK_C1D60B62C65F9894');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE8565851');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498565851');
        $this->addSql('ALTER TABLE dish DROP FOREIGN KEY FK_957D8CB8639666D6');
        $this->addSql('ALTER TABLE meal_establishment DROP FOREIGN KEY FK_C1D60B62639666D6');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE639666D6');
        $this->addSql('ALTER TABLE order_dish DROP FOREIGN KEY FK_D88CB6AF8D9F6D38');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CEC8B7ADE');
        $this->addSql('ALTER TABLE customer_request DROP FOREIGN KEY FK_274A2B21A76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE customer_request');
        $this->addSql('DROP TABLE dish');
        $this->addSql('DROP TABLE order_dish');
        $this->addSql('DROP TABLE dish_category');
        $this->addSql('DROP TABLE establishment');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE meal_establishment');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE period');
        $this->addSql('DROP TABLE user');
    }
}
