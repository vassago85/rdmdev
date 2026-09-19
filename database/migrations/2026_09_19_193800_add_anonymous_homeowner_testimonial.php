<?php

use App\Models\PageSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = PageSetting::query()->find(1);

        if (! $page) {
            return;
        }

        $quote = 'Dankie vir die ongelooflike harde werk die afgelope paar maande. Julle het gehelp om ons huis en die prentjie wat ons gehad het, waar te maak. Dit was ’n plesier om julle deel van ons huis te kon maak vir amper drie maande. Dankie vir die vasbyt as ons moeilik geraak het, vir die ekstra op-en-af ry, en vir die ure wat julle hier deurgebring het. Ons glo verseker daar gaan ’n volgende fase wees.';

        $items = collect($page->testimonials ?? []);

        if ($items->contains(fn ($item) => ($item['quote'] ?? '') === $quote)) {
            return;
        }

        $items->push([
            'quote'  => $quote,
            'name'   => 'Homeowner',
            'suburb' => '',
            'rating' => 5,
        ]);

        $page->testimonials = $items->values()->all();
        $page->save();
    }

    public function down(): void
    {
        $page = PageSetting::query()->find(1);

        if (! $page) {
            return;
        }

        $quote = 'Dankie vir die ongelooflike harde werk die afgelope paar maande. Julle het gehelp om ons huis en die prentjie wat ons gehad het, waar te maak. Dit was ’n plesier om julle deel van ons huis te kon maak vir amper drie maande. Dankie vir die vasbyt as ons moeilik geraak het, vir die ekstra op-en-af ry, en vir die ure wat julle hier deurgebring het. Ons glo verseker daar gaan ’n volgende fase wees.';

        $page->testimonials = collect($page->testimonials ?? [])
            ->reject(fn ($item) => ($item['quote'] ?? '') === $quote)
            ->values()
            ->all();
        $page->save();
    }
};
