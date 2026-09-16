<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full border border-transparent bg-marca-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-marca-600 focus:outline-none focus:ring-2 focus:ring-marca-300 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
