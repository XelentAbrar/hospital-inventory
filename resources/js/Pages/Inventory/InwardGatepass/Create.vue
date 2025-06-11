<template>
  <Head title="Create Inward Gatepass" />
  <AppLayout>
     <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ inwardGatepass?.id ? "Update" : "Create" }} Inward Gatepass
          </h1>
          <div class="text-gray-900">
            <form
              @submit.prevent="
                inwardGatepass?.id
                  ? inwardGatepass.put(route('inward-gatepass.update', { id: inwardGatepass.id }))
                  : inwardGatepass.post(route('inward-gatepass.store'), inwardGatepass)
              "
              enctype="multipart/form-data"
            >
              <!-- Profile section -->
              <div class="py-6 lg:pb-8">
                <div>
                  <h2 class="text-2xl font-medium leading-6 text-gray-900">
                    Inward Gatepass
                  </h2>
                  <!-- <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p> -->
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                    <div class="">
                      <label
                        for="date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Gatepass #
                      </label>
                      <input
                        type="text"
                        name="gatepass_no"
                        id="gatepass_no"
                        autocomplete="family-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="gatepass_no"
                        disabled
                      />
                    </div>
                    <div class="">
                      <label
                        for="date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Date
                        <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="date"
                        name="date"
                        id="date"
                        autocomplete="family-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="inwardGatepass.date"
                        :disabled="goodsReceiptNotesIssue"
                      />
                      <InputError
                        v-if="inwardGatepass?.errors?.date"
                        :message="inwardGatepass?.errors?.date"
                      />
                    </div>
                    <div class="">
                      <label
                        for="department"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Supplier</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedSupplier"
                          :options="supplierOptions"
                          @select="onSupplierSelect"
                          :disabled="goodsReceiptNotesIssue"
                          label="name"
                          track-by="id"
                          placeholder="Select a Supplier"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="inwardGatepass?.errors?.supplier_id"
                        :message="inwardGatepass?.errors?.supplier_id"
                      />
                    </div>
                    <div class="sm:col-span-2">
                      <label
                        for="remarks"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Remarks
                        <span class="text-red-500">*</span>
                      </label>
                      <textarea
                        rows="2"
                        name="remarks"
                        id="remarks"
                        autocomplete="family-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="inwardGatepass.remarks"
                      ></textarea>
                      <InputError
                        v-if="inwardGatepass?.errors?.remarks"
                        :message="inwardGatepass?.errors?.remarks"
                      />
                    </div>
                    <div class="" v-if="!goodsReceiptNotesIssue">
                      <label
                        for="purchase_order"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Purchase Order</label>
                      <div class="mt-1">
                        <multiselect
                          v-model="purchaseOrderSelected"
                          :options="purchaseOrderOptions"
                          @select="onPurchaseOrderSelect"
                          label="po_no"
                          track-by="id"
                          placeholder="Select a PO"
                        ></multiselect>
                      </div>
                    </div>
                    <!-- <div class="col-span-12 sm:col-span-4">
                      <label
                        for="demand"
                        class="block"
                        >&nbsp;</label>
                          <button type="button" class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" @click="demandOnPurchaseOrder"><svg data-slot="icon" fill="none" stroke-width="1.5" class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/></svg></button>
                    </div> -->
                  </div>
              </div>

              <!-- Education section -->
              <div class="divide-y divide-gray-200">
                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-6 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Inward Gatepass Detail</h3>
                  <InputError
                    v-if="inwardGatepass?.errors?.detail"
                    :message="inwardGatepass?.errors?.detail"
                  />
                  <div class="w-1/2 min-w-0 flex-1" v-if="!goodsReceiptNotesIssue">
                    <label for="card-expiration-date" class="sr-only"
                      >Product</label
                    >
                    <div class="mt-2">
                      <multiselect
                        :options="purchase_order_details"
                        @select="onPurchaseOrderDetailSelect($event)"
                        label="display_product_name"
                        track-by="id"
                        placeholder="Select a Product"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="inwardGatepass.errors?.[`product_id`]"
                        :message="
                          inwardGatepass.errors?.[`product_id`]
                        "
                      />
                  </div>
                  <!-- <div
                    v-for="(detail, index) in purchaseOrder.detail"
                    :key="index"
                  >
                    <div class="flex gap-4 -space-x-px">
                      <div class="w-1/2 min-w-0 flex-1">
                        <label for="card-expiration-date" class="sr-only"
                          >Demand</label
                        >
                        <div class="mt-2">
                          {{detail.demand_name}}
                        </div>
                        <InputError
                          v-if="purchaseOrder.errors?.[`detail.${index}.demand_id`]"
                          :message="
                            purchaseOrder.errors?.[`detail.${index}.demand_id`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1">
                        <label for="card-cvc" class="sr-only">Qty</label>
                        <input
                          @input="onQtySelect(index, $event.target.value)"
                          v-model="detail.qty"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Qty"
                        />
                        <InputError
                          v-if="purchaseOrder.errors?.[`detail.${index}.qty`]"
                          :message="
                            purchaseOrder.errors?.[`detail.${index}.qty`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1">
                        <label for="card-cvc" class="sr-only">Price</label>
                        <input
                          v-model="detail.price"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Price"
                        />
                        <InputError
                          v-if="purchaseOrder.errors?.[`detail.${index}.price`]"
                          :message="
                            purchaseOrder.errors?.[`detail.${index}.price`]
                          "
                        />
                      </div>
                      <div>
                        <button
                          type="button"
                          @click="removeDetail(index)"
                          class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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
                  </div> -->
                  <div class="mt-6 flow-root">
                  <div class="border rounded mb-2">
                        <div class="ring-1 ring-gray-200 sm:rounded-lg">
                          <table class="min-w-full border-separate border-spacing-0">
                            <thead>
                              <tr class="divide-x divide-gray-200">
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  Product
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  Expiry Date
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  Stock
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  Qty
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  Price
                                </th>
                                <th
                                  scope="col"
                                  class="sticky w-32 top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  Tax
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  Total
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 px-3 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter"
                                >
                                  <span class="sr-only">Edit</span>
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr
                                class="divide-x divide-gray-200"
                                v-for="(detail, index) in inwardGatepass.detail"
                                :key="index"
                                :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                              >
                                <td
                                  class="border-b border-gray-200 py-4 px-3 text-sm font-medium text-gray-900"
                                >
                                  {{ detail?.purchase_order_detail?.display_product_name }}
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <input
                                    v-model="detail.expiry_date"
                                    type="date"
                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    placeholder="expiry_date"
                                  />
                                  <InputError
                                    v-if="inwardGatepass.errors?.[`detail.${index}.expiry_date`]"
                                    :message="
                                      inwardGatepass.errors?.[`detail.${index}.expiry_date`]
                                    "
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <input
                                    :value="detail?.purchase_order_detail?.available_qty"
                                    type="text" step="0.01"
                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    disabled
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                <div class="mt-1 relative rounded-md shadow-sm">
                                  <!-- <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-900 sm:text-sm">$</span>
                                  </div>  -->
                                  <input
                                    @input="onQtySelect(index, $event.target.value)"
                                    v-model="detail.qty"
                                    type="text" step="0.01"
                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    placeholder="Qty"
                                    :disabled="goodsReceiptNotesIssue"
                                  />

                                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span id="parts-currency" class="text-gray-900 sm:text-sm">
                                      {{detail?.purchase_order_detail?.product?.uom?.abrv}}
                                    </span>
                                  </div>
                                </div>
                                  <InputError
                                    v-if="inwardGatepass.errors?.[`detail.${index}.qty`]"
                                    :message="
                                      inwardGatepass.errors?.[`detail.${index}.qty`]
                                    "
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <input
                                    :value="detail.price"
                                    type="number"
                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    placeholder="Price"
                                    disabled
                                  />
                                  <InputError
                                    v-if="inwardGatepass.errors?.[`detail.${index}.price`]"
                                    :message="
                                      inwardGatepass.errors?.[`detail.${index}.price`]
                                    "
                                  />
                                </td>

                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                    <div class="">
                                      {{detail?.tax?.name}}
                                      <!-- <multiselect
                                        v-model="detail.tax"
                                        :options="taxes"
                                        @select="onTaxSelect(index, $event)"
                                        label="name"
                                        track-by="id"
                                        placeholder="Select Tax"
                                      ></multiselect> -->
                                    </div>
                                    <InputError
                                      v-if="
                                        inwardGatepass.errors?.[`detail.${index}.tax_id`]
                                      "
                                      :message="
                                        inwardGatepass.errors?.[`detail.${index}.tax_id`]
                                      "
                                    />
                                </td>

                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <input
                                  :value="(parseFloat(detail.price) * parseFloat(detail.qty)) + (parseFloat(detail.price) * parseFloat(detail.qty) * (parseFloat(detail.tax ? detail.tax.rate : 0) / 100))"

                                    type="text"
                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    placeholder="Price"
                                    disabled
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <button
                                    type="button"
                                    @click="removeDetail(index)"
                                    class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    v-if="!goodsReceiptNotesIssue"
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
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  <!-- <button
                    type="button"
                    @click="addDetail"
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
                  </button> -->
                </div>
              </div>
              <div class="flex items-center justify-end gap-x-6 py-4">
                  <InertiaLink
                    :href="route('inward-gatepass.index')"
                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="inwardGatepass.processing"
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
import { ref, watchEffect, computed, onMounted } from "vue";
import Multiselect from "vue-multiselect";
import axios from "axios";


const props = defineProps({
  inwardGatepass: { type: Object, default: null },
  purchase_orders: { type: Array, required: true, default: () => [] },
  suppliers: { type: Array, required: true, default: () => [] },
  taxes: { type: Array, required: true, default: () => [] },
  purchase_order_details: { type: Array, required: true, default: () => [] },
  gatepass_no: { type: Array, required: true, default: () => [] },
  goodsReceiptNotesIssue: { type: Array, required: true, default: () => 0 },
});

const inwardGatepass = useForm({
  id: props?.inwardGatepass?.id || null,
  supplier_id: props?.inwardGatepass?.supplier_id || null,
  date: props?.inwardGatepass?.date || new Date().toISOString().split('T')[0],
  remarks: props?.inwardGatepass?.remarks || null,
  detail: props?.inwardGatepass?.detail || [],
  _method: props?.inwardGatepass?.id ? "put" : "post",
});

const purchaseOrderSelected = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");

const purchase_orders = ref(props.purchase_orders || []);
const gatepass_no = ref(props.gatepass_no || []);
const goodsReceiptNotesIssue = ref(props.goodsReceiptNotesIssue || 0);
const purchase_order_details = ref(props.purchase_order_details || []);
const suppliers = ref(props.suppliers || []);
const taxes = ref(props.taxes || []);


const purchaseOrderOptions = computed(() => {
  return purchase_orders.value && purchase_orders.value.length ? purchase_orders.value : [];
});


const selectedSupplier = ref(null);
if (props?.inwardGatepass) {
  const supplier = props.suppliers.find(
    (c) => c.id === props.inwardGatepass.supplier_id
  );
  if (supplier) {
    selectedSupplier.value = supplier;
  }
}

const supplierOptions = computed(() => {
  return suppliers.value && suppliers.value.length ? suppliers.value : [];
});



const onTaxSelect = async (index, selectedTax) => {
    inwardGatepass.detail[index].tax_id = selectedTax.id;
    inwardGatepass.detail[index].tax = selectedTax;
}

const onSupplierSelect = async (selectedSupplier) => {
  inwardGatepass.supplier_id = selectedSupplier.id;
};

const onInwardGatepassSelect = (selectedInwardGatepass) => {
  inwardGatepass.reporting_to = selectedInwardGatepass.id;
};

const onPurchaseOrderSelect = async (selectedPurchaseOrder) => {
  purchaseOrderSelected.value = selectedPurchaseOrder;
  purchaseOrderOnInwardGatepass();
};

const purchaseOrderOnInwardGatepass = async () => {
  purchaseOrderSelected.value.detail.map(res => {
    if(res.available_qty != 0 && res.available_qty != '0' && res.available_qty != '' && res.available_qty != null){
      let checkExists = inwardGatepass.detail.filter(res1 => {
        return res1.purchase_order_detail_id == res.id;
      })
      if(checkExists.length == 0){
        inwardGatepass.detail.push({
          product_id: res.product_id,
          purchase_order_detail_id: res.id,
          qty: res.available_qty,
          price: res.price,
          expiry_date: res.expiry_date,
          tax_id: res.tax_id,
          tax: res.tax,
          purchase_order_detail: res,
          purchase_order_id: purchaseOrderSelected.value.id,
          purchase_order_name: purchaseOrderSelected.value.name
        });
      }
    }
  });
  purchaseOrderSelected.value = [];
};

const onPurchaseOrderDetailSelect = async (selectedProduct) => {
  let productAvailabilityCheck = true;
  console.log(selectedProduct, inwardGatepass.detail);
  inwardGatepass.detail.map(res => {
    if(selectedProduct.id == res.purchase_order_detail_id){
      productAvailabilityCheck = false;
    }
  });
  if(productAvailabilityCheck){
    inwardGatepass.detail.push({
      purchase_order_detail_id: selectedProduct.id,
      product_id: selectedProduct.product_id,
      purchase_order_detail: selectedProduct,
      qty: selectedProduct.qty,
      expiry_date: selectedProduct.expiry_date,
      price: selectedProduct.price,
      tax_id: selectedProduct.tax_id,
      tax: selectedProduct.tax,
    });
    inwardGatepass.errors[`product_id`] = '';
  }
  else{
    inwardGatepass.errors[`product_id`] = 'product already selected!';
  }
};

const onQtySelect = async (index, qty) => {
  if(!inwardGatepass.detail[index]?.purchase_order_detail){
    inwardGatepass.errors[`detail.${index}.product_id`] = 'please fill this!';
    inwardGatepass.detail[index].qty = 0;
  }
  else if(parseFloat(inwardGatepass.detail[index]?.purchase_order_detail?.available_qty) < parseFloat(qty)){
    inwardGatepass.errors[`detail.${index}.qty`] = 'qty must not be greater than '+inwardGatepass.detail[index]?.purchase_order_detail?.available_qty;
    console.log(inwardGatepass.detail[index]?.purchase_order_detail?.available_qty)
    inwardGatepass.detail[index].qty = inwardGatepass.detail[index]?.purchase_order_detail?.available_qty;
  }
  else{
    inwardGatepass.detail[index].qty = qty;
    inwardGatepass.errors[`detail.${index}.qty`] = '';
    inwardGatepass.errors[`detail.${index}.product_id`] = '';
  }
};

const productCustomLabel = (product) => {
  return `${product.name}`;
};

// const addDetail = () => {
//   inwardGatepass.detail.push({
//     purchase_order_detail_id: "",
//     product_id: "",
//     qty: "0",
//     price: "0",
//     tax_id: "",
//   });
// };

const removeDetail = (index) => {
  inwardGatepass.detail.splice(index, 1);
};

</script>

<script>
import { defineComponent } from "vue";
import InputError from "../../Components/InputError.vue";
export default defineComponent({
  components: {
    Multiselect,
    InputError,
  },
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
