<?php

namespace Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages;

use Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource;
use Biostate\FilamentMenuBuilder\Models\Menu;
use Filament\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

class EditMenu extends EditRecord
{
    Use Translatable;

    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('back')
                ->url(MenuResource::getUrl())
                ->label(__('admin.return'))
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
            Actions\LocaleSwitcher::make(),
            Actions\Action::make(__('filament-menu-builder::menu-builder.configure_menu'))
                ->url(fn (Menu $record): string => MenuResource::getUrl('build', ['record' => $record]))
                ->color('primary')
                ->icon('heroicon-o-globe-alt'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-pencil'),
            Actions\ForceDeleteAction::make()
                ->icon('heroicon-o-trash'),
            Actions\RestoreAction::make()
                ->icon('heroicon-o-refresh'),
        ];
    }
}
