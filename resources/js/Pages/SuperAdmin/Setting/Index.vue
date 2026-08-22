<script setup>
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { computed, ref } from 'vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const form = useForm('post', '/admin/settings', {
    logo: null,
    favicon: null,
    title: props.settings.title,
    meta_title: props.settings.meta_title,
    og_title: props.settings.og_title,
    meta_description: props.settings.meta_description,
});

const logoPreview = ref(null);
const logoInput = ref(null);

const selectNewLogo = () => {
    logoInput.value.click();
};

const faviconPreview = ref(null);
const faviconInput = ref(null);

const selectNewFavicon = () => {
    faviconInput.value.click();
};

const submit = () => {
    if (logoInput.value) {
        form.logo = logoInput.value.files[0];
    }
    if (faviconInput.value) {
        form.favicon = faviconInput.value.files[0];
    }
    form.submit({
        // onSuccess: () => clearPhotoFileInput(),
    });
};
const updateLogoPreview = () => {
    const logo = logoInput.value.files[0];

    if (!logo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        logoPreview.value = e.target.result;
    };

    reader.readAsDataURL(logo);
};

const updateFaviconPreview = () => {
    const favicon = faviconInput.value.files[0];

    if (!favicon) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        faviconPreview.value = e.target.result;
    };

    reader.readAsDataURL(favicon);
};

const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head title="Setting" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <section class="max-w-xl">
                        <header>
                            <h2
                                class="text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ $page.props.lang['Setting Information'] }}
                            </h2>

                            <p
                                class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                            >
                                {{
                                    $page.props.lang[
                                        'Enter the setting information.'
                                    ]
                                }}
                            </p>
                        </header>
                        <form
                            @submit.prevent="
                                form.post(route('super_admin.settings.update'))
                            "
                            class="mt-6 space-y-6"
                            enctype="multipart/form-data"
                        >
                            <div>
                                <!-- Hidden File Input -->
                                <input
                                    id="logo"
                                    ref="logoInput"
                                    type="file"
                                    class="hidden"
                                    @change="updateLogoPreview"
                                />

                                <Label for="logo"
                                    >{{ $page.props.lang['Logo'] }} *</Label
                                >

                                <!-- Current Profile Photo -->
                                <div v-show="!logoPreview" class="mt-2">
                                    <img
                                        :src="'/storage/' + settings.logo"
                                        :alt="settings.title"
                                        class="size-20 rounded-full object-cover"
                                    />
                                </div>

                                <!-- New Profile Photo Preview -->
                                <div v-show="logoPreview" class="mt-2">
                                    <span
                                        class="block size-20 rounded-full bg-cover bg-center bg-no-repeat"
                                        :style="
                                            'background-image: url(\'' +
                                            logoPreview +
                                            '\');'
                                        "
                                    />
                                </div>

                                <Button
                                    class="me-2 mt-2"
                                    @click.prevent="selectNewLogo"
                                >
                                    {{ $page.props.lang['Select A New Logo'] }}
                                </Button>

                                <InputError
                                    :message="form.errors.logo"
                                    class="mt-2"
                                />
                            </div>
                            <div>
                                <!-- Hidden File Input -->
                                <input
                                    id="favicon"
                                    ref="faviconInput"
                                    type="file"
                                    class="hidden"
                                    @change="updateFaviconPreview"
                                />

                                <Label for="favicon"
                                    >{{ $page.props.lang['Favicon'] }} *</Label
                                >

                                <!-- Current Profile Photo -->
                                <div v-show="!faviconPreview" class="mt-2">
                                    <img
                                        :src="'/storage/' + settings.favicon"
                                        :alt="settings.title"
                                        class="size-20 rounded-full object-cover"
                                    />
                                </div>

                                <!-- New Profile Photo Preview -->
                                <div v-show="faviconPreview" class="mt-2">
                                    <span
                                        class="block size-20 rounded-full bg-cover bg-center bg-no-repeat"
                                        :style="
                                            'background-image: url(\'' +
                                            faviconPreview +
                                            '\');'
                                        "
                                    />
                                </div>

                                <Button
                                    class="me-2 mt-2"
                                    @click.prevent="selectNewFavicon"
                                >
                                    {{
                                        $page.props.lang['Select A New Favicon']
                                    }}
                                </Button>

                                <InputError
                                    :message="form.errors.favicon"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <Label for="title"
                                    >{{
                                        $page.props.lang['Site Title']
                                    }}
                                    *</Label
                                >

                                <Input
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autocomplete="title"
                                    @change="form.validate('title')"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>
                            <div>
                                <Label for="meta_title">{{
                                    $page.props.lang['Meta Title']
                                }}</Label>

                                <Input
                                    id="meta_title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.meta_title"
                                    required
                                    autocomplete="meta_title"
                                    @change="form.validate('meta_title')"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.meta_title"
                                />
                            </div>
                            <div>
                                <Label for="og_title">{{
                                    $page.props.lang['OG Title']
                                }}</Label>

                                <Input
                                    id="og_title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.og_title"
                                    required
                                    autocomplete="og_title"
                                    @change="form.validate('og_title')"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.og_title"
                                />
                            </div>

                            <div>
                                <Label for="city"
                                    >{{ $page.props.lang['Meta Description'] }}
                                </Label>

                                <Textarea
                                    id="meta_description"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.meta_description"
                                    autocomplete="meta_description"
                                    @change="form.validate('meta_description')"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.meta_description"
                                />
                            </div>

                            <div
                                v-if="can['manage setting'] ?? false"
                                class="flex items-center gap-4"
                            >
                                <Button
                                    :disabled="form.processing"
                                    @click="submit"
                                    >{{ $page.props.lang['Update'] }}</Button
                                >
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
