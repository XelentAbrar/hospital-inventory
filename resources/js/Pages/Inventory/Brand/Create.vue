<template>
  <Head title="Create Brand" />
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ brand?.id ? "Update" : "Create" }} Brand
      </h2>
    </template>
    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form
            @submit.prevent="
                brand?.id
                  ? brand.put(
                      route('brands.update', {
                        id: brand.id,
                      })
                    )
                  : brand.post(route('brands.store'), brand)
              "
              class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2"
              enctype="multipart/form-data"
            >
              <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-3 md:col-span-2 col-span-12">
                    <label
                      for="name"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-2">
                      <input
                        type="text"
                        name="name"
                        id="name"
                        autocomplete="given-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        v-model="brand.name"
                      />
                    </div>
                    <InputError
                      v-if="brand.errors?.name"
                      :message="brand.errors?.name"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-2 col-span-12">
                    <label
                      for="slug"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Slug</label
                    >
                    <div class="mt-2">
                      <input
                        type="text"
                        name="slug"
                        id="slug"
                        autocomplete="slug-name"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        v-model="brand.slug"
                      />
                    </div>
                    <InputError
                      v-if="brand.errors?.slug"
                      :message="brand.errors?.slug"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-2 col-span-12">
                    <label
                      for="description"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Description</label
                    >
                    <div class="mt-2">
                      <input
                        type="text"
                        name="description"
                        id="description"
                        autocomplete="description"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        v-model="brand.description"
                      />
                    </div>
                    <InputError
                      v-if="brand.errors?.description"
                      :message="brand.errors?.description"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-3 col-span-12">
                    <label
                      for="logo"
                      class="block text-sm font-medium leading-6 text-gray-900"
                      >Logo</label
                    >
                    <file-pond
                    name="logo"
                    ref="pond"
                    credits="false"
                    v-bind:allow-multiple="false"
                    accepted-file-types = "image/png, image/jpeg, image/jpg"
                    :server="{
                        url: '',
                        timeout: 7000,
                        process: {
                            url:'/upload-files',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            withCredentials: false,
                            onload: handleFilePondLoad,
                            ondata:(fileData) => {
                              fileData.append('prefix', 'brn-');
                                  fileData.append('name', 'logo');
                                  fileData.append('path', 'uploads/Inventory');
                                  fileData.append('private', 0);
                                  return fileData;
                              }
                        }
                    }"
                    v-bind:files="myFiles"
                    v-on:init="handleFilePondInit"
                  >
                  </file-pond>
                  <InputError
                      v-if="brand.errors?.logo"
                      :message="brand.errors?.logo"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-3 col-span-12">
                    <div class="mt-2">
                      <SwitchGroup
                        as="div"
                        class="flex items-center justify-between"
                      >
                        <span class="flex flex-grow flex-col">
                          <SwitchLabel
                            as="span"
                            class="text-sm font-medium leading-6 text-gray-900"
                            passive
                            >Status</SwitchLabel
                          >
                          <SwitchDescription
                            as="span"
                            class="text-sm text-gray-900"
                            >Active when toggled on, Inactive when toggled
                            off.</SwitchDescription
                          >
                        </span>
                        <Switch
                          v-model="enabled"
                          :class="[
                            enabled ? 'bg-indigo-600' : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
                          ]"
                        >
                          <span
                            aria-hidden="true"
                            :class="[
                              enabled ? 'translate-x-5' : 'translate-x-0',
                              'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                            ]"
                          />
                        </Switch>
                      </SwitchGroup>
                    </div>
                    <InputError
                      v-if="brand.errors?.status"
                      :message="brand.errors?.status"
                    />
                  </div>
                </div>
              </div>
              <div
                class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8"
              >
                <InertiaLink
                class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                  :href="route('brands.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="brand.processing"
                >
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watch, getCurrentInstance } from "vue";
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css';
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import {
  Switch,
  SwitchDescription,
  SwitchGroup,
  SwitchLabel,
} from "@headlessui/vue";
import InputError from "../../Components/InputError.vue";
const props = defineProps({
  brand: {
    type: Object,
    default: null,
  },
});

const brand = useForm({
    id: props?.brand?.id || null,
  name: props?.brand?.name || null,
  slug: props?.brand?.slug || null,
  description: props?.brand?.description || null,
  logo: props?.brand?.logo || null,
  status: props?.brand?.status || "active",
});

const instance = getCurrentInstance();

watch(
  () => brand.name,
  (newValue) => {
    if (newValue) {
      brand.slug =
        instance.appContext.config.globalProperties.slugify(newValue);
    }
  }
);

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
const init = ref(false);

const FilePond  = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);

const handleFilePondInit = () => {
  if (props?.brand?.logo) {
    myFiles.value = [
      {
        source: '/uploads/Inventory/Brands/' + brand.logo,
        options: {
          type: 'local',
          metadata: {
            poster: '/uploads/Inventory/Brands/' + brand.logo,
          },
        },
      },
    ];
  } else {
    myFiles.value = [];
  }
};

const handleFilePondLoad = (response) => {
  const parsedResponse = JSON.parse(response);
  const filename = parsedResponse.logo;

  if (!init.value) {
    brand.logo = ref(filename);
    init.value = true;
  } else {
    brand.logo.value = filename;
  }
  console.log(brand.logo.value);
};

const enabled = ref(true);

watch(enabled, (newValue) => {
  brand.status = newValue ? "active" : "inactive";
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
.filepond--panel-root {
  background-color: transparent;
  border: 2px solid #2c3340;
}
.filepond--item {
  width: calc(99% - 0.5em);
  height: auto;
}
</style>
