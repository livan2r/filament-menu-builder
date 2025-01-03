<?php

namespace Biostate\FilamentMenuBuilder\Filament\Resources;

use App\Filament\Resources\Helper;
use App\Models\Page;
use Filament\Forms\Components\Section;
use Biostate\FilamentMenuBuilder\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
        return Helper::twoColumnsForm($form,
            firstColumn: [
                Section::make(__('filament-menu-builder::menu-builder.details'))
                    ->icon('heroicon-o-folder')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->prefixIcon('heroicon-o-tag')
                            ->prefixIconColor('secondary')
                            ->label(__('filament-menu-builder::menu-builder.name.label'))
                            ->helperText(__('filament-menu-builder::menu-builder.name.desc'))
                            ->required()
                            ->live(onBlur: true)
                            ->maxLength(255)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (!empty(($get('slug') ?? ''))) {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->prefixIcon('heroicon-o-tag')
                            ->prefixIconColor('secondary')
                            ->label(__('filament-menu-builder::menu-builder.slug.label'))
                            ->helperText(__('filament-menu-builder::menu-builder.slug.desc'))
                            ->required()
                            ->disabledOn(['edit', 'view'])
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament-menu-builder::menu-builder.description.label'))
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText(__('filament-menu-builder::menu-builder.description.desc')),
                    ])
                    ->columns(2)
                    ->inlineLabel(false),
            ],
            secondColumn: [
                Section::make(__('filament-menu-builder::menu-builder.adjustments'))
                    ->icon('heroicon-o-cog')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Toggle::make('enabled')
                            ->label(__('filament-menu-builder::menu-builder.enabled.label'))
                            ->helperText(__('filament-menu-builder::menu-builder.enabled.desc'))
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-x-mark')
                            ->default(true)
                            ->required(),
                    ])->inlineLabel(false),
            ],
            helperText: __('filament-menu-builder::menu-builder.helper_text')
        );
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
                    ->description(fn (Menu $record): string => Str::limit($record->description, 100))
                    ->tooltip(fn (Menu $record): string => strlen($record->description) > 100 ? $record->description : '')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('filament-menu-builder::menu-builder.slug.label'))
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
                Tables\Filters\TernaryFilter::make('enabled')
                    ->label(__('filament-menu-builder::menu-builder.enabled.label'))
                    ->default(true),
                Tables\Filters\TrashedFilter::make(),
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
