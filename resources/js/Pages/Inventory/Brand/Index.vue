<template>
    <Head title="Brands" />

    <AppLayout title="Brands">
        <div class="py-1">
            <div class="w-full mx-auto px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1
                                    class="text-lg font-semibold leading-6 text-gray-900"
                                >
                                    Brands
                                </h1>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <inertia-link
                                    :href="route('brands.create')"
                                    class="block rounded-md bg-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                                    >Create Brand</inertia-link
                                >
                            </div>
                        </div>
                        <div class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                                <div
                                    class="inline-block min-w-full py-2 align-middle"
                                >
                                    <table
                                        class="min-w-full border-separate border-spacing-0"
                                    >
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
                                                    description
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8"
                                                >
                                                    Image
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
                                                    <span class="sr-only"
                                                        >Edit</span
                                                    >
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    brand, index
                                                ) in brands.data"
                                                :key="brand.id"
                                                :class="
                                                    index % 2 === 0
                                                        ? 'bg-blue-50'
                                                        : 'bg-white'
                                                "
                                            >
                                                <td
                                                    class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                                                >
                                                    {{ brand.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                                                >
                                                    {{ brand.description }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                                                >
                                                    <img
                                                        v-if="brand.logo"
                                                        class="w-10 h-auto"
                                                        :src="`/uploads/Inventory/Brands/${brand.logo}`"
                                                        alt="{{ brand.name }}"
                                                    />
                                                </td>
                                                <td
                                                    class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8"
                                                >
                                                    {{ brand.status }}
                                                </td>
                                                <td
                                                    class="relative whitespace-nowrap border-b border-gray-200 py-4 pr-4 pl-3 text-right text-sm font-medium sm:pr-8 lg:pr-8"
                                                >
                                                <div v-if="role && role.role_id === 1">
                                                    <InertiaLink
                                                        :href="
                                                            route(
                                                                'brands.edit',
                                                                brand.id
                                                            )
                                                        "
                                                        class="text-primary hover:text-accent"
                                                        >Edit</InertiaLink
                                                    >
                                                    <button
                                                        @click.prevent="
                                                            deleteBrand(
                                                                brand.id
                                                            )
                                                        "
                                                        type="button"
                                                        class="text-red-600 hover:text-red-900 ml-4"
                                                    >
                                                        Delete
                                                    </button>
                                                </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="pagination-container py-4 flex justify-between px-2"
                                >
                                    <div class="pagination-info flex">
                                        Page
                                        <span
                                            class="font-semibold px-1 text-gray-900 report"
                                            >{{ brands.current_page }}</span
                                        >
                                        of
                                        <span
                                            class="font-semibold px-1 text-gray-900 report"
                                            >{{ brands.last_page }}</span
                                        >
                                        <div>
                                            <p class="pagination-info">
                                                Showing
                                                <span
                                                    class="font-semibold text-gray-900 report"
                                                    >{{ brands.from }}</span
                                                >
                                                to
                                                <span
                                                    class="font-semibold text-gray-900 report"
                                                    >{{ brands.to }}</span
                                                >
                                                of
                                                <span
                                                    class="font-semibold text-gray-900 report"
                                                    >{{ brands.total }}</span
                                                >
                                                entries
                                            </p>
                                        </div>
                                    </div>

                                    <div class="pagination-boxes">
                                        <template v-if="brands.prev_page_url">
                                            <InertiaLink
                                                :key="'prev'"
                                                :href="brands.prev_page_url"
                                                class="pagination-box"
                                            >
                                                <span>Previous</span>
                                            </InertiaLink>
                                        </template>

                                        <template
                                            v-for="page in brands.last_page"
                                        >
                                            <InertiaLink
                                                :key="`page-${page}`"
                                                v-if="
                                                    page === brands.current_page
                                                "
                                                :href="
                                                    route('brands.index', {
                                                        page: page,
                                                    })
                                                "
                                                class="pagination-box current"
                                            >
                                                <span>{{ page }}</span>
                                            </InertiaLink>
                                            <InertiaLink
                                                :key="`page-${page}`"
                                                v-else
                                                :href="
                                                    route('brands.index', {
                                                        page: page,
                                                    })
                                                "
                                                class="pagination-box"
                                            >
                                                <span>{{ page }}</span>
                                            </InertiaLink>
                                        </template>
                                        <template v-if="brands.next_page_url">
                                            <InertiaLink
                                                :key="'next'"
                                                :href="brands.next_page_url"
                                                class="pagination-box"
                                            >
                                                <span>Next</span>
                                            </InertiaLink>
                                        </template>
                                    </div>
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
    brands: Array,
    role: Object,
});

const deleteBrand = (id) => {
    // if (confirm("Are you sure you want to delete this brand?")) {
    //     return Inertia.delete(route("brands.destroy", id));
    // }
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
            Inertia.delete(route("brands.destroy", id));
        }
    });
};
</script>
<style scoped>
.pagination-container {
    align-items: center;
}

.pagination-boxes {
    margin-top: 0px;
    display: flex;
    gap: 8px;
}

.pagination-box {
    display: inline-block;
    padding: 10px 16px;
    background-color: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    text-decoration: none;
    color: #4a4a4a;
    transition: background-color 0.3s, color 0.3s;
}

.pagination-box:hover {
    background-color: #e5e7eb;
    color: #333;
}

.current {
    background-color: #6d4c41;
    color: white;
}

.pagination-info {
    margin-top: 0px;
    color: #6b7280;
    font-size: 16px;
    padding-left: 4px;
}
</style>
