<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230723130133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX is_member ON `test_users` (is_member)');
        $this->addSql('CREATE INDEX email ON `test_users` (email)');
        $this->addSql('CREATE INDEX username ON `test_users` (username)');
        $this->addSql('CREATE INDEX last_login_at ON `test_users` (last_login_at)');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX is_member ON is_member');
        $this->addSql('DROP INDEX email ON email');
        $this->addSql('DROP INDEX username ON username');
        $this->addSql('DROP INDEX last_login_at ON last_login_at');
    }
}
