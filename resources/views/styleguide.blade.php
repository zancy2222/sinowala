{{-- Local only (routes/web.php registers it when APP_ENV=local). Every token and component in one place. --}}
@php
    $colors = [
        'tinta' => '#12161F · text, primary buttons', 'tinta-2' => '#2A303C · primary hover', 'kahel' => '#FF5B1F · Wala only + logo',
        'papel' => '#FFFFFF · surfaces', 'abo' => '#E3E5E9 · present tiles, borders', 'ground' => '#F1F2F4 · page background',
        'muted' => '#545A66 · secondary text', 'line-strong' => '#8B919C · control borders', 'hover' => '#F6F7F8', 'press' => '#E9EBEE',
        'danger' => '#B42318 · destructive, errors', 'danger-soft' => '#FDECEA', 'success' => '#1E7A47 · success', 'success-soft' => '#E7F4EC',
    ];
@endphp
<x-layouts.public title="Styleguide" noindex ground="bg-ground">
    <div class="mx-auto flex max-w-[1240px] flex-col gap-12 safe-x py-12 sm:px-6 lg:px-8">
        <div>
            <h1 class="text-3xl font-extrabold">Styleguide</h1>
            <p class="mt-2 text-muted">Local only. One spacing scale (4px), one type scale, one radius pair, one icon set (Lucide, 1.75 stroke).</p>
        </div>

        <x-ui.card title="Colour tokens">
            <ul class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($colors as $name => $desc)
                    <li class="flex items-center gap-3"><span class="size-10 rounded border border-line bg-{{ $name }}"></span><span><span class="block font-semibold">{{ $name }}</span><span class="text-sm text-muted">{{ $desc }}</span></span></li>
                @endforeach
            </ul>
            {{-- Tailwind needs to see these class names: bg-tinta bg-tinta-2 bg-kahel bg-papel bg-abo bg-ground bg-muted bg-line-strong bg-hover bg-press bg-danger bg-danger-soft bg-success bg-success-soft --}}
        </x-ui.card>

        <x-ui.card title="Type scale (12 / 14 / 16 / 18 / 24 / 32 / 48)">
            <div class="flex flex-col gap-2">
                <p class="text-3xl font-extrabold">48 · Sino'ng wala ngayon?</p>
                <p class="text-2xl font-extrabold">32 · Team calendar</p>
                <p class="text-xl font-extrabold">24 · Oktubre 2026</p>
                <p class="text-lg">18 · Lead text para sa mga page.</p>
                <p class="text-base">16 · Body text. Martes, Okt 27, 2026.</p>
                <p class="text-sm">14 · Labels at secondary text.</p>
                <p class="text-xs">12 · Meta at maliliit na label.</p>
            </div>
        </x-ui.card>

        <x-ui.card title="Buttons">
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button>Primary</x-ui.button>
                <x-ui.button variant="secondary">Secondary</x-ui.button>
                <x-ui.button variant="ghost">Ghost</x-ui.button>
                <x-ui.button variant="destructive">Destructive</x-ui.button>
                <x-ui.button variant="danger-solid">Delete</x-ui.button>
                <x-ui.button disabled>Disabled</x-ui.button>
                <x-ui.button size="sm" icon="plus">Small</x-ui.button>
                <x-ui.button size="lg" icon-right="arrow-right">Large</x-ui.button>
                <x-ui.button aria-busy="true"><svg class="size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3"/><path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>Loading</x-ui.button>
            </div>
        </x-ui.card>

        <x-ui.card title="Form fields">
            <div class="grid max-w-2xl gap-5 sm:grid-cols-2">
                <x-ui.field label="Input" for="sg-input" help="Help text sa ilalim ng label."><x-ui.input name="sg-input" :help="true" placeholder="Placeholder" /></x-ui.field>
                <x-ui.field label="Select" for="sg-select"><x-ui.select name="sg-select"><option>Lahat</option><option>Active</option></x-ui.select></x-ui.field>
                <x-ui.field label="Textarea" for="sg-text" optional class="sm:col-span-2"><x-ui.textarea name="sg-text" rows="3" /></x-ui.field>
                <x-ui.field label="Disabled" for="sg-dis"><x-ui.input name="sg-dis" disabled value="Hindi mababago" /></x-ui.field>
                <x-ui.field label="Read-only" for="sg-ro"><x-ui.input name="sg-ro" readonly value="K7Q2MX" /></x-ui.field>
                <div class="sm:col-span-2"><x-ui.checkbox name="sg-check" label="Checkbox" description="May description din." /></div>
                <fieldset class="flex flex-col gap-2 sm:col-span-2"><legend class="mb-2 text-sm font-semibold">Radio group</legend>
                    <x-ui.radio name="sg-radio" value="a" label="Para sa sarili ko" description="Magagamit agad" checked />
                    <x-ui.radio name="sg-radio" value="b" label="Para sa kumpanya ko" />
                </fieldset>
            </div>
        </x-ui.card>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-ui.card title="Badges and avatars">
                <div class="flex flex-wrap items-center gap-2">
                    <x-ui.badge>Neutral</x-ui.badge><x-ui.badge tone="wala">Wala · AM</x-ui.badge><x-ui.badge tone="success">Active</x-ui.badge>
                    <x-ui.badge tone="danger">Naka-suspend</x-ui.badge><x-ui.badge tone="outline">Outline</x-ui.badge><x-ui.badge tone="dark">Admin</x-ui.badge>
                    <x-ui.avatar name="Ana Santos" size="sm" /><x-ui.avatar name="Ben Cruz" /><x-ui.avatar name="Carlo Reyes" size="lg" />
                    <x-ui.kbd>N</x-ui.kbd><x-ui.kbd>?</x-ui.kbd>
                </div>
            </x-ui.card>
            <x-ui.card title="Tiles">
                <div class="grid grid-cols-4 gap-1">
                    <span class="tile h-16"><span class="text-sm font-semibold">26</span></span>
                    <span class="tile tile-wala h-16"><span class="text-sm font-semibold">27</span><span class="mt-auto text-xs font-extrabold">Wala AM</span></span>
                    <span class="tile tile-today h-16"><span class="text-sm font-semibold">28</span></span>
                    <span class="tile tile-outside h-16"><span class="text-sm font-semibold">1</span></span>
                </div>
            </x-ui.card>
        </div>

        <div class="flex flex-col gap-3">
            <x-ui.alert title="Neutral">Impormasyon lang.</x-ui.alert>
            <x-ui.alert tone="pending" title="Hinihintay pa ang approval">Bubukas ang team calendar kapag na-approve na.</x-ui.alert>
            <x-ui.alert tone="success" title="Na-save">Na-save ang profile mo.</x-ui.alert>
            <x-ui.alert tone="danger" title="May mali">Hindi tugma ang email at password.</x-ui.alert>
        </div>

        <x-ui.card title="Table" :padding="false">
            <x-ui.table caption="Halimbawa">
                <thead><tr><th scope="col">Kumpanya</th><th scope="col">Status</th><th scope="col" class="text-right">Members</th></tr></thead>
                <tbody>
                    <tr><td class="font-semibold">Kapihan sa Kanto</td><td><x-ui.badge tone="success">Active</x-ui.badge></td><td class="text-right">5</td></tr>
                    <tr><td class="font-semibold">Gupit ni Aling Rosa</td><td><x-ui.badge>Hinihintay ang approval</x-ui.badge></td><td class="text-right">1</td></tr>
                </tbody>
            </x-ui.table>
        </x-ui.card>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-ui.empty title="Wala ka pang naka-file na lakad." icon="calendar-days">Kapag may lakad ka, i-file mo rito para alam ng team.<x-slot:action><x-ui.button size="sm" icon="calendar-plus">Mag-file ng lakad</x-ui.button></x-slot:action></x-ui.empty>
            <x-ui.card title="Skeleton"><x-ui.skeleton :lines="3" /></x-ui.card>
        </div>

        <x-ui.card title="Tabs, segmented, dropdown, dialog, toast">
            <div class="flex flex-wrap items-center gap-4">
                <x-ui.tabs label="Tabs" :items="[['label' => 'Team', 'href' => '#', 'active' => true], ['label' => 'Ako', 'href' => '#', 'active' => false]]" />
                <div role="group" aria-label="Segmented" class="inline-flex rounded border border-line bg-papel p-1">
                    <button type="button" aria-pressed="true" class="inline-flex min-h-10 items-center rounded-sm bg-tinta px-4 text-sm font-semibold text-white">Buwan</button>
                    <button type="button" aria-pressed="false" class="inline-flex min-h-10 items-center rounded-sm px-4 text-sm font-semibold hover:bg-hover">Linggo</button>
                </div>
                <x-ui.dropdown label="Mga aksyon" icon-only>
                    <x-ui.dropdown-item type="button" icon="shield-check">Gawing admin</x-ui.dropdown-item>
                    <x-ui.dropdown-item type="button" icon="user-minus" danger>Alisin sa team</x-ui.dropdown-item>
                </x-ui.dropdown>
                <x-ui.button variant="secondary" @click="$dispatch('open-dialog', { name: 'sg-dialog' })">Buksan ang dialog</x-ui.button>
                <x-ui.button variant="secondary" @click="$dispatch('toast', { message: 'Na-save na.' })">Ipakita ang toast</x-ui.button>
            </div>
            <x-ui.dialog name="sg-dialog" title="Dialog" description="Bottom sheet ito sa phone.">
                <p>Escape para isara. Babalik ang focus sa button.</p>
                <x-slot:footer><x-ui.button variant="secondary" @click="close()">Isara</x-ui.button><x-ui.button @click="close()">OK</x-ui.button></x-slot:footer>
            </x-ui.dialog>
        </x-ui.card>

        <x-ui.card title="Icons (Lucide)">
            <div class="flex flex-wrap gap-4 text-tinta">
                @foreach (glob(resource_path('icons/*.svg')) as $file)
                    @php $name = basename($file, '.svg'); @endphp
                    <span class="flex w-24 flex-col items-center gap-1 text-center text-xs text-muted"><x-ui.icon :name="$name" size="20" class="text-tinta" />{{ $name }}</span>
                @endforeach
            </div>
        </x-ui.card>
    </div>
</x-layouts.public>
