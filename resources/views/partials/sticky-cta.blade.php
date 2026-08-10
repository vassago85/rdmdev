{{--
    Mobile sticky action bar — keeps Call + WhatsApp one tap away on phones,
    where most of a local builder's traffic lands. Hidden on lg+ (desktop has
    the header Call button and the floating WhatsApp FAB instead).
--}}
<div class="fixed inset-x-0 bottom-0 z-40 lg:hidden border-t border-ink-100 bg-white/95 backdrop-blur shadow-[0_-2px_12px_rgba(15,23,42,0.08)] pb-[env(safe-area-inset-bottom)]">
    <div class="grid grid-cols-2 gap-2 p-2.5">
        <a href="tel:{{ config('rdm.phone_tel') }}"
           class="btn btn-md bg-brand text-white hover:bg-brand-600">
            <x-lucide name="phone" class="h-5 w-5" stroke="2" />
            Call Ruben
        </a>
        <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode(config('rdm.whatsapp_greeting')) }}"
           target="_blank" rel="noopener"
           class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957]">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.767.966-.94 1.164-.173.198-.347.223-.644.074-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.762-1.653-2.06-.173-.297-.018-.458.13-.606.134-.134.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.064 2.875 1.213 3.074c.148.198 2.095 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.247-.694.247-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
            </svg>
            WhatsApp
        </a>
    </div>
</div>
