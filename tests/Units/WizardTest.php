<?php declare(strict_types=1);

use PHPWarrior\Units\Wizard;

beforeEach(function (): void {
   $this->wizard = new Wizard();
});

it ('should have look and shoot abilities', function (): void {
    $abilities = $this->wizard->abilities();

    $this->assertArrayHasKey('look', $abilities);
    $this->assertArrayHasKey('shoot', $abilities);
});