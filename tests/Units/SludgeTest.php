<?php declare(strict_types=1);

use PHPWarrior\Units\Sludge;

beforeEach (function (): void {
    $this->sludge = new Sludge();
});

it ('should have attack ability', function (): void {
    $this->assertArrayHasKey('attack', $this->sludge->abilities());
});

it ('should have feel sense', function (): void {
   $this->assertArrayHasKey('feel', $this->sludge->abilities());
});

it ('should have attack power of 3', function (): void {
   $this->assertEquals(3, $this->sludge->attack_power());
});

it ('should have 12 max health', function (): void {
    $this->assertEquals(12, $this->sludge->max_health());
});

it ('should appear as S on map', function (): void {
    $this->assertEquals('s', $this->sludge->character());
});
