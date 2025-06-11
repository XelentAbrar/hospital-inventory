<template>
  <Head title="Create Purchase Order" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ purchaseOrder?.id ? "Update" : "Create" }} Purchase Order
          </h1>
          <div class=" text-gray-900">
            <form
              @submit.prevent="
                purchaseOrder?.id
                  ? purchaseOrder.put(route('purchase-orders.update', { id: purchaseOrder.id }))
                  : purchaseOrder.post(route('purchase-orders.store'), purchaseOrder)
              "
               enctype="multipart/form-data"
            >
              <!-- Profile section -->
              <div class="py-6 lg:pb-8">
                <div>
                  <h2 class="text-2xl font-medium leading-6 text-gray-900">
                    Purchase Order
                  </h2>
                  <!-- <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p> -->
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                    <div>
                      <label
                        for="date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >PO #
                      </label>
                      <input
                        type="text"
                        name="po_no"
                        id="po_no"
                        autocomplete="family-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="po_no"
                        disabled
                      />
                    </div>
                    <div>
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
                        v-model="purchaseOrder.date"
                      />
                      <InputError
                        v-if="purchaseOrder?.errors?.date"
                        :message="purchaseOrder?.errors?.date"
                      />
                    </div>
                    <div>
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
                          label="name"
                          track-by="id"
                          placeholder="Select a Supplier"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="purchaseOrder?.errors?.supplier_id"
                        :message="purchaseOrder?.errors?.supplier_id"
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
                        v-model="purchaseOrder.remarks"
                      ></textarea>
                      <InputError
                        v-if="purchaseOrder?.errors?.remarks"
                        :message="purchaseOrder?.errors?.remarks"
                      />
                    </div>
                    <div>
                      <label
                        for="demand"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Demand</label>
                      <div class="mt-1">
                        <multiselect
                          v-model="demandSelected"
                          :options="demandOptions"
                          @select="onDemandSelect"
                          label="demand_no"
                          track-by="id"
                          placeholder="Select a Demand"
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
              <div class="divide-y divide-gray-200 pt-4 pb-4">
                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-6 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Purchase Order Detail</h3>
                  <InputError
                    v-if="purchaseOrder?.errors?.detail"
                    :message="purchaseOrder?.errors?.detail"
                  />
                  <div class="w-1/2 min-w-0 flex-1">
                    <label for="card-expiration-date" class="sr-only"
                      >Product</label
                    >
                    <div class="mt-1">
                      <multiselect
                        :options="demand_details"
                        @select="onDemandDetailSelect($event)"
                        label="display_product_name"
                        track-by="id"
                        placeholder="Select a Product"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="purchaseOrder.errors?.[`product_id`]"
                        :message="
                          purchaseOrder.errors?.[`product_id`]
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
                                  class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"
                                >
                                  Stock
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"
                                >
                                  Qty
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"
                                >
                                  Price
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"
                                >
                                  Tax
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 hidden border-b border-gray-300 bg-white bg-opacity-75 px-3 py-3.5 text-center text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:table-cell"
                                >
                                  Total
                                </th>
                                <th
                                  scope="col"
                                  class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter"
                                >
                                  <span class="sr-only">Edit</span>
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr
                              class="divide-x divide-gray-200"
                                v-for="(detail, index) in purchaseOrder.detail"
                                :key="index"
                                :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                              >
                                <td
                                  class="border-b border-gray-200 py-4 px-3 text-sm font-medium text-gray-900"
                                >
                                  {{ detail?.demand_detail?.display_product_name }}
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <input
                                    :value="detail?.demand_detail?.available_qty"
                                    type="text" step="0.01"
                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    disabled
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                <div class="mt-1 relative rounded-md shadow-sm">
                                  <input
                                    @input="onQtySelect(index, $event.target.value)"
                                    v-model="detail.qty"
                                    type="text" step="0.01"
                                    class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    placeholder="Qty"
                                  />

                                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span id="parts-currency" class="text-gray-900 sm:text-sm">
                                      {{detail?.demand_detail?.product?.uom?.abrv}}
                                    </span>
                                  </div>
                                </div>
                                  <InputError
                                    v-if="purchaseOrder.errors?.[`detail.${index}.qty`]"
                                    :message="
                                      purchaseOrder.errors?.[`detail.${index}.qty`]
                                    "
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <input
                                    v-model="detail.price"
                                    type="number" step="0.01"
                                    class="relative block rounded-md w-36 border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    placeholder="Price"
                                  />
                                  <InputError
                                    v-if="purchaseOrder.errors?.[`detail.${index}.price`]"
                                    :message="
                                      purchaseOrder.errors?.[`detail.${index}.price`]
                                    "
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                    <div class="w-36">
                                      <multiselect
                                        v-model="detail.tax"
                                        :options="taxes"
                                        @select="onTaxSelect(index, $event)"
                                        label="name"
                                        track-by="id"
                                        placeholder="Select Tax"
                                        class="h-8"
                                      ></multiselect>
                                    </div>
                                    <InputError
                                      v-if="
                                        purchaseOrder.errors?.[`detail.${index}.tax_id`]
                                      "
                                      :message="
                                        purchaseOrder.errors?.[`detail.${index}.tax_id`]
                                      "
                                    />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <input
                                  :value="parseFloat((parseFloat(detail.price) * parseFloat(detail.qty)) * (1 + parseFloat(detail.tax ? detail.tax.rate : 0) / 100)).toFixed(2)"                                     type="text"
                                    class="relative block rounded-md w-36 border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    placeholder="Price"
                                    disabled
                                  />
                                </td>
                                <td
                                  class="whitespace-nowrap border-b border-gray-200 p-2 text-sm text-gray-900"
                                >
                                  <button
                                    type="button"
                                    @click="removeDetail(index, detail.id)"
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
                                </td>
                              </tr>
                            </tbody>
                          </table>
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
              </div>
                <div class="flex items-center justify-end gap-x-6 py-4">
                  <InertiaLink
                    :href="route('purchase-orders.index')"
                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="purchaseOrder.processing"
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
  purchaseOrder: { type: Object, default: null },
  purchaseOrders: { type: Object, required: true },
  demands: { type: Array, required: true, default: () => [] },
  suppliers: { type: Array, required: true, default: () => [] },
  taxes: { type: Array, required: true, default: () => [] },
  demand_details: { type: Array, required: true, default: () => [] },
  po_no: { type: Array, required: true, default: () => [] },
  purchaseOrderIdUseInPo: { type: Array, required: true, default: () => [] },
});

const purchaseOrder = useForm({
  id: props?.purchaseOrder?.id || null,
  supplier_id: props?.purchaseOrder?.supplier_id || null,
  date: props?.purchaseOrder?.date || null,
  remarks: props?.purchaseOrder?.remarks || null,
  detail: props?.purchaseOrder?.detail || [],
  _method: props?.purchaseOrder?.id ? "put" : "post",
});

const demandSelected = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");

const purchaseOrderIdUseInPo = ref(props.purchaseOrderIdUseInPo || []);
const demands = ref(props.demands || []);
const po_no = ref(props.po_no || []);
const demand_details = ref(props.demand_details || []);
const suppliers = ref(props.suppliers || []);
const taxes = ref(props.taxes || []);


const demandOptions = computed(() => {
  return demands.value && demands.value.length ? demands.value : [];
});


const selectedSupplier = ref(null);
if (props?.purchaseOrder) {
  const supplier = props.suppliers.find(
    (c) => c.id === props.purchaseOrder.supplier_id
  );
  if (supplier) {
    selectedSupplier.value = supplier;
  }
}

const supplierOptions = computed(() => {
  return suppliers.value && suppliers.value.length ? suppliers.value : [];
});



// reporting to
const purchaseOrders = ref(props.purchaseOrders);


const selectedReportingTo = ref(null);
if (props?.purchaseOrder?.reporting_to) {
  selectedReportingTo.value = purchaseOrders.value.find(
    (e) => e.id === props.purchaseOrder.reporting_to
  );
}

const onTaxSelect = async (index, selectedTax) => {
    purchaseOrder.detail[index].tax_id = selectedTax.id;
    purchaseOrder.detail[index].tax = selectedTax;
}

const onSupplierSelect = async (selectedSupplier) => {
  purchaseOrder.supplier_id = selectedSupplier.id;
};

const onPurchaseOrderSelect = (selectedPurchaseOrder) => {
  purchaseOrder.reporting_to = selectedPurchaseOrder.id;
};

const onDemandSelect = async (selectedDemand) => {
  demandSelected.value = selectedDemand;
  demandOnPurchaseOrder();
};

const demandOnPurchaseOrder = async () => {
  demandSelected.value.detail.map(res => {
    if(res.available_qty != 0 && res.available_qty != '0' && res.available_qty != '' && res.available_qty != null){
      let checkExists = purchaseOrder.detail.filter(res1 => {
        return res1.demand_detail_id == res.id;
      })
      if(checkExists.length == 0){
        purchaseOrder.detail.push({
          product_id: res.product_id,
          demand_detail_id: res.id,
          qty: res.available_qty,
          price: "0",
          tax_id: "",
          demand_detail: res,
          demand_id: demandSelected.value.id,
          demand_name: demandSelected.value.name
        });
      }
    }
  });
  demandSelected.value = [];
};

const onDemandDetailSelect = async (selectedProduct) => {
  let productAvailabilityCheck = true;
  console.log(selectedProduct, purchaseOrder.detail);
  purchaseOrder.detail.map(res => {
    if(selectedProduct.id == res.demand_detail_id){
      productAvailabilityCheck = false;
    }
  });
  if(productAvailabilityCheck){
    purchaseOrder.detail.push({
      demand_detail_id: selectedProduct.id,
      product_id: selectedProduct.product_id,
      demand_detail: selectedProduct,
      qty: '0',
      price: '0',
    });
    purchaseOrder.errors[`product_id`] = '';
  }
  else{
    purchaseOrder.errors[`product_id`] = 'product already selected!';
  }
};

const onQtySelect = async (index, qty) => {
  if(!purchaseOrder.detail[index]?.demand_detail){
    purchaseOrder.errors[`detail.${index}.product_id`] = 'please fill this!';
    purchaseOrder.detail[index].qty = 0;
  }
  else if(parseFloat(purchaseOrder.detail[index]?.demand_detail?.available_qty) < parseFloat(qty)){
    purchaseOrder.errors[`detail.${index}.qty`] = 'qty must not be greater than '+purchaseOrder.detail[index]?.demand_detail?.available_qty;
    console.log(purchaseOrder.detail[index]?.demand_detail?.available_qty)
    purchaseOrder.detail[index].qty = purchaseOrder.detail[index]?.demand_detail?.available_qty;
  }
  else{
    purchaseOrder.detail[index].qty = qty;
    purchaseOrder.errors[`detail.${index}.qty`] = '';
    purchaseOrder.errors[`detail.${index}.product_id`] = '';
  }
};

const productCustomLabel = (product) => {
  return `${product.name}`;
};

// const addDetail = () => {
//   purchaseOrder.detail.push({
//     demand_detail_id: "",
//     product_id: "",
//     qty: "0",
//     price: "0",
//     tax_id: "",
//   });
// };

const removeDetail = (index, id) => {
  if(purchaseOrderIdUseInPo.value && id && purchaseOrderIdUseInPo.value.indexOf(id) > -1){
    if (confirm("This product has already Inward Gatepass, please first delete Inward Gatepass!")) {
    }
    return;
  }
  purchaseOrder.detail.splice(index, 1);
};

</script>

<script>
import { defineComponent } from "vue";
import InputError from "../../Components/InputError.vue";
import { parse } from "filepond";
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
