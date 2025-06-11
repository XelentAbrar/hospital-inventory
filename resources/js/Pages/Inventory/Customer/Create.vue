<template>
  <Head title="Create Customer" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ customer?.id ? "Update" : "Create" }} Customer
          </h1>
            <form
              @submit.prevent="
                customer?.id
                  ? customer.put(
                      route('customers.update', {
                        id: customer.id,
                      })
                    )
                  : customer.post(route('customers.store'), customer)
              "
              enctype="multipart/form-data"
            >
              <!-- Profile section -->
              <div class="pt-6">
                <div>
                  <h2 class="text-2xl font-medium leading-6 text-gray-900">
                    Customers
                  </h2>
                  <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                    <div>
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
                        v-model="customer.name"
                      />
                      <InputError
                        v-if="customer.errors?.name"
                        :message="customer.errors?.name"
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
                        v-model="customer.email"
                      />
                      <InputError
                        v-if="customer.errors?.email"
                        :message="customer.errors?.email"
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
                      v-model="customer.phone"
                    />
                    <InputError
                      v-if="customer.errors?.phone"
                      :message="customer.errors?.phone"
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
                      v-model="customer.landline"
                    />
                    <InputError
                      v-if="customer.errors?.landline"
                      :message="customer.errors?.landline"
                    />
                  </div>
                  <div>
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
                      v-model="customer.address"
                    />
                    <InputError
                      v-if="customer.errors?.address"
                      :message="customer.errors?.address"
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
                      v-model="customer.postal_code"
                    />
                    <InputError
                      v-if="customer.errors?.postal_code"
                      :message="customer.errors?.postal_code"
                    />
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
                      v-if="customer.errors?.country_id"
                      :message="customer.errors?.country_id"
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
                      v-if="customer.errors?.state_id"
                      :message="customer.errors?.state_id"
                    />
                  </div>
                  <div>
                    <label
                      for="city"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >City</label
                    >
                    <div class="mt-2">
                      <multiselect
                        v-model="selectedCity"
                        :options="cityOptions"
                        :custom-label="cityCustomLabel"
                        @select="onCitySelect"
                        placeholder="Select a City"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="customer.errors?.city_id"
                      :message="customer.errors?.city_id"
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
                      v-model="customer.opening_balance"
                    />
                    <InputError
                      v-if="customer.errors?.opening_balance"
                      :message="customer.errors?.opening_balance"
                    />
                  </div>
                  <div>
                    <label
                      for="opening_type"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Opening Type</label
                    >
                    <multiselect
                      v-model="selectedOpeningType"
                      :options="OpeningTypeOptions"
                      :custom-label="OpeningTypeCustomLabel"
                      @select="onOpeningTypeSelect"
                      label="type"
                      track-by="type"
                      placeholder="Select Opening Type"
                      class="mt-1"
                    ></multiselect>
                    <InputError
                      v-if="customer.errors?.opening_type"
                      :message="customer.errors?.opening_type"
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
                        v-model="customer.tax_number"
                      />
                      <InputError
                        v-if="customer.errors?.tax_number"
                        :message="customer.errors?.tax_number"
                      />
                  </div>
                  <div class="col-span-3">
                    <label
                      for="notes"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Notes</label
                    >
                      <textarea
                        rows="2"
                        name="notes"
                        id="notes"
                        autocomplete="notes"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="customer.notes"
                      ></textarea>
                      <InputError
                        v-if="customer.errors?.notes"
                        :message="customer.errors?.notes"
                      />
                  </div>

                </div>

              </div>

              <!-- Privacy section -->
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
                  v-if="customer.errors?.status"
                  :message="customer.errors?.status"
                />
                <div class="flex items-center justify-end gap-4 pt-8 py-6">
                  <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('customers.index')"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="customer.processing"
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
  customer: { type: Object, required: false, default: null },
  countries: { type: Array, required: false, default: () => [] },
  states: { type: Array, required: false, default: () => [] },
  cities: { type: Array, required: false, default: () => [] },
});

const customer = useForm({
  id: props?.customer?.id || null,
  name: props?.customer?.name || null,
  email: props?.customer?.email || null,
  phone: props?.customer?.phone || null,
  landline: props?.customer?.landline || null,
  country_id: props?.customer?.country_id || null,
  state_id: props?.customer?.state_id || null,
  city_id: props?.customer?.city_id || null,
  address: props?.customer?.address || null,
  postal_code: props?.customer?.postal_code || null,
  opening_balance: props?.customer?.opening_balance || null,
  opening_type: props?.customer?.opening_type || null,
  tax_number: props?.customer?.tax_number || null,
  notes: props?.customer?.notes || null,
  status: props?.customer?.status || "active",
});

const countries = ref(props.countries || []);
const states = ref(props.states || []);
const cities = ref(props.cities || []);

const selectedCountry = ref(null);
if (props?.customer?.country_id) {
  const country = props.countries.find(
    (c) => c.id === props.customer.country_id
  );
  if (country) {
    selectedCountry.value = country;
  }
}
const selectedState = ref(null);
if (props?.customer?.state_id) {
  const state = props.states.find((c) => c.id === props.customer.state_id);
  if (state) {
    selectedState.value = state;
  }
}
const selectedCity = ref(null);
if (props?.customer?.city_id) {
  const city = props.cities.find((c) => c.id === props.customer.city_id);
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
if (props?.customer?.opening_type) {
  selectedOpeningType.value = OpeningTypeOptions.value.find(
    (option) => option.type === props.customer.opening_type
  );
}

const OpeningTypeCustomLabel = (option) => option.type;

const onOpeningTypeSelect = async (selectedOpeningType) => {
  customer.opening_type = selectedOpeningType.type;
};

const onCountrySelect = async (selectedCountry) => {
  customer.country_id = selectedCountry.id;
  selectedState.value = null;
  await loadStates(selectedCountry.id);
};

const onStateSelect = async (selectedState) => {
  customer.state_id = selectedState.id;
  selectedCity.value = null;
  await loadCities(selectedState.id);
};

const onCitySelect = (selectedCity) => {
  customer.city_id = selectedCity.id;
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

const enabled = ref(true);

watch(enabled, (newValue) => {
  customer.status = newValue ? "active" : "inactive";
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

