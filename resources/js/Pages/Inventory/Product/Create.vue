<template>
  <Head title="Create Product" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ product?.id ? "Update" : "Create" }} Product
          </h1>
          <form
            @submit.prevent="submitForm()"
            class=""
            enctype="multipart/form-data"
          >
            <div
              class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3 lg:gap-8 mt-6"
            >
              <!-- Left column -->
              <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                <section aria-labelledby="section-1-title">
                  <h2 class="sr-only text-2xl font-medium leading-6 text-gray-900" id="section-1-title">Section title</h2>
                  <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-6">
                      <div
                        class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-9"
                      >
                        <div class="sm:col-span-9">
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
                              v-model="product.name"
                            />
                          <InputError
                            v-if="product.errors?.name"
                            :message="product.errors?.name"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-4">
                          <label
                            for="slug"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Slug
                            <span class="text-red-500">*</span>
                            </label
                          >
                            <input
                              type="text"
                              name="slug"
                              id="slug"
                              autocomplete="slug"
                              class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                              v-model="product.slug"
                            />
                          <InputError
                            v-if="product.errors?.slug"
                            :message="product.errors?.slug"
                          />
                        </div>

                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="product_code"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Product Code</label
                          >
                          <div class="mt-1 flex rounded-md shadow-sm">
                            <div
                              class="relative flex flex-grow items-stretch focus-within:z-10"
                            >
                              <input
                                type="number"
                                name="product_code"
                                id="product_code"
                                class="block w-full rounded-none rounded-l-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 h-11"
                                v-model="product.product_code"
                              />
                            </div>
                            <button
                              type="button"
                              @click="generateProductCode"
                              class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            >
                              <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="-ml-0.5 h-5 w-5 text-gray-400"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"
                                />
                              </svg>
                            </button>
                          </div>
                          <InputError
                            v-if="product.errors?.product_code"
                            :message="product.errors?.product_code"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-2">
                          <label
                            for="sku"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >SKU</label
                          >
                            <input
                              type="text"
                              name="sku"
                              id="sku"
                              autocomplete="sku"
                              class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                              v-model="product.sku"
                            />
                          <InputError
                            v-if="product.errors?.sku"
                            :message="product.errors?.sku"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="uom"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Salt
                            <span class="text-red-500">*</span>
                            </label
                          >
                          <div class="mt-1">
                            <multiselect
                              v-model="selectedSalt"
                              :options="props.salt_items"
                              :searchable="true"
                              :close-on-select="true"
                              :show-labels="false"
                              placeholder="Select Salt"
                              label="salt_name"
                              track-by="id"
                              @select="onSaltSelect"
                            />
                          </div>
                          <InputError
                            v-if="product.errors?.item_salt_id"
                            :message="product.errors?.item_salt_id"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="uom"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Product Type
                            <span class="text-red-500">*</span>
                            </label
                          >
                          <div class="mt-1">
                            <multiselect
                              v-model="selectedItemType"
                              :options="props.item_type"
                              :searchable="true"
                              :close-on-select="true"
                              :show-labels="false"
                              placeholder="Select Product Type"
                              label="type_name"
                              track-by="id"
                              @select="onItemTypeSelect"
                            />
                          </div>
                          <InputError
                            v-if="product.errors?.item_type_id"
                            :message="product.errors?.item_type_id"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="uom"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Product Category
                            <span class="text-red-500">*</span>
                            </label
                          >
                          <div class="mt-1">
                            <multiselect
                              v-model="selectedItemCategory"
                              :options="props.item_category"
                              :searchable="true"
                              :close-on-select="true"
                              :show-labels="false"
                              placeholder="Select Product Category"
                              label="cat_name"
                              track-by="id"
                              @select="onItemCategorySelect"
                            />
                          </div>
                          <InputError
                            v-if="product.errors?.item_category_id"
                            :message="product.errors?.item_category_id"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="uom"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >UOM
                            <span class="text-red-500">*</span>
                            </label
                          >
                          <div class="mt-1">
                            <multiselect
                              v-model="selectedUom"
                              :options="props.uoms"
                              :searchable="true"
                              :close-on-select="true"
                              :show-labels="false"
                              placeholder="Select UOM"
                              label="name"
                              track-by="id"
                              @select="onUomSelect"
                            />
                          </div>
                          <InputError
                            v-if="product.errors?.uom_id"
                            :message="product.errors?.uom_id"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="opening_stock"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Opening Stock</label
                          >
                            <input
                              type="number"
                              name="opening_stock"
                              id="opening_stock"
                              autocomplete="opening_stock"
                              class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                              v-model="product.opening_stock"
                            />
                          <InputError
                            v-if="product.errors?.opening_stock"
                            :message="product.errors?.opening_stock"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="max_qty"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Max Qty</label
                          >
                            <input
                              type="number"
                              name="max_qty"
                              id="max_qty"
                              autocomplete="max_qty"
                              class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                              v-model="product.max_qty"
                            />
                          <InputError
                            v-if="product.errors?.max_qty"
                            :message="product.errors?.max_qty"
                          />
                        </div>
                        <div class="col-span-9 sm:col-span-3">
                          <label
                            for="min_qty"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Min Qty</label
                          >
                            <input
                              type="number"
                              name="min_qty"
                              id="min_qty"
                              autocomplete="min_qty"
                              class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                              v-model="product.min_qty"
                            />
                          <InputError
                            v-if="product.errors?.min_qty"
                            :message="product.errors?.min_qty"
                          />
                        </div>
                        
                        <div class="col-span-9">
                          <label
                            for="short_description"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Short Description</label
                          >
                            <textarea
                              rows="4"
                              name="short_description"
                              id="short_description"
                              class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="product.short_description"
                            ></textarea>
                          <InputError
                            v-if="product.errors?.short_description"
                            :message="product.errors?.short_description"
                          />
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <!-- Right column -->
              <div class="grid grid-cols-1 gap-4">
                <section aria-labelledby="section-2-title">
                  <h2 class="sr-only" id="section-2-title">Section title</h2>
                  <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-6">
                      <div class="space-y-6">
                        <div>
                          <label
                            for="featured_image"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Featured Image</label
                          >
                          <file-pond
                            name="featured_image"
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
                                  fileData.append('prefix', 'pr-');
                                  fileData.append('name', 'featured_image');
                                  fileData.append('path', 'uploads/Inventory');
                                  return fileData;
                                },
                              },
                            }"
                            v-bind:files="myFiles"
                            v-on:init="handleFilePondInit"
                          >
                          </file-pond>
                          <InputError
                            v-if="product.errors?.featured_image"
                            :message="product.errors?.featured_image"
                          />
                        </div>

                        <div class="mt-2">
                          <SwitchGroup
                            as="div"
                            class="flex items-center justify-between"
                          >
                            <span class="flex flex-grow flex-col">
                              <SwitchLabel
                                as="span"
                                class="text-sm md:text-base font-medium text-gray-900"
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
                          <InputError
                            v-if="product.errors?.status"
                            :message="product.errors?.status"
                          />
                        </div>

                        <div class="flex items-center justify-end gap-4 py-6">
                          <InertiaLink
                          class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('products.index')"
                          >
                            Cancel
                          </InertiaLink>
                          <button
                            type="submit"
                            class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                            :disabled="product.processing"
                          >
                            Save
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
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
import {
  ref,
  watch,
  getCurrentInstance,
  computed,
  reactive,
  onMounted,
} from "vue";
import Multiselect from "vue-multiselect";
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
import InputError from "../../Components/InputError.vue";


const props = defineProps({
  product: { type: Object, default: null },
  uoms: { type: Array, required: true },
  item_category: { type: Array, required: true },
  item_type: { type: Array, required: true },
  salt_items: { type: Array, required: true },
});

const product = useForm({
  id: props?.product?.id || null,
  sku: props?.product?.sku || null,
  name: props?.product?.name || null,
  slug: props?.product?.slug || null,
  product_code: props?.product?.product_code || "",
  featured_image: props?.product?.featured_image || null,
  uom_id: props?.product?.uom_id || null,
  item_salt_id: props?.product?.item_salt_id || null,
  item_type_id: props?.product?.item_type_id || null,
  item_category_id: props?.product?.item_category_id || null,
  opening_stock: props?.product?.opening_stock || null,
  max_qty: props?.product?.max_qty || null,
  min_qty: props?.product?.min_qty || null,
  short_description: props?.product?.short_description || null,
  status: props?.product?.status || "active",
});

const selectedUom = ref(
  props?.product?.uom_id
    ? props.uoms.find((uom) => uom.id === props.product.uom_id)
    : null
);

const onUomSelect = (selectedUom) => {
  product.uom_id = selectedUom.id;
};
const selectedItemType = ref(
  props?.product?.item_type_id
    ? props.item_type.find((item_type) => item_type.id === props.product.item_type_id)
    : null
);
const onItemTypeSelect = (selectedItemType) => {
  product.item_type_id = selectedItemType.id;
};
const selectedSalt = ref(
  props?.product?.item_salt_id
    ? props.salt_items.find((item_salt) => item_salt.id === props.product.item_salt_id)
    : null
);
const onSaltSelect = (selectedSalt) => {
  product.item_salt_id = selectedSalt.id;
};
const selectedItemCategory = ref(
  props?.product?.item_category_id
    ? props.item_category.find((item_category) => item_category.id === props.product.item_category_id)
    : null
);
const onItemCategorySelect = (selectedItemCategory) => {
  product.item_category_id = selectedItemCategory.id;
};

// slugify
const instance = getCurrentInstance();

watch(
  () => product.name,
  (newValue) => {
    if (newValue) {
      product.slug =
        instance.appContext.config.globalProperties.slugify(newValue);
    }
  }
);
// end of slugify

// generate Product code
const generateProductCode = () => {
  let code = Math.floor(10000000 + Math.random() * 90000000).toString();
  product.product_code = code;
  // product.slug = code;
};
// end of product code


// Featured Image
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);
const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
const init = ref(false);

const handleFilePondInit = () => {
  if (props?.product?.featured_image) {
    myFiles.value = [
      {
        source: "/uploads/Inventory/Product/" + product?.featured_image,
        options: {
          type: "local",
          metadata: {
            poster: "/uploads/Inventory/Product/" + product?.featured_image,
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
  const filename = parsedResponse.featured_image;

  if (!init.value) {
    product.featured_image = ref(filename);
    init.value = true;
  } else {
    product.featured_image.value = filename;
  }
};
// end of Featured Image



const enabled = ref(props?.product?.status === "active" || true);

watch(enabled, (newValue) => {
  product.status = newValue ? "active" : "inactive";
});

const submitForm = () => {
  product?.id
    ? product.put(route("products.update", { id: product.id }))
    : product.post(route("products.store"), product);
};
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
.ck.ck-content:not(.ck-comment__input *) {
  height: 300px;
  overflow-y: auto;
}
</style>