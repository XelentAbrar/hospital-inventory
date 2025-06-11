<template>
  <Head title="Create Supplier" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ supplier?.id ? "Update" : "Create" }} Supplier
          </h1>
          <form
            @submit.prevent="
              supplier?.id
                ? supplier.put(
                  route('suppliers.update', {
                    id: supplier.id,
                  })
                )
              : supplier.post(route('suppliers.store'), supplier)
            "
            enctype="multipart/form-data"
          >
            <!-- Profile section -->
            <div class="pt-6">
              <h2 class="text-2xl font-medium leading-6 text-gray-900">
                Suppliers
              </h2>
              <p class="mt-1 text-sm text-gray-900">
                This information will be displayed publicly so be careful
                what you share.
              </p>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
              <div>
                <label
                  for="name"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Name
                  <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  name="name"
                  id="name"
                  autocomplete="given-name"
                  class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                  v-model="supplier.name"
                />
                <InputError
                  v-if="supplier.errors?.name"
                  :message="supplier.errors?.name"
                />
              </div>
              <div>
                <label
                  for="email"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Email Address</label
                >
                <input
                  type="text"
                  name="email"
                  id="email"
                  autocomplete="family-name"
                  class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                  v-model="supplier.email"
                />
                <InputError
                  v-if="supplier.errors?.email"
                  :message="supplier.errors?.email"
                />
              </div>
              <div>
                <label
                  for="phone"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Phone</label
                >
                <input
                  type="text"
                  name="phone"
                  id="phone"
                  autocomplete="phone-number"
                  class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                  v-model="supplier.phone"
                />
                <InputError
                  v-if="supplier.errors?.phone"
                  :message="supplier.errors?.phone"
                />
              </div>
              <div>
                <label
                  for="landline"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Landline</label
                >
                <input
                  type="text"
                  name="landline"
                  id="landline"
                  autocomplete="landline-number"
                  class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                  v-model="supplier.landline"
                />
                <InputError
                  v-if="supplier.errors?.landline"
                  :message="supplier.errors?.landline"
                />
              </div>
              <div class="col-span-2 grid grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                  <label
                    for="address"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Street Address</label
                  >
                  <input
                    type="text"
                    name="address"
                    id="address"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    v-model="supplier.address"
                  />
                  <InputError
                    v-if="supplier.errors?.address"
                    :message="supplier.errors?.address"
                  />
                </div>
                <div>
                  <label
                    for="postal-code"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Postal Code</label
                  >
                  <input
                    type="number"
                    name="postal-code"
                    id="postal-code"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    v-model="supplier.postal_code"
                  />
                  <InputError
                    v-if="supplier.errors?.postal_code"
                    :message="supplier.errors?.postal_code"
                  />
                </div>
              </div>
              <div>
                <label
                  for="country"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Country</label
                >
                <div class="mt-1">
                  <multiselect
                    v-model="selectedCountry"
                    :options="countryOptions"
                    :custom-label="countryCustomLabel"
                    @select="onCountrySelect"
                    placeholder="Select a Country"
                  ></multiselect>
                </div>
                <InputError
                  v-if="supplier.errors?.country_id"
                  :message="supplier.errors?.country_id"
                />
              </div>
              <div>
                <label
                  for="state"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >State</label
                >
                <div class="mt-1">
                  <multiselect
                    v-model="selectedState"
                    :options="stateOptions"
                    :custom-label="stateCustomLabel"
                    @select="onStateSelect"
                    placeholder="Select a State"
                  ></multiselect>
                </div>
                <InputError
                  v-if="supplier.errors?.state_id"
                  :message="supplier.errors?.state_id"
                />
              </div>
              <div>
                <label
                  for="city"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >City</label
                >
                <div class="mt-1">
                  <multiselect
                    v-model="selectedCity"
                    :options="cityOptions"
                    :custom-label="cityCustomLabel"
                    @select="onCitySelect"
                    placeholder="Select a City"
                  ></multiselect>
                </div>
                <InputError
                  v-if="supplier.errors?.city_id"
                  :message="supplier.errors?.city_id"
                />
              </div>
              <div>
                <label
                  for="opening_balance"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Opening Balance</label
                >
                <input
                  type="number"
                  name="opening_balance"
                  id="opening_balance"
                  autocomplete="probation-end-date"
                  class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                  v-model="supplier.opening_balance"
                />
                <InputError
                  v-if="supplier.errors?.opening_balance"
                  :message="supplier.errors?.opening_balance"
                />
              </div>
              <div>
                <label
                  for="opening_type"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Opening Type</label
                >
                <multiselect
                  class="mt-1"
                  v-model="selectedOpeningType"
                  :options="OpeningTypeOptions"
                  :custom-label="OpeningTypeCustomLabel"
                  @select="onOpeningTypeSelect"
                  label="type"
                  track-by="type"
                  placeholder="Select Opening Type"
                ></multiselect>
                <InputError
                  v-if="supplier.errors?.opening_type"
                  :message="supplier.errors?.opening_type"
                />
              </div>
              <div>
                <label
                  for="tax_number"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Tax Number</label
                >
                <input
                  type="text"
                  name="tax_number"
                  id="tax_number"
                  autocomplete="tax_number"
                  class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                  v-model="supplier.tax_number"
                />
                <InputError
                  v-if="supplier.errors?.tax_number"
                  :message="supplier.errors?.tax_number"
                />
              </div>
              <div class="col-span-2">
                <label
                  for="notes"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Notes</label
                >
                <textarea
                  rows="3"
                  name="notes"
                  id="notes"
                  autocomplete="notes"
                  class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                  v-model="supplier.notes"
                ></textarea>
                <InputError
                  v-if="supplier.errors?.notes"
                  :message="supplier.errors?.notes"
                />
              </div>
              <div>
                <label
                  for="logo"
                  class="block text-sm md:text-base font-medium text-gray-900"
                 >Logo</label
                >
                <file-pond
                  name="logo"
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
                        fileData.append('prefix', 'sup-');
                        fileData.append('name', 'logo');
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
                  v-if="supplier.errors?.logo"
                  :message="supplier.errors?.logo"
                />
              </div>
            </div>
              <!-- Contact Person section -->
              <div class="divide-y divide-gray-200 mt-6 pt-4 pb-4">
                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-6 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Supplier Contact</h3>
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b" >
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="supplier-name" class="text-center font-medium">Name</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="designation" class="text-center font-medium">Designation</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="department" class="text-center font-medium">Department</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="contact" class="text-center font-medium">Contact</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="notes" class="text-center font-medium">Notes</label>
                      </div>
                      <div class="flex items-center p-1 w-[49px]"></div>
                    </div>
                  <div
                    v-for="(
                      supplier_contact, index
                    ) in supplier.supplier_contact"
                    :key="index"
                  >
                    <div class="flex divide-x border-b">
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="name" class="sr-only">Name</label>
                        <input
                          v-model="supplier_contact.name"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="name"
                        />
                        <InputError
                          v-if="
                            supplier.errors?.[`supplier_contact.${index}.name`]
                          "
                          :message="
                            supplier.errors?.[`supplier_contact.${index}.name`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="designation" class="sr-only"
                          >designation</label
                        >
                        <input
                          v-model="supplier_contact.designation"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Designation"
                        />
                        <InputError
                          v-if="
                            supplier.errors?.[
                              `supplier_contact.${index}.designation`
                            ]
                          "
                          :message="
                            supplier.errors?.[
                              `supplier_contact.${index}.designation`
                            ]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="department" class="sr-only"
                          >Department</label
                        >
                        <input
                          v-model="supplier_contact.department"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Department"
                        />
                        <InputError
                          v-if="
                            supplier.errors?.[
                              `supplier_contact.${index}.department`
                            ]
                          "
                          :message="
                            supplier.errors?.[
                              `supplier_contact.${index}.department`
                            ]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="contact" class="sr-only">Contact</label>
                        <input
                          v-model="supplier_contact.contact"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Contact"
                        />
                        <InputError
                          v-if="
                            supplier.errors?.[
                              `supplier_contact.${index}.contact`
                            ]
                          "
                          :message="
                            supplier.errors?.[
                              `supplier_contact.${index}.contact`
                            ]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="notes" class="sr-only">Notes</label>
                        <input
                          v-model="supplier_contact.notes"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Notes"
                        />
                        <InputError
                          v-if="
                            supplier.errors?.[`supplier_contact.${index}.notes`]
                          "
                          :message="
                            supplier.errors?.[`supplier_contact.${index}.notes`]
                          "
                        />
                      </div>
                      <div class="flex items-center p-1">
                        <button
                          type="button"
                          @click="removeSupplierContact(index)"
                          class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"
                            />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                  </div>
                  <button
                    type="button"
                    @click="addSupplierContact"
                    class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      />
                    </svg>
                    Add More
                  </button>
                </div>
              </div>
              <!-- Privacy section -->
              <div>
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
                      <SwitchDescription as="span" class="text-sm text-gray-900"
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
                  v-if="supplier.errors?.status"
                  :message="supplier.errors?.status"
                />
                </div>
                <div class="flex items-center justify-end gap-4 mt-6 py-4">
                  <InertiaLink
                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                    :href="route('suppliers.index')"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="supplier.processing"
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
import { ref, watch, computed } from "vue";
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";
import "filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import Multiselect from "vue-multiselect";
import axios from "axios";
import {
  Switch,
  SwitchDescription,
  SwitchGroup,
  SwitchLabel,
} from "@headlessui/vue";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  supplier: { type: Object, required: false, default: null },
  countries: { type: Array, required: false, default: () => [] },
  states: { type: Array, required: false, default: () => [] },
  cities: { type: Array, required: false, default: () => [] },
});

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);

const supplier = useForm({
  id: props?.supplier?.id || null,
  name: props?.supplier?.name || null,
  email: props?.supplier?.email || null,
  phone: props?.supplier?.phone || null,
  landline: props?.supplier?.landline || null,
  logo: props?.supplier?.logo || null,
  country_id: props?.supplier?.country_id || null,
  state_id: props?.supplier?.state_id || null,
  city_id: props?.supplier?.city_id || null,
  address: props?.supplier?.address || null,
  postal_code: props?.supplier?.postal_code || null,
  opening_balance: props?.supplier?.opening_balance || null,
  opening_type: props?.supplier?.opening_type || null,
  tax_number: props?.supplier?.tax_number || null,
  notes: props?.supplier?.notes || null,
  status: props?.supplier?.status || "active",
  supplier_contact:
    props?.supplier?.supplier_contact?.length > 0
      ? props?.supplier?.supplier_contact
      : [
          {
            name: "",
            designation: "",
            department: "",
            contact: "",
            notes: "",
          },
        ],
  _method: props?.supplier?.id ? "put" : "post",
});

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
const init = ref(false);

const handleFilePondInit = () => {
  if (props?.supplier?.logo) {
    myFiles.value = [
      {
        source: "/uploads/Inventory/Suppliers/" + props?.supplier?.logo,
        options: {
          type: "local",
          metadata: {
            poster: "/uploads/Inventory/Suppliers/" + props?.supplier?.logo,
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
    supplier.logo = ref(filename);
    init.value = true;
  } else {
    supplier.logo.value = filename;
  }
  console.log(supplier.logo.value);
};

const countries = ref(props.countries || []);
const states = ref(props.states || []);
const cities = ref(props.cities || []);

const selectedCountry = ref(null);
if (props?.supplier?.country_id) {
  const country = props.countries.find(
    (c) => c.id === props.supplier.country_id
  );
  if (country) {
    selectedCountry.value = country;
  }
}
const selectedState = ref(null);
if (props?.supplier?.state_id) {
  const state = props.states.find((c) => c.id === props.supplier.state_id);
  if (state) {
    selectedState.value = state;
  }
}
const selectedCity = ref(null);
if (props?.supplier?.city_id) {
  const city = props.cities.find((c) => c.id === props.supplier.city_id);
  if (city) {
    selectedCity.value = city;
  }
}

const countryOptions = computed(() => {
  return countries.value && countries.value.length ? countries.value : [];
});

const stateOptions = computed(() => {
  return states.value && states.value.length ? states.value : [];
});

const cityOptions = computed(() => {
  return cities.value && cities.value.length ? cities.value : [];
});
// opening type
const selectedOpeningType = ref(null);
const OpeningTypeOptions = computed(() => {
  const openingTypes = [{ type: "payable" }, { type: "receiveable" }];
  return openingTypes;
});
if (props?.supplier?.opening_type) {
  selectedOpeningType.value = OpeningTypeOptions.value.find(
    (option) => option.type === props.supplier.opening_type
  );
}

const OpeningTypeCustomLabel = (option) => option.type;

const onOpeningTypeSelect = async (selectedOpeningType) => {
  supplier.opening_type = selectedOpeningType.type;
};

const onCountrySelect = async (selectedCountry) => {
  supplier.country_id = selectedCountry.id;
  selectedState.value = null;
  await loadStates(selectedCountry.id);
};

const onStateSelect = async (selectedState) => {
  supplier.state_id = selectedState.id;
  selectedCity.value = null;
  await loadCities(selectedState.id);
};

const onCitySelect = (selectedCity) => {
  supplier.city_id = selectedCity.id;
};

const loadStates = async (countryId) => {
  try {
    const response = await axios.get(`/states/${countryId}`);
    console.log("Loaded states:", response.data);
    states.value = response.data.states;
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};

const loadCities = async (stateId) => {
  try {
    const response = await axios.get(`/cities-by-state/${stateId}`);
    cities.value = response.data.cities;
  } catch (error) {
    console.error("Error fetching cities:", error);
  }
};

const countryCustomLabel = (country) => {
  return `${country.name}`;
};

const stateCustomLabel = (state) => {
  return `${state.name}`;
};

const cityCustomLabel = (city) => {
  return `${city.name}`;
};

//supplier_contact
const addSupplierContact = () => {
  supplier.supplier_contact.push({
    name: "",
    designation: "",
    department: "",
    contact: "",
    notes: "",
  });
};

const removeSupplierContact = (index) => {
  supplier.supplier_contact.splice(index, 1);
};

const enabled = ref(true);

watch(enabled, (newValue) => {
  supplier.status = newValue ? "active" : "inactive";
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