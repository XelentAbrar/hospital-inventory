<template>
<Head title="Update Supplier" />
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Supplier</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submitForm" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2" enctype="multipart/form-data">

            <div class="px-4 py-6 sm:p-6 lg:pb-8">
              <div>
                <h2 class="text-lg font-medium leading-6 text-gray-900">Supplier</h2>
                <p class="mt-1 text-sm text-gray-900">This information will be displayed publicly so be careful what you share.</p>
              </div>

              <div class="mt-6 grid grid-cols-12 gap-6">
                <div class="col-span-12 sm:col-span-8 grid grid-cols-12 gap-6">
                  <div class="col-span-12 sm:col-span-6">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                    <input type="text" name="name" id="name" autocomplete="given-name" class="mt-2 block w-full rounded-md border-0 px-3 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:border-0 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.name">
                    <div v-if="supplier.errors.name">{{ supplier.errors.name }}</div>
                  </div>
                  <div class="col-span-12 sm:col-span-6">
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email Address</label>
                    <input type="text" name="email" id="email" autocomplete="family-name" class="mt-2 block w-full rounded-md border-0 px-3 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:border-0 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.email">
                    <div v-if="supplier.errors.email">{{ supplier.errors.email }}</div>
                  </div>
                </div>
                <div class="col-span-12 sm:col-span-4">
                  <label for="logo" class="block text-sm font-medium leading-6 text-gray-900">Logo</label>
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
                                  fileData.append('prefix', 'sup-');
                                  fileData.append('name', 'logo');
                                  fileData.append('path', 'uploads/Inventory');
                                  return fileData;
                              }
                        }
                    }"
                    v-bind:files="myFiles"
                    v-on:init="handleFilePondInit"
                  >
                  </file-pond>
                </div>
                  </div>

              <div class="mt-6 grid grid-cols-12 gap-6">
                <div class="col-span-12 sm:col-span-6">
                  <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Phone</label>
                  <input type="text" name="phone" id="phone" autocomplete="phone-number" class="mt-2 block w-full rounded-md border-0 px-3 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:border-0 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.phone">
                </div>

                <div class="col-span-12 sm:col-span-6">
                  <label for="landline" class="block text-sm font-medium leading-6 text-gray-900">Landline</label>
                  <input type="text" name="landline" id="landline" autocomplete="landline-number" class="mt-2 block w-full rounded-md border-0 px-3 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:border-0 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.landline">
                </div>

                <div class="col-span-12 sm:col-span-10">
                  <label for="address" class="block text-sm font-medium leading-6 text-gray-900">Street Address</label>
                  <input type="text" name="address" id="address" class="mt-2 block w-full rounded-md border-0 px-3 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:border-0 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.address">
                </div>
                <div class="col-span-12 sm:col-span-2">
                  <label for="postal_code" class="block text-sm font-medium leading-6 text-gray-900">Postal Code</label>
                  <input type="text" name="postal_code" id="postal_code" class="mt-2 block w-full rounded-md border-0 px-3 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:border-0 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.postal_code">
                </div>
                <div class="col-span-12 sm:col-span-4">
                  <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Country</label>
                  <div class="mt-2">
                    <multiselect
                      id="country_id"
                      v-model="selectedCountry"
                      :options="countryOptions"
                      :searchable="true"
                      :close-on-select="true"
                      :allow-empty="false"
                      placeholder="Select a country"
                      label="name"
                      track-by="id"
                      :custom-label="countryCustomLabel"
                      @select="onCountrySelect"
                    />
                  </div>
                </div>

                <div class="col-span-12 sm:col-span-4">
                  <label for="state" class="block text-sm font-medium leading-6 text-gray-900">State</label>
                  <div class="mt-2">
                    <multiselect
                      id="state_id"
                      v-model="selectedState"
                      :options="stateOptions"
                      :searchable="true"
                      :close-on-select="true"
                      :allow-empty="false"
                      placeholder="Select a state"
                      label="name"
                      track-by="id"
                      :custom-label="stateCustomLabel"
                      @select="onStateSelect"
                    />
                  </div>
                </div>

                <div class="col-span-12 sm:col-span-4">
                  <label for="city" class="block text-sm font-medium leading-6 text-gray-900">City</label>
                  <div class="mt-2">
                    <multiselect
                      id="city_id"
                      v-model="selectedCity"
                      :options="cityOptions"
                      :searchable="true"
                      :close-on-select="true"
                      :allow-empty="false"
                      placeholder="Select a city"
                      label="name"
                      track-by="id"
                      :custom-label="cityCustomLabel"
                      @select="onCitySelect"
                    />
                  </div>
                </div>
                <div class="col-span-12 sm:col-span-6">
                  <label for="opening_balance" class="block text-sm font-medium leading-6 text-gray-900">Opening Balance</label>
                  <input type="number" name="opening_balance" id="opening_balance" autocomplete="probation-start-date" class="mt-2 block w-full rounded-md border-0 px-3 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:border-0 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.opening_balance">
                </div>
                <div class="col-span-12 sm:col-span-3">
                  <label for="opening_type" class="block text-sm font-medium leading-6 text-gray-900">Opening Type</label>
                  <multiselect
                      v-model="selectedOpeningType"
                      :options="OpeningTypeOptions"
                      :custom-label="OpeningTypeCustomLabel"
                      @select="onOpeningTypeSelect"
                      label="type"
                      track-by="type"
                      placeholder="Select Opening Type"
                    ></multiselect>
                </div>
                <div class="col-span-12 sm:col-span-3">
                  <label for="tax_number" class="block text-sm font-medium leading-6 text-gray-900">Tax Number</label>
                  <div class="mt-2 flex rounded-md shadow-sm">
                    <input type="text" name="tax_number" id="tax_number" autocomplete="tax_number" class="block w-full min-w-0 flex-grow rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.tax_number">
                  </div>
                </div>
                <div class="col-span-12 sm:col-span-3">
                  <label for="notes" class="block text-sm font-medium leading-6 text-gray-900">Notes</label>
                  <div class="mt-2 flex rounded-md shadow-sm">
                    <input type="text" name="notes" id="notes" autocomplete="notes" class="block w-full min-w-0 flex-grow rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6" v-model="supplier.notes">
                  </div>
                </div>
              </div>
            </div>
            <!-- Contact Person section -->
            <div class="divide-y divide-gray-200 pt-4 pb-4">
              <div class="px-4 sm:px-6">
                <h3 class="text-lg font-semibold py-2">Supplier Contacts</h3>
                <div v-for="(supplier_contact, index) in supplier.supplier_contact" :key="index">
                  <div class="flex -space-x-px">
                    <div class="w-1/2 min-w-0 flex-1">
                      <label for="card-expiration-date" class="sr-only">Name</label>
                      <input v-model="supplier_contact.name" type="text" class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Name">
                    </div>
                    <div class="min-w-0 flex-1">
                      <label for="designation" class="sr-only">Designation</label>
                      <input v-model="supplier_contact.designation" type="text" class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Designation">
                    </div>
                    <div class="min-w-0 flex-1">
                      <label for="department" class="sr-only">Department</label>
                      <input v-model="supplier_contact.department" type="text" class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Department">
                    </div>
                    <div class="min-w-0 flex-1">
                      <label for="contact" class="sr-only">Contact</label>
                      <input v-model="supplier_contact.contact" type="text" class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Contact">
                    </div>
                    <div class="min-w-0 flex-1">
                      <label for="notes" class="sr-only">Notes</label>
                      <input v-model="supplier_contact.notes" type="text" class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Notes">
                    </div>
                    <button type="button" @click="removeSupplierContact(index)" class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  </button>
                  </div>
                </div>
                <button type="button" @click="addSupplierContact" class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add More
              </button>
              </div>
            </div>
            <div class="divide-y divide-gray-200 pt-4 pb-4">
              <div class="px-4 sm:px-6">
                <SwitchGroup as="div" class="flex items-center justify-between">
                      <span class="flex flex-grow flex-col">
                        <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>Status</SwitchLabel>
                        <SwitchDescription as="span" class="text-sm text-gray-900">Active when toggled on, Inactive when toggled off.</SwitchDescription>
                      </span>
                      <Switch v-model="enabled" :class="[enabled ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
                        <span aria-hidden="true" :class="[enabled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                      </Switch>
                    </SwitchGroup>
              </div>
              <div class="mt-4 flex gap-x-3 px-4 py-4 sm:px-6" :class="{ 'justify-between': supplier.isDirty, 'justify-end': !supplier.isDirty }">
                <div class="text-orange-400 italic text-xs" v-if="supplier.isDirty">There are unsaved form changes.</div>
                <div class="flex gap-x-6">
                  <button type="button" class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" @click="cancel">Cancel</button>
                  <button type="submit" class="inline-flex justify-center rounded-md bg-sky-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700" :disabled="supplier.processing">Save & Update</button>
                </div>
              </div>
            </div>
    </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref, watchEffect,computed,watch, onMounted  } from 'vue';
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css';
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import Multiselect from 'vue-multiselect'
import axios from 'axios';
import { Switch, SwitchDescription, SwitchGroup, SwitchLabel } from '@headlessui/vue'

const countries = ref([]);
const selectedCountry = ref(null);

const states = ref([]);
const selectedState = ref(null);

const cities = ref([]);
const selectedCity = ref(null);


const FilePond  = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);

const supplier = useForm({
  id: props.supplier.id,
  name: props.supplier.name,
  email: props.supplier.email,
  phone: props.supplier.phone,
  landline: props.supplier.landline,
  logo: props.supplier.logo,
  country_id: props.supplier.country_id,
  state_id: props.supplier.state_id,
  city_id: props.supplier.city_id,
  address: props.supplier.address,
  postal_code: props.supplier.postal_code,
  opening_balance: props.supplier.opening_balance,
  opening_type: props.supplier.opening_type,
  tax_number: props.supplier.tax_number,
  notes: props.supplier.notes,
  status: props.supplier.status,
  supplier_contact: props.supplier.supplier_contact || [],
  _method: 'put'
});

const props = defineProps({
  supplier: { type: Object, required: true },
  countries: { type: Array, required: false, default: () => [] },
  states: { type: Array, required: false, default: () => [] },
  cities: { type: Array, required: false, default: () => [] },
});



const countryOptions = computed(() => {
  return props.countries.length ? props.countries : [];
});

const stateOptions = computed(() => {
  return states.value && states.value.length ? states.value : [];
});

const cityOptions = computed(() => {
  return cities.value && cities.value.length ? cities.value : [];
});

watchEffect(() => {
  if (props.countries?.length) {
    countries.value = props.countries;
  }
});


watchEffect(() => {
  if (props.states?.length) {
    states.value = props.states;
  }
});

watchEffect(() => {
  if (props.cities?.length) {
    cities.value = props.cities;
  }
});


const onCountrySelect = async (selectedCountry) => {
  supplier.country_id = selectedCountry.id;
  selectedState.value = null;
  selectedCity.value = null;
  await loadStates(selectedCountry.id);
  console.log('Country selected:', selectedCountry);
};

const onStateSelect = async (selectedState) => {
  supplier.state_id = selectedState.id;
  selectedCity.value = null;
  await loadCities(selectedState.id);
};


const setInitialState = () => {
  // Set initial country value
  if (props.countries?.length) {
    const country = props.countries.find((c) => c.id === props.supplier.country_id);
    if (country) {
      selectedCountry.value = country;
    }
  }

  // Set initial state value
  if (props.states?.length) {
    const state = props.states.find((s) => s.id === props.supplier.state_id);
    if (state) {
      selectedState.value = state;
    }
  }

  // Set initial city value
  if (props.cities?.length) {
    const city = props.cities.find((c) => c.id === props.supplier.city_id);
    if (city) {
      selectedCity.value = city;
    }
  }
};




watch(
  () => props.countries,
  () => {
    if (props.countries?.length) {
      countries.value = props.countries;
    }
  }
);

watch(
  () => props.states,
  () => {
    if (props.states?.length) {
      states.value = props.states;
    }
  }
);

watch(
  () => props.cities,
  () => {
    if (props.cities?.length) {
      cities.value = props.cities;
    }
  }
);

setInitialState();


const onCitySelect = (selectedCity) => {
  supplier.city_id = selectedCity.id;
};

const stateCustomLabel = (state) => {
  return `${state.name}`;
};


const countryCustomLabel = (country) => {
  return `${country.name}`;
};

const cityCustomLabel = (city) => {
  return `${city.name}`;
};

let myFiles = ref([]);
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let init = ref(false);

const cancel = () => {
  Inertia.visit(route('suppliers.index'));
};

const handleFilePondInit = () => {
  if (supplier.logo) {
    myFiles.value = [
      {
        source: '/uploads/Inventory/Suppliers/' + supplier.logo,
        options: {
          type: 'local',
          metadata: {
            poster: '/uploads/Inventory/Suppliers/' + supplier.logo,
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

onMounted(async () => {
  if (selectedCountry.value) {
    await loadStates(selectedCountry.value.id);
  }
  if (selectedState.value) {
    await loadCities(selectedState.value.id);
  }
});

const loadStates = async (countryId) => {
  try {
    const response = await axios.get(`/states/${countryId}`);
    states.value = response.data.states;
    console.log('Loaded states:', states.value);
  } catch (error) {
    console.error('Error fetching states:', error);
  }
};

const loadCities = async (stateId) => {
  try {
    const response = await axios.get(`/cities-by-state/${stateId}`);
    cities.value = response.data.cities;
    if (!selectedCity.value) {
      selectedCity.value = cities.value.find((c) => c.id === props.supplier.city_id);
    }
  } catch (error) {
    console.error("Error loading cities:", error);
  }
};

// Opening type
const selectedOpeningType = ref(null);
const OpeningTypeOptions = computed(() => {
  const openingTypes = [
  { type: 'payable' },
    { type: 'receiveable' },
  ];
  return openingTypes;
});

const OpeningTypeCustomLabel = (option) => option.type;

const onOpeningTypeSelect = async (selectedOpeningType) => {
  supplier.opening_type = selectedOpeningType.type;
};

onMounted(() => {
  // Set the selectedOpeningType ref based on the current opening_type value.
  selectedOpeningType.value = OpeningTypeOptions.value.find(
    (option) => option.type === props.supplier.opening_type
  );
});

//supplier contact
const addSupplierContact = () => {
    supplier.supplier_contact.push({
        name: '',
        designation: '',
        department: '',
        contact: '',
        notes: '',
    });
};

const removeSupplierContact = (index) => {
    supplier.supplier_contact.splice(index, 1);
};

const enabled = ref(props.supplier.status === 'active');
watch(enabled, (newValue) => {
  supplier.status = newValue ? 'active' : 'inactive';
});

const submitForm = () => {
  supplier.post('/suppliers/' + supplier.id);
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

</style>