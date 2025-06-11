<template>
  <Head title="Create Demand" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ demand?.id ? "Update" : "Create" }} Demand
          </h1>
          <div class=" text-gray-900">
            <form
              @submit.prevent="
                demand?.id
                  ? demand.put(route('demands.update', { id: demand.id }))
                  : demand.post(route('demands.store'), demand)
              "
              enctype="multipart/form-data"
            >
              <!-- Profile section -->
              <div class="py-6 lg:pb-8">
                <div>
                  <h2 class="text-2xl font-medium leading-6 text-gray-900">
                    Demand
                  </h2>
                  <!-- <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p> -->
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                    <div>
                      <label
                        for="demand_no"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Demand #
                        <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        name="demand_no"
                        id="demand_no"
                        disabled
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="demand_no"
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
                        v-model="demand.date"
                      />
                      <InputError
                        v-if="demand?.errors?.date"
                        :message="demand?.errors?.date"
                      />
                    </div>
                    <div>
                      <label
                        for="department"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Department</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedDepartment"
                          :options="departmentOptions"
                          @select="onDepartmentSelect"
                          label="name"
                          track-by="id"
                          placeholder="Select a Department"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="demand?.errors?.department_id"
                        :message="demand?.errors?.department_id"
                      />
                    </div>
                    <div class="sm:col-span-3">
                      <label
                        for="remarks"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Remarks
                        <!-- <span class="text-red-500">*</span> -->
                      </label>
                      <textarea
                        rows="2"
                        name="remarks"
                        id="remarks"
                        autocomplete="family-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="demand.remarks"
                      ></textarea>
                      <InputError
                        v-if="demand?.errors?.remarks"
                        :message="demand?.errors?.remarks"
                      />
                    </div>
                </div>
              </div>

              <!-- Education section -->
              <div class="divide-y divide-gray-200">
                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-6 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Detail</h3>
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b" >
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="product" class="text-center font-medium">Product</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="qty" class="text-center font-medium">Qty</label>
                      </div>
                      <div class="flex items-center p-1 w-[49px]"></div>
                    </div>
                    <InputError
                      v-if="demand?.errors?.detail"
                      :message="demand?.errors?.detail"
                    />
                    <div
                      v-for="(detail, index) in demand.detail"
                      :key="index"
                    >
                      <div class="flex divide-x border-b">
                        <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                          <div class="w-full">
                          <label for="card-expiration-date" class="sr-only"
                            >Product</label
                          >
                          <multiselect
                            v-model="detail.product"
                            :options="products"
                            @select="onProductSelect(index, $event)"
                            label="name"
                            track-by="id"
                            placeholder="Select a Product"
                          ></multiselect>
                          <InputError
                          v-if="
                            demand.errors?.[`detail.${index}.product_id`]
                            "
                          :message="
                            demand.errors?.[`detail.${index}.product_id`]
                            "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">Qty</label>
                        <input
                          v-model="detail.qty"
                          type="number" step="0.01"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          placeholder="Qty"
                        />
                        <!-- <InputError
                          v-if="demand.errors?.[`detail.${index}.qty`]"
                          :message="
                            demand.errors?.[`detail.${index}.qty`]
                          "
                        /> -->
                        <InputError
                        v-if="demand.errors?.[`detail.${index}.qty`]"
                        message="The qty field is required"
                        />
                      </div>
                      </div>
                      <div class="flex items-center p-1">
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
                      </div>
                    </div>
                  </div>
                </div>
                  <button
                    type="button"
                    @click="addDetail"
                    class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
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
                <div class="flex items-center justify-end gap-4 mt-6 py-4">
                  <InertiaLink
                    :href="route('demands.index')"
                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="demand.processing"
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

  <!--Print-->



</template>


<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import Multiselect from "vue-multiselect";
import axios from "axios";


const props = defineProps({
  demand: { type: Object, default: null },
  demands: { type: Object, required: true },
  departments: { type: Array, required: true, default: () => [] },
  demandIdUseInPo: { type: Array, required: true, default: () => [] },
  products: { type: Array, required: true, default: () => [] },
  demand_no: { type: Array, required: true, default: () => [] },
});

const demand = useForm({
  id: props?.demand?.id || null,
  date: props?.demand?.date || new Date().toISOString().split('T')[0],
  department_id: props?.demand?.department_id || null,
  remarks: props?.demand?.remarks || null,
  detail: props?.demand?.detail || [
    {
      product_id: "",
      qty: "",
    },
  ],
  _method: props?.demand?.id ? "put" : "post",
});

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");

const demand_no = ref(props.demand_no || []);
const departments = ref(props.departments || []);
const demandIdUseInPo = ref(props.demandIdUseInPo || []);
const products = ref(props.products || []);

const selectedDepartment = ref(null);
if (props?.demand) {
  const department = props.departments.find(
    (c) => c.id === props.demand.department_id
  );
  if (department) {
    selectedDepartment.value = department;
  }
}

const departmentOptions = computed(() => {
  return departments.value && departments.value.length ? departments.value : [];
});



// reporting to
const demands = ref(props.demands);


const selectedReportingTo = ref(null);
if (props?.demand?.reporting_to) {
  selectedReportingTo.value = demands.value.find(
    (e) => e.id === props.demand.reporting_to
  );
}

const onDemandSelect = (selectedDemand) => {
  demand.reporting_to = selectedDemand.id;
};

const onDepartmentSelect = async (selectedDepartment) => {
  demand.department_id = selectedDepartment.id;
};


const onProductSelect = async (index, selectedProduct) => {
  let productAvailabilityCheck = true;
  demand.detail.map(res => {
    if(selectedProduct.id == res.product_id){
      productAvailabilityCheck = false;
    }
  });
  if(productAvailabilityCheck){
    demand.detail[index].product_id = selectedProduct.id;
    demand.detail[index].product = selectedProduct;
    demand.errors[`detail.${index}.product_id`] = '';
  }
  else{
    demand.errors[`detail.${index}.product_id`] = 'product already selected!';
    demand.detail[index].product_id = null;
    demand.detail[index].product = null;
  }
};

const productCustomLabel = (product) => {
  return `${product.name}`;
};

//education
const addDetail = () => {
  demand.detail.push({
    product_id: "",
    qty: "",
  });
};

const removeDetail = (index, id) => {
  if(demandIdUseInPo.value && id && demandIdUseInPo.value.indexOf(id) > -1){
    if (confirm("This product has already Purchase Order, please first delete Purchase Order!")) {
    }
    return;
  }
  demand.detail.splice(index, 1);
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


/*--Print css--*/
@media print {
          @page { size: A4; margin: 0; }
          body { margin: 0; }
      }
        .a4-size {
            width: 210mm;
            height: 297mm;
        }
.printDiv {
	display: none;
}
@media print {
	.no-printDiv  {
		display: none;
	}
	.printDiv  {
		display: block;
	}
}

td {
              text-align: left;
              padding: 10px 8px;
              font-size: 14px;
          }

          th {
              font-size: 14px;
              font-weight: 600;
              color: black;
              padding: 4px 8px;
              text-align: center;
              height: 24px;
          }

          table {
              width: 100%;
              height: auto;
              border-collapse: collapse;
          }

          table th{
          padding: 14px 16px;
          font-size:18px;
        }
        table td{
          padding: 16px;
          font-size: 16px;
        }

          .total_row {
              border-left: 1px solid;
              border-right: 1px solid;
          }

          .total_row td {
              border: none;
              font-weight: 600;
          }

          table tfoot tr {
              border: 1px solid;
          }

          table tfoot td {
              border: none;
              font-size: 14px;
              font-weight: 600;
              text-align: left;
          }
          .print-only {
              display: none;
          }

          /* CSS to show the element only when printing */
          @media print {
              table th{
          padding: 1px;
          font-size: 12px !important;
          line-height: 12px !important;
        }
        table td{
          padding: 1px 4px;
          font-size: 10px !important;
          line-height: 12px !important;
        }
          }
</style>
