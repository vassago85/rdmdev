@php
    use App\Support\JsonLd;

    $entities = [JsonLd::business()];

    if (! empty($schemaExtra) && is_array($schemaExtra)) {
        foreach ($schemaExtra as $extra) {
            if (! empty($extra)) {
                $entities[] = $extra;
            }
        }
    }
@endphp
<script type="application/ld+json">
{!! JsonLd::graph($entities) !!}
</script>
