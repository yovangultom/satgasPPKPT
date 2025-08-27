<x-filament-panels::page>

    <a href="{{ route('borang.pdf.view', ['borangPdf' => $record]) }}" target="_blank"
        class="fi-btn fi-btn-size-md fi-btn-color-primary"
        style="display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            style="width: 1.25rem; height: 1.25rem;">
            <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
            <path fill-rule="evenodd"
                d="M.664 10.59a1.651 1.651 0 0 1 0-1.18l.879-.589a1.651 1.651 0 0 0 .54-1.057l.122-1.061a1.651 1.651 0 0 1 1.518-1.354l1.012.223a1.651 1.651 0 0 0 1.228-.54l.832-.832a1.651 1.651 0 0 1 2.332 0l.832.832a1.651 1.651 0 0 0 1.228.54l1.012-.223a1.651 1.651 0 0 1 1.518 1.354l.122 1.061a1.651 1.651 0 0 0 .54 1.057l.879.589a1.651 1.651 0 0 1 0 1.18l-.879.589a1.651 1.651 0 0 0-.54 1.057l-.122 1.061a1.651 1.651 0 0 1-1.518 1.354l-1.012-.223a1.651 1.651 0 0 0-1.228.54l-.832.832a1.651 1.651 0 0 1-2.332 0l-.832-.832a1.651 1.651 0 0 0-1.228-.54l-1.012.223a1.651 1.651 0 0 1-1.518-1.354l-.122-1.061a1.651 1.651 0 0 0-.54-1.057L.664 10.59ZM10 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"
                clip-rule="evenodd" />
        </svg>
        Buka di Tab Baru
    </a>

    <div style="width: 100%; height: 80vh; border: 1px solid #ccc;">
        <iframe src="{{ route('borang.pdf.view', ['borangPdf' => $record]) }}" width="100%" height="100%"
            frameborder="0">
        </iframe>
    </div>

</x-filament-panels::page>
