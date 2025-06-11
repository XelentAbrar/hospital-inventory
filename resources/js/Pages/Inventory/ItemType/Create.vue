<template>
    <Head title="Create Product Type" />
    <AppLayout>
      <div class="w-full pt-6">
        <div class="mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ item_type?.id ? "Update" : "Create" }} Product Type
            </h1>
            <form
                @submit.prevent="
                  item_type?.id
                    ? item_type.put(
                        route('item_types.update', {
                          id: item_type.id,
                        })
                      )
                    : item_type.post(route('item_types.store'), item_type)
                "
                enctype="multipart/form-data"
              >
              <div class="grid grid-cols-1 gap-8 sm:grid-cols-3 py-6">
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
                          v-model="item_type.type_name"
                        />
                      <InputError
                        v-if="item_type.errors?.type_name"
                        :message="item_type.errors?.type_name"
                      />
                    </div>
                    <div class="sm:col-span-2">
                      <div class="mt-6">
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
                      </div>
                      <InputError
                        v-if="item_type.errors?.status"
                        :message="item_type.errors?.status"
                      />
                    </div>
                  </div>
                
                  <div class="flex items-center justify-end gap-4 pb-4">
                  <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                    :href="route('item_types.index')"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="item_type.processing"
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
  import {
    Switch,
    SwitchDescription,
    SwitchGroup,
    SwitchLabel,
  } from "@headlessui/vue";
  import InputError from "../../Components/InputError.vue";
  const props = defineProps({
    item_type: {
      type: Object,
      required: false,
      default: null,
    },
  });
  
  const item_type = useForm({
    id: props?.item_type?.id || null,
    type_name: props?.item_type?.type_name || null,
    status: props?.item_type?.status || "active",
  });
  
  const enabled = ref(true);
  
  watch(enabled, (newValue) => {
    item_type.status = newValue ? "active" : "inactive";
  });
  </script>
  