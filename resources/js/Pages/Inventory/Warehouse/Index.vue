<template>
  <Head title="Warehouses" />

  <AppLayout title="Warehouses">
    <div class="py-1">
      <div class="w-full mx-auto px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="sm:flex sm:items-center">
              <div class="sm:flex-auto">
                <h1 class="text-lg font-semibold leading-6 text-gray-900">
                  Warehouses
                </h1>
              </div>
              <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <inertia-link
                  :href="route('warehouses.create')"
                  class="block rounded-md bg-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                  >Create Warehouse</inertia-link
                >
              </div>
            </div>
            <div class="mt-8 flow-root">
              <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle">
                  <table class="min-w-full border-separate border-spacing-0">
                    <thead>
                      <tr>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                        >
                          Name
                        </th>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                        >
                          Location
                        </th>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                        >
                          Phone
                        </th>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                        >
                          Email
                        </th>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                        >
                          Logo
                        </th>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                        >
                          Adress
                        </th>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                        >
                          Status
                        </th>
                        <th
                          scope="col"
                          class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter sm:pr-6 lg:pr-8"
                        >
                          <span class="sr-only">Edit</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(warehouse, index) in warehouses"
                        :key="warehouse.id"
                        :class="index % 2 === 0 ? 'bg-blue-50' : 'bg-white'"
                      >
                        <td
                          class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                        >
                          {{ warehouse.name }}
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                        >
                          {{ warehouse.location }}
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                        >
                          {{ warehouse.phone }}
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                        >
                          {{ warehouse.email }}
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                        >
                        <img
                            v-if="warehouse.logo"
                            class="w-10 h-auto"
                            :src="`/uploads/Inventory/Warehouse/${warehouse.logo}`"
                            alt="{{ warehouse.name }}"
                          />
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                        >
                        {{ warehouse.address }}
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                        >
                          {{ warehouse.status }}
                        </td>
                        <td
                          class="relative whitespace-nowrap border-b border-gray-200 py-4 pr-4 pl-3 text-right text-sm font-medium sm:pr-8 lg:pr-8"
                        >
                          <InertiaLink v-if="role && role.role_id === 1"
                            :href="route('warehouses.edit', warehouse.id)"
                            class="text-primary hover:text-accent"
                            >Edit</InertiaLink
                          >
                          <button v-if="role && role.role_id === 1"
                            @click.prevent="deleteWarehouse(warehouse.id)"
                            type="button"
                            class="text-red-600 hover:text-red-900 ml-4"
                          >
                            Delete
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed } from "vue";
import Swal from 'sweetalert2';

const props = defineProps({
  warehouses: Array,
  role: Object,
});

const deleteWarehouse = (id) => {
//   if (confirm("Are you sure you want to delete this warehouse?")) {
//     return Inertia.delete(route("warehouses.destroy", id));
//   }
Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("warehouses.destroy", id));
        }
    });
};
</script>
