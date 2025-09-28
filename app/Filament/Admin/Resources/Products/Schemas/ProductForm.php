<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use App\Services\VariantGeneratorService;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Enter the core product details. The slug will auto-generate from the name.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        MarkdownEditor::make('description_md')
                            ->label('Description')
                            ->columnSpanFull(),
                        
                        Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Images')
                    ->description('Upload general product images, lifestyle shots, and size charts. These show by default on the product page.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->imageEditor()
                            ->conversion('thumb'),
                    ]),

                Section::make('Variant Configuration')
                    ->description('Create product variants (Size, Color, etc.). First define axes and values, then generate all combinations.')
                    ->schema([
                        Repeater::make('variant_axes')
                            ->label('Variant Axes')
                            ->helperText('Define what makes variants different (e.g., Size with values S/M/L, Color with Red/Blue)')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->placeholder('e.g., Size, Color'),
                                
                                Repeater::make('values')
                                    ->schema([
                                        TextInput::make('value')
                                            ->required()
                                            ->placeholder('e.g., Small, Red'),
                                    ])
                                    ->minItems(1)
                                    ->addActionLabel('Add Value')
                                    ->simple(
                                        TextInput::make('value')
                                            ->required()
                                            ->placeholder('e.g., Small, Red')
                                    ),
                            ])
                            ->minItems(0)
                            ->addActionLabel('Add Variant Axis')
                            ->live(),

                        Section::make('Product Variants')
                            ->description('Click "Generate Variants" to create all combinations. Then set SKU, price, and upload color-specific images for each.')
                            ->afterHeader([
                                Action::make('generateVariants')
                                    ->label('Generate Variants')
                                    ->action(function (Get $get, Set $set) {
                                        $axes = $get('variant_axes') ?? [];
                                        $variants = VariantGeneratorService::generate($axes);
                                        $set('variants', $variants);
                                    })
                                    ->color('success'),
                            ])
                            ->schema([
                                Repeater::make('variants')
                                    ->schema([
                                        TextInput::make('combination_label')
                                            ->label('Variant')
                                            ->disabled()
                                            ->columnSpan(2),
                                        
                                        TextInput::make('sku')
                                            ->required()
                                            ->unique(ignoreRecord: true),
                                        
                                        TextInput::make('price_cents')
                                            ->label('Price (cents)')
                                            ->numeric()
                                            ->required(),
                                        
                                        TextInput::make('cost_cents')
                                            ->label('Cost (cents)')
                                            ->numeric(),
                                        
                                        Toggle::make('is_active')
                                            ->default(true),
                                        
                                        SpatieMediaLibraryFileUpload::make('variant_images')
                                            ->label('Variant Images')
                                            ->helperText('Upload images specific to this variant (e.g., color photos). Leave empty to use general product images.')
                                            ->collection('variant_gallery')
                                            ->multiple()
                                            ->reorderable()
                                            ->image()
                                            ->imageEditor()
                                            ->conversion('thumb')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(3)
                                    ->minItems(0),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}