<div class="editor-content">
    @vite(['resources/css/client.ts'])

    {!! $content !!}
</div>

<script>
    document.querySelectorAll('[data-type="uiblock"]').forEach(async el => {
        const name = el.dataset.name;
        const res = await fetch(`/render-uiblock/${name}`);
        if (res.ok) {
            const html = await res.text();
            el.outerHTML = html;
        }
    });
</script>
