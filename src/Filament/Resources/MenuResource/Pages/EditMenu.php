<?php

namespace Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages;

use Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource;
use Biostate\FilamentMenuBuilder\Models\Menu;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('Builder')
                ->url(fn (Menu $record): string => MenuResource::getUrl('build', ['record' => $record]))
                ->icon('heroicon-o-bars-3'),
            Actions\DeleteAction::make(),
        ];
    }
}