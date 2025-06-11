<template>
  <Head title="Create Sub Category" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ subCategory?.id ? "Update" : "Create" }} Sub Category
          </h1>
            <form
              @submit.prevent="
                subCategory?.id
                  ? subCategory.put(
                      route('sub-categories.update', { id: subCategory.id })
                    )
                  : subCategory.post(route('sub-categories.store'), subCategory)
              "
              enctype="multipart/form-data"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-6 py-6">
                  <div class="sm:col-span-3">
                    <label
                      for="category"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Main Category
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedCategory"
                        :options="categories"
                        :searchable="true"
                        :close-on-select="true"
                        :show-labels="false"
                        @select="onCategorySelect"
                        placeholder="Select category"
                        label="name"
                        track-by="id"
                      />
                      <InputError
                        v-if="subCategory.errors?.category_id"
                        :message="subCategory.errors?.category_id"
                      />
                    </div>
                  </div>
                  <div class="sm:col-span-3">
                    <label
                      for="name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        type="text"
                        name="name"
                        id="name"
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="subCategory.name"
                      />
                    <InputError
                      v-if="subCategory.errors?.name"
                      :message="subCategory.errors?.name"
                    />
                  </div>
                  <div class="sm:col-span-3">
                    <label
                      for="description"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Description</label
                    >
                      <textarea
                        rows="3"
                        name="description"
                        id="description"
                        autocomplete="description"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="subCategory.description"
                      ></textarea>
                    <InputError
                      v-if="subCategory.errors?.description"
                      :message="subCategory.errors?.description"
                    />
                  </div>
                  <div class="sm:col-span-3">
                    <label
                      for="about"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Picture
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <file-pond
                      class="mt-1"
                      name="image"
                      ref="pond"
                      credits="false"
                      v-bind:allow-multiple="false"
                      accepted-file-types="image/png, image/jpeg, image/jpg"
                      :server="{
                        url: '',
                        timeout: 7000,
                        process: {
                          url: '/upload-files',
                          method: 'POST',
                          headers: {
                            'X-CSRF-TOKEN': csrfToken,
                          },
                          withCredentials: false,
                          onload: handleFilePondLoad,
                          ondata: (fileData) => {
                            fileData.append('prefix', 'subcat-');
                            fileData.append('name', 'image');
                            fileData.append('path', 'uploads/Inventory');
                            fileData.append('private', 0);
                            return fileData;
                          },
                        },
                      }"
                      v-bind:files="myFiles"
                      v-on:init="handleFilePondInit"
                    >
                    </file-pond>
                    <InputError
                      v-if="subCategory.errors?.image"
                      :message="subCategory.errors?.image"
                    />
                  </div>
                  <div class="sm:col-span-3">
                    <div class="mt-2">
                      <SwitchGroup
                        as="div"
                        class="flex items-center justify-between"
                      >
                        <span class="flex flex-grow flex-col">
                          <SwitchLabel
                            as="span"
                            class="text-sm sm:text-base font-medium text-gray-900"
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
                      v-if="subCategory.errors?.status"
                      :message="subCategory.errors?.status"
                    />
                  </div>
                </div>
                <div class="flex items-center justify-end gap-4 pt-8 py-6">
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('sub-categories.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="subCategory.processing"
                >
                  Save
                </button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";
import "filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import {
  Switch,
  SwitchDescription,
  SwitchGroup,
  SwitchLabel,
} from "@headlessui/vue";
import Multiselect from "vue-multiselect";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  categories: Array,
  subCategory: {
    type: Object,
    default: null,
  },
});

const subCategory = useForm({
  id: props?.subCategory?.id || null,
  name: props?.subCategory?.name || null,
  category_id: props?.subCategory?.category_id || null,
  description: props?.subCategory?.description || null,
  image: props?.subCategory?.image || null,
  status: props?.subCategory?.status || "active",
  _method: props?.subCategory?.id ? "put" : "post",
});

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);

const selectedCategory = ref(null);
if (props?.subCategory) {
  const category = props.categories.find(
    (c) => c.id === props.subCategory.category_id
  );
  if (category) {
    selectedCategory.value = category;
  }
}
const onCategorySelect = async (selectedCategory) => {
  subCategory.category_id = selectedCategory.id;
};

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
const init = ref(false);

const handleFilePondInit = () => {
  if (props?.subCategory?.image) {
    myFiles.value = [
      {
        source: "/uploads/Inventory/sub-categories/" + subCategory.image,
        options: {
          type: "local",
          metadata: {
            poster: "/uploads/Inventory/sub-categories/" + subCategory.image,
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
  const filename = parsedResponse.image;

  if (!init.value) {
    subCategory.image = ref(filename);
    init.value = true;
  } else {
    subCategory.image.value = filename;
  }
  console.log(subCategory.image.value);
};

const enabled = ref(true);

watch(enabled, (newValue) => {
  subCategory.status = newValue ? "active" : "inactive";
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
