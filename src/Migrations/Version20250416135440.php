<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416135440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE allowed_user (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, allowed_id INT NOT NULL, allowed_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_2007AFB47E3C61F9 (owner_id), INDEX IDX_2007AFB4F7F34542 (allowed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', is_deleted TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_CBE5A331A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(32) NOT NULL, hash_password VARCHAR(255) NOT NULL, registered_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE allowed_user ADD CONSTRAINT FK_2007AFB47E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE allowed_user ADD CONSTRAINT FK_2007AFB4F7F34542 FOREIGN KEY (allowed_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book ADD CONSTRAINT FK_CBE5A331A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE doctrine_migrations_versions
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE doctrine_migrations_versions (version VARCHAR(1024) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, executed_at DATETIME DEFAULT NULL, execution_time INT DEFAULT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE allowed_user DROP FOREIGN KEY FK_2007AFB47E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE allowed_user DROP FOREIGN KEY FK_2007AFB4F7F34542
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE allowed_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
