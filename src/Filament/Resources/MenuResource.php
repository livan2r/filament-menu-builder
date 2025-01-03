<?php

namespace Biostate\FilamentMenuBuilder\Filament\Resources;

use App\Models\Page;
use Biostate\FilamentMenuBuilder\Models\Menu;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MenuResource extends Resource
{
    use Translatable;

    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function getNavigationGroup(): ?string
    {
        return __('filament-menu-builder::menu-builder.navigation_group');
    }

    public static function getModelLabel(): string
    {
        return __('filament-menu-builder::menu-builder.menu');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-menu-builder::menu-builder.menus');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('filament-menu-builder::menu-builder.form_labels.name'))
                    ->required()
                    ->autofocus()
                    ->placeholder('Name')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.common.id.label'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-menu-builder::menu-builder.form_labels.name'))
                    ->description(fn (Page $record): string => Str::limit($record->description, 60))
                    ->tooltip(fn (Page $record): string => strlen($record->description) > 60 ? $record->description : '')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Component')
                    ->copyable()
                    ->copyMessage(__('filament-menu-builder::menu-builder.component_copy_message'))
                    ->copyMessageDuration(3000)
                    ->badge(),
                Tables\Columns\ToggleColumn::make('enabled')
                    ->label(__('admin.page.enabled.label'))
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make(__('filament-menu-builder::menu-builder.configure_menu'))
                    ->url(fn (Menu $record): string => static::getUrl('build', ['record' => $record]))
                    ->icon('heroicon-o-globe-alt')
                    ->tooltip(__('filament-menu-builder::menu-builder.configure_menu'))
                    ->hiddenLabel()
                    ->color('primary')
                    ->size(ActionSize::Medium),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->size(ActionSize::Medium)
                    ->tooltip(__('filament-actions::edit.single.label')),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->size(ActionSize::Medium)
                    ->tooltip(__('filament-actions::delete.single.label')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\ListMenus::route('/'),
            'create' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\CreateMenu::route('/create'),
            'edit' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu::route('/{record}/edit'),
            'build' => \Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\MenuBuilder::route('/{record}/build'),
        ];
    }
}
