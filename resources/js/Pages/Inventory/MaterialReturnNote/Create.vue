<template>
  <Head title="Create Material Return Note" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ materialReturnNote?.id ? "Update" : "Create" }} Material Return Note
          </h1>
          <div class="text-gray-900">
            <form
              @submit.prevent="
                materialReturnNote?.id
                  ? materialReturnNote.put(route('material-return-notes.update', { id: materialReturnNote.id }))
                  : materialReturnNote.post(route('material-return-notes.store'), materialReturnNote)
              "
              enctype="multipart/form-data"
            >
              <!-- Profile section -->
              <div class="pt-6">
                <div>
                  <h2 class="text-2xl font-medium leading-6 text-gray-900">
                    Material Return Note
                  </h2>
                  <!-- <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p> -->
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 py-6">
                    <div>
                      <label
                        for="mrn_no"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Material Return Note #
                        <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        name="mrn_no"
                        id="mrn_no"
                        disabled
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="mrn_no"
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
                        v-model="materialReturnNote.date"
                      />
                      <InputError
                        v-if="materialReturnNote?.errors?.date"
                        :message="materialReturnNote?.errors?.date"
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
                        v-model="materialReturnNote.remarks"
                      ></textarea>
                      <InputError
                        v-if="materialReturnNote?.errors?.remarks"
                        :message="materialReturnNote?.errors?.remarks"
                      />
                    </div>
                </div>
              </div>

              <!-- Education section -->
               <div class="divide-y divide-gray-200 pt-4 pb-4">
                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-6 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Detail</h3>
                  <InputError
                    v-if="materialReturnNote?.errors?.detail"
                    :message="materialReturnNote?.errors?.detail"
                  />
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
                  <div
                    v-for="(detail, index) in materialReturnNote.detail"
                    :key="index"
                  >
                  <div class="flex divide-x border-b">
                        <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                          <div class="w-full">
                        <label for="card-expiration-date" class="sr-only"
                          >Product</label
                        >
                        <div class="mt-1">
                          <multiselect
                            v-model="detail.product"
                            :options="products"
                            @select="onProductSelect(index, $event)"
                            label="name"
                            track-by="id"
                            placeholder="Select a Product"
                          ></multiselect>
                        </div>
                        <InputError
                          v-if="
                            materialReturnNote.errors?.[`detail.${index}.product_id`]
                          "
                          :message="
                            materialReturnNote.errors?.[`detail.${index}.product_id`]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="qty" class="sr-only">Qty</label>
                        <input
                          @input="onQtySelect(index, $event.target.value)"
                          v-model="detail.qty"
                          type="number" step="0.01"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          placeholder="Qty"
                        />
                        <InputError
                          v-if="materialReturnNote.errors?.[`detail.${index}.qty`]"
                          :message="
                            materialReturnNote.errors?.[`detail.${index}.qty`]
                          "
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

              <div class="flex items-center justify-end gap-4 pb-4">
                  <InertiaLink
                    :href="route('material-return-notes.index')"
                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="materialReturnNote.processing"
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
  materialReturnNote: { type: Object, default: null },
  products: { type: Array, required: true, default: () => [] },
  mrn_no: { type: Array, required: true, default: () => [] },
});

const materialReturnNote = useForm({
  id: props?.materialReturnNote?.id || null,
  date: props?.materialReturnNote?.date || new Date().toISOString().split('T')[0],
  remarks: props?.materialReturnNote?.remarks || null,
  detail: props?.materialReturnNote?.detail || [
    {
      product_id: "",
      qty: "",
    },
  ],
  _method: props?.materialReturnNote?.id ? "put" : "post",
});

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");

const mrn_no = ref(props.mrn_no || []);
const products = ref(props.products || []);

const onProductSelect = async (index, selectedProduct) => {
  let productAvailabilityCheck = true;
  materialReturnNote.detail.map(res => {
    if(selectedProduct.id == res.product_id){
      productAvailabilityCheck = false;
    }
  });
  if(productAvailabilityCheck){
    materialReturnNote.detail[index].product_id = selectedProduct.id;
    materialReturnNote.detail[index].product = selectedProduct;
    materialReturnNote.errors[`detail.${index}.product_id`] = '';
  }
  else{
    materialReturnNote.errors[`detail.${index}.product_id`] = 'product already selected!';
    materialReturnNote.detail[index].product_id = null;
    materialReturnNote.detail[index].product = null;
  }
};


// const onQtySelect = async (index, qty) => {
//   if(!materialReturnNote.detail[index]?.product){
//     materialReturnNote.errors[`detail.${index}.product_id`] = 'please fill this!';
//     materialReturnNote.detail[index].qty = 0;
//   }
//   else if(parseFloat(materialReturnNote.detail[index]?.product?.stock?.current_stock) < parseFloat(qty)){
//     materialReturnNote.errors[`detail.${index}.qty`] = 'qty must not be greater than '+materialReturnNote.detail[index]?.product?.stock?.current_stock;
//     materialReturnNote.detail[index].qty = materialReturnNote.detail[index]?.product?.stock?.current_stock;
//   }
//   else{
//     materialReturnNote.detail[index].qty = qty;
//     materialReturnNote.errors[`detail.${index}.qty`] = '';
//     materialReturnNote.errors[`detail.${index}.product_id`] = '';
//   }
// };
const onQtySelect = async (index, qty) => {
  if (!materialReturnNote.detail[index]?.product) {
    materialReturnNote.errors[`detail.${index}.product_id`] = 'Please fill this!';
    this.$set(materialReturnNote.detail[index], 'qty', 0);
  }
  else if (parseFloat(materialReturnNote.detail[index]?.product?.stock?.current_stock) < parseFloat(qty)) {
    materialReturnNote.errors[`detail.${index}.qty`] = 'Qty must not be greater than ' + materialReturnNote.detail[index]?.product?.stock?.current_stock;
    this.$set(materialReturnNote.detail[index], 'qty', materialReturnNote.detail[index]?.product?.stock?.current_stock); // Ensures reactivity
  }
  else {
    this.$set(materialReturnNote.detail[index], 'qty', qty);
    materialReturnNote.errors[`detail.${index}.qty`] = '';
    materialReturnNote.errors[`detail.${index}.product_id`] = '';
  }
};

const productCustomLabel = (product) => {
  return `${product.name}`;
};

//education
const addDetail = () => {
  materialReturnNote.detail.push({
    product_id: "",
    qty: "",
  });
};

const removeDetail = (index, id) => {
  materialReturnNote.detail.splice(index, 1);
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
