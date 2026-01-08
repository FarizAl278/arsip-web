<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Arsip extends Page
{
    protected string $view = 'filament.pages.arsip';
    
    public string $activeTab = 'pns';
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-text';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Arsip';
    }
    
    public function getTitle(): string
    {
        return 'Arsip Kepegawaian';
    }
}