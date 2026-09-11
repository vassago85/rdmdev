<?php

namespace App\Filament\Pages;

use App\Models\PageSetting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageAboutTeam extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $title = 'Website content';

    protected static ?string $navigationLabel = 'Website content';

    protected static ?string $slug = 'website-content';

    protected static string $view = 'filament.pages.manage-about-team';

    protected static ?int $navigationSort = 15;

    public ?array $data = [];

    public function mount(): void
    {
        $record = PageSetting::current();

        $this->form->fill($record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->model(PageSetting::current())
            ->statePath('data')
            ->schema([
                Forms\Components\Tabs::make('website-content')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Homepage')
                            ->icon('heroicon-o-home')
                            ->schema($this->homepageSchema()),
                        Forms\Components\Tabs\Tab::make('About')
                            ->icon('heroicon-o-document-text')
                            ->schema($this->storySchema()),
                        Forms\Components\Tabs\Tab::make('Team')
                            ->icon('heroicon-o-user-group')
                            ->schema($this->teamSchema()),
                        Forms\Components\Tabs\Tab::make('Testimonials')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema($this->testimonialsSchema()),
                        Forms\Components\Tabs\Tab::make('Contact')
                            ->icon('heroicon-o-envelope')
                            ->schema($this->contactSchema()),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema($this->seoSchema()),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function storySchema(): array
    {
        return [
            Forms\Components\Section::make('Hero')
                ->description('Top of the About & Team page. Keep the heading specific to Pretoria East work.')
                ->schema([
                    Forms\Components\TextInput::make('about_eyebrow')
                        ->label('Small label above the heading')
                        ->maxLength(80)
                        ->helperText('e.g. About RDM'),
                    Forms\Components\TextInput::make('about_heading')
                        ->label('Page heading')
                        ->required()
                        ->maxLength(191)
                        ->helperText('The big H1. This is the main thing Google and visitors see first.'),
                    Forms\Components\Textarea::make('about_intro')
                        ->label('Intro paragraph')
                        ->rows(4)
                        ->maxLength(800)
                        ->helperText('2–3 sentences under the heading.'),
                    Forms\Components\FileUpload::make('about_hero_image')
                        ->label('Hero photo (optional)')
                        ->image()
                        ->disk('public')
                        ->directory('about')
                        ->imageEditor()
                        ->imagePreviewHeight('200')
                        ->helperText('If you skip this, the page keeps the branded gradient card.'),
                ]),

            Forms\Components\Section::make('Company story')
                ->schema([
                    Forms\Components\TextInput::make('about_story_heading')
                        ->label('Story heading')
                        ->maxLength(191),
                    Forms\Components\RichEditor::make('about_body')
                        ->label('Story')
                        ->toolbarButtons([
                            'bold', 'italic', 'link', 'bulletList', 'orderedList',
                            'h2', 'h3', 'blockquote', 'undo', 'redo',
                        ])
                        ->helperText('Use H2 for section headings so the outline stays H1 → H2 → H3.')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('What clients can expect')
                ->schema([
                    Forms\Components\TextInput::make('expect_heading')
                        ->label('Section heading')
                        ->maxLength(191),
                    Forms\Components\Repeater::make('expect_items')
                        ->label('Points')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Title')
                                ->required()
                                ->maxLength(80),
                            Forms\Components\TextInput::make('text')
                                ->label('Short explanation')
                                ->required()
                                ->maxLength(200),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->collapsible()
                        ->columnSpanFull()
                        ->helperText('Leave empty to hide this block on the site.'),
                ]),

            Forms\Components\Section::make('Where we work')
                ->schema([
                    Forms\Components\TextInput::make('where_heading')
                        ->label('Section heading')
                        ->maxLength(191),
                    Forms\Components\Textarea::make('where_we_work')
                        ->label('Copy')
                        ->rows(4)
                        ->maxLength(800)
                        ->helperText('Leave empty to hide this block.'),
                ]),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function teamSchema(): array
    {
        return [
            Forms\Components\Section::make('Team section')
                ->description('Up to six people. Each slot is a title, a face, and a name. Unpublished or empty slots stay off the website.')
                ->schema([
                    Forms\Components\TextInput::make('team_heading')
                        ->label('Section heading')
                        ->maxLength(191)
                        ->helperText('e.g. Meet the team'),
                    Forms\Components\Textarea::make('team_intro')
                        ->label('Short intro (optional)')
                        ->rows(2)
                        ->maxLength(300),
                    Forms\Components\FileUpload::make('team_group_photo')
                        ->label('Group photo (optional)')
                        ->image()
                        ->disk('public')
                        ->directory('team')
                        ->imageEditor()
                        ->imagePreviewHeight('220')
                        ->helperText('Wide photo of the team on-site. Shown above the individual cards. Landscape works best (roughly 1600×900).'),
                    Forms\Components\TextInput::make('team_group_photo_caption')
                        ->label('Photo caption (optional)')
                        ->maxLength(160)
                        ->helperText('One short line describing what\'s in the photo. Leave empty to hide.'),
                    Forms\Components\Repeater::make('members')
                        ->relationship()
                        ->label('Team members')
                        ->maxItems(PageSetting::MAX_TEAM_MEMBERS)
                        ->defaultItems(0)
                        ->reorderable()
                        ->orderColumn('sort_order')
                        ->collapsible()
                        ->cloneable(false)
                        ->addActionLabel('Add team member')
                        ->schema([
                            Forms\Components\FileUpload::make('photo')
                                ->label('Face')
                                ->image()
                                ->disk('public')
                                ->directory('team')
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper()
                                ->imagePreviewHeight('160'),
                            Forms\Components\TextInput::make('title')
                                ->label('Title')
                                ->required()
                                ->maxLength(80)
                                ->helperText('e.g. Owner & Project Supervisor'),
                            Forms\Components\TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->maxLength(80),
                            Forms\Components\Toggle::make('is_published')
                                ->label('Show on the website')
                                ->default(true),
                        ])
                        ->itemLabel(fn (array $state): ?string => filled($state['name'] ?? null)
                            ? ($state['name'] . (filled($state['title'] ?? null) ? ' — ' . $state['title'] : ''))
                            : 'New team member')
                        ->grid(2)
                        ->columnSpanFull(),
                ]),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function homepageSchema(): array
    {
        $iconOptions = [
            'users'          => 'People — team',
            'shield-check'   => 'Shield — trust / supervised',
            'message-circle' => 'Chat — communication',
            'sparkles'       => 'Sparkles — finish / quality',
            'hammer'         => 'Hammer — building',
            'house'          => 'House — home',
            'check-circle-2' => 'Tick — checklist',
        ];

        return [
            Forms\Components\Section::make('Hero')
                ->description('The first thing visitors see. Keep Pretoria East in the heading.')
                ->schema([
                    Forms\Components\TextInput::make('home_eyebrow')
                        ->label('Small label')
                        ->maxLength(80),
                    Forms\Components\TextInput::make('home_heading')
                        ->label('Heading')
                        ->maxLength(191),
                    Forms\Components\Textarea::make('home_intro')
                        ->label('Intro')
                        ->rows(4)
                        ->maxLength(800),
                    Forms\Components\FileUpload::make('home_hero_image')
                        ->label('Background photo (optional)')
                        ->image()
                        ->disk('public')
                        ->directory('home')
                        ->imageEditor()
                        ->imagePreviewHeight('180')
                        ->helperText('If you skip this, the page uses the branded gradient (or public/images/hero-home.jpg if that file exists).'),
                ]),

            Forms\Components\Section::make('Owner trust card')
                ->description('The quote card next to the hero. Photo comes from the first published team member.')
                ->schema([
                    Forms\Components\TextInput::make('home_owner_role')
                        ->label('Role under the name')
                        ->maxLength(80),
                    Forms\Components\Textarea::make('home_owner_quote')
                        ->label('Quote')
                        ->rows(3)
                        ->maxLength(300),
                    Forms\Components\Repeater::make('home_owner_bullets')
                        ->label('Trust points')
                        ->schema([
                            Forms\Components\TextInput::make('text')
                                ->label('Point')
                                ->required()
                                ->maxLength(80),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Why RDM')
                ->schema([
                    Forms\Components\TextInput::make('why_eyebrow')->label('Small label')->maxLength(80),
                    Forms\Components\TextInput::make('why_heading')->label('Heading')->maxLength(191),
                    Forms\Components\Textarea::make('why_intro')->label('Intro')->rows(3)->maxLength(400),
                    Forms\Components\Repeater::make('why_items')
                        ->label('Cards')
                        ->schema([
                            Forms\Components\Select::make('icon')
                                ->options($iconOptions)
                                ->native(false)
                                ->required(),
                            Forms\Components\TextInput::make('title')->required()->maxLength(80),
                            Forms\Components\Textarea::make('body')->required()->rows(3)->maxLength(240),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->collapsible()
                        ->grid(2)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Featured projects heading')
                ->schema([
                    Forms\Components\TextInput::make('projects_eyebrow')->label('Small label')->maxLength(80),
                    Forms\Components\TextInput::make('projects_heading')->label('Heading')->maxLength(191),
                    Forms\Components\Textarea::make('projects_intro')->label('Intro')->rows(2)->maxLength(300),
                ]),

            Forms\Components\Section::make('Services heading')
                ->schema([
                    Forms\Components\TextInput::make('services_eyebrow')->label('Small label')->maxLength(80),
                    Forms\Components\TextInput::make('services_heading')->label('Heading')->maxLength(191),
                    Forms\Components\Textarea::make('services_intro')->label('Intro')->rows(3)->maxLength(400),
                    Forms\Components\Textarea::make('services_disclaimer')
                        ->label('Disclaimer under the grid')
                        ->rows(2)
                        ->maxLength(300),
                ]),

            Forms\Components\Section::make('Areas we serve')
                ->description('Suburb chips themselves stay in site config. This is only the heading copy.')
                ->schema([
                    Forms\Components\TextInput::make('areas_eyebrow')->label('Small label')->maxLength(80),
                    Forms\Components\TextInput::make('areas_heading')->label('Heading')->maxLength(191),
                    Forms\Components\Textarea::make('areas_intro')->label('Intro')->rows(3)->maxLength(400),
                ]),

            Forms\Components\Section::make('About teaser')
                ->description('The About block near the bottom of the home page. Links through to About & Team.')
                ->schema([
                    Forms\Components\TextInput::make('home_about_eyebrow')->label('Small label')->maxLength(80),
                    Forms\Components\TextInput::make('home_about_heading')->label('Heading')->maxLength(191),
                    Forms\Components\Textarea::make('home_about_intro')->label('Intro')->rows(4)->maxLength(600),
                    Forms\Components\Repeater::make('home_about_bullets')
                        ->label('Bullet points')
                        ->schema([
                            Forms\Components\TextInput::make('text')->label('Bullet')->required()->maxLength(200),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->columnSpanFull(),
                ])
                ->collapsed(),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function contactSchema(): array
    {
        return [
            Forms\Components\Section::make('Contact page')
                ->description('The heading visitors see on /contact. The enquiry form itself stays as-is.')
                ->schema([
                    Forms\Components\TextInput::make('contact_eyebrow')->label('Small label')->maxLength(80),
                    Forms\Components\TextInput::make('contact_heading')->label('Heading')->maxLength(191),
                    Forms\Components\Textarea::make('contact_intro')->label('Intro')->rows(4)->maxLength(600),
                ]),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function testimonialsSchema(): array
    {
        return [
            Forms\Components\Section::make('Client testimonials')
                ->description('Shown on the home page and the About & Team page. Only add real client quotes — empty quote or name hides that card.')
                ->schema([
                    Forms\Components\TextInput::make('testimonials_eyebrow')
                        ->label('Small label')
                        ->maxLength(80)
                        ->helperText('e.g. What clients say'),
                    Forms\Components\TextInput::make('testimonials_heading')
                        ->label('Heading')
                        ->maxLength(191),
                    Forms\Components\Textarea::make('testimonials_intro')
                        ->label('Intro')
                        ->rows(2)
                        ->maxLength(300),
                    Forms\Components\Repeater::make('testimonials')
                        ->label('Quotes')
                        ->schema([
                            Forms\Components\Textarea::make('quote')
                                ->label('Quote')
                                ->required()
                                ->rows(3)
                                ->maxLength(500),
                            Forms\Components\TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->maxLength(80)
                                ->helperText('First name, or “Homeowner” if they asked not to be named.'),
                            Forms\Components\TextInput::make('suburb')
                                ->label('Suburb')
                                ->maxLength(80)
                                ->helperText('e.g. Garsfontein — “Pretoria East” is added automatically.'),
                            Forms\Components\Select::make('rating')
                                ->label('Stars')
                                ->options([
                                    5 => '5 stars',
                                    4 => '4 stars',
                                    3 => '3 stars',
                                    2 => '2 stars',
                                    1 => '1 star',
                                ])
                                ->default(5)
                                ->native(false)
                                ->required(),
                        ])
                        ->defaultItems(0)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => filled($state['name'] ?? null)
                            ? ($state['name'] . (filled($state['suburb'] ?? null) ? ' — ' . $state['suburb'] : ''))
                            : 'New testimonial')
                        ->addActionLabel('Add testimonial')
                        ->columnSpanFull(),
                ]),
        ];
    }

    /** @return array<int, \Filament\Forms\Components\Component> */
    protected function seoSchema(): array
    {
        return [
            Forms\Components\Section::make('Homepage')
                ->schema([
                    Forms\Components\TextInput::make('home_seo_title')
                        ->label('SEO title')
                        ->maxLength(70)
                        ->helperText('Google shows about 60 characters.'),
                    Forms\Components\Textarea::make('home_seo_meta_description')
                        ->label('Meta description')
                        ->rows(3)
                        ->maxLength(300)
                        ->helperText('Google shows about 155 characters. Longer text is trimmed automatically in search results.'),
                ]),
            Forms\Components\Section::make('About & Team')
                ->schema([
                    Forms\Components\TextInput::make('seo_title')
                        ->label('SEO title')
                        ->maxLength(70)
                        ->helperText('Google shows about 60 characters.'),
                    Forms\Components\Textarea::make('seo_meta_description')
                        ->label('Meta description')
                        ->rows(3)
                        ->maxLength(300)
                        ->helperText('Google shows about 155 characters. Longer text is trimmed automatically in search results.'),
                ]),
            Forms\Components\Section::make('Contact')
                ->schema([
                    Forms\Components\TextInput::make('contact_seo_title')
                        ->label('SEO title')
                        ->maxLength(70)
                        ->helperText('Google shows about 60 characters.'),
                    Forms\Components\Textarea::make('contact_seo_meta_description')
                        ->label('Meta description')
                        ->rows(3)
                        ->maxLength(300)
                        ->helperText('Google shows about 155 characters. Longer text is trimmed automatically in search results.'),
                ]),
        ];
    }

    public function save(): void
    {
        $record = PageSetting::current();
        $data = $this->form->getState();

        $record->update($data);
        $this->form->model($record)->saveRelationships();

        PageSetting::flushCache();
        $record = PageSetting::current();
        $this->form->model($record)->fill($record->attributesToArray());

        Notification::make()
            ->title('Published')
            ->body('Your changes are live. Use the view buttons to check the homepage or About & Team.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('View About & Team')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn () => route('about'))
                ->openUrlInNewTab(),
            Action::make('viewHome')
                ->label('View homepage')
                ->icon('heroicon-o-home')
                ->color('gray')
                ->url(fn () => route('home'))
                ->openUrlInNewTab(),
        ];
    }
}
