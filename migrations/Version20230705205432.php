<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705205432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            INSERT INTO currency (`name`, `code`) 
            VALUES ('Euro', 'EUR'), ('United States Dollar', 'USD'), ('British Pound Sterling', 'GBP')
        ");

        $this->addSql("
            INSERT INTO category (`name`, `color_hex`) 
            VALUES ('Food', '#ffbe0a'), ('Drinks', '#256fff'), ('Newspapers', '#cb410b')
        ");

        $this->addSql("
            INSERT INTO wallet_type (`name`) 
            VALUES ('Cash'), ('Bank Account'), ('Credit Card')
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
