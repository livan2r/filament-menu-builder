<?php

namespace Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages;

use Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make()
                ->label(__('filament-menu-builder::menu-builder.add_new'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
