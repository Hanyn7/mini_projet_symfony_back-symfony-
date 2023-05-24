<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518211017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, author VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D9777D11E');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D9777D11E');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
        $this->addSql('DROP INDEX fk_5a8a6c8d9777d11e ON post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D9777D11E ON post (category_id_id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D9777D11E');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D9777D11E');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_5a8a6c8d9777d11e ON post');
        $this->addSql('CREATE INDEX FK_5A8A6C8D9777D11E ON post (category_id_id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D9777D11E FOREIGN KEY (category_id_id) REFERENCES category (id)');
    }
}
