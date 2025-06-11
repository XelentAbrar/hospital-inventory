<template>
  <Head title="Create Goods Receipt Note" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ goodsReceiptNote?.id ? "Update" : "Create" }} Goods Receipt Note
          </h1>
          <div class="text-gray-900">
            <form
              @submit.prevent="
                goodsReceiptNote?.id
                  ? goodsReceiptNote.put(route('goods-receipt-notes.update', { id: goodsReceiptNote.id }))
                  : goodsReceiptNote.post(route('goods-receipt-notes.store'), goodsReceiptNote)
              "
              enctype="multipart/form-data"
            >
              <!-- Profile section -->
              <div class="pt-6">
                <div>
                  <h2 class="text-2xl font-medium leading-6 text-gray-900">
                    Goods Receipt Note
                  </h2>
                  <!-- <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p> -->
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                    <div>
                      <label
                        for="goods_receipt_no"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Good Receipt #
                        <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        name="goods_receipt_no"
                        id="goods_receipt_no"
                        disabled
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="goods_receipt_no"
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
                        v-model="goodsReceiptNote.date"
                      />
                      <InputError
                        v-if="goodsReceiptNote?.errors?.date"
                        :message="goodsReceiptNote?.errors?.date"
                      />
                    </div>
                    <div>
                      <label
                        for="inward_gatepass_id"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Inward Gatepass</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedInwardGatepass"
                          :options="inwardGatepassOptions"
                          @select="onInwardGatepassSelect"
                          label="gatepass_no"
                          track-by="id"
                          placeholder="Select a Inward Gatepass"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="goodsReceiptNote?.errors?.inward_gatepass_id"
                        :message="goodsReceiptNote?.errors?.inward_gatepass_id"
                      />
                    </div>
                    <div class="sm:col-span-3">
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
                        v-model="goodsReceiptNote.remarks"
                      ></textarea>
                      <InputError
                        v-if="goodsReceiptNote?.errors?.remarks"
                        :message="goodsReceiptNote?.errors?.remarks"
                      />
                    </div>
                </div>
              </div>

              <div class="flex items-center justify-end gap-x-4 py-4">
                  <InertiaLink
                    :href="route('goods-receipt-notes.index')"
                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="goodsReceiptNote.processing"
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
  goodsReceiptNote: { type: Object, default: null },
  inwardGatepass: { type: Array, required: true, default: () => [] },
  goods_receipt_no: { type: Array, required: true, default: () => [] },
});

const goodsReceiptNote = useForm({
  id: props?.goodsReceiptNote?.id || null,
  date: props?.goodsReceiptNote?.date || new Date().toISOString().split('T')[0],
  inward_gatepass_id: props?.goodsReceiptNote?.inward_gatepass_id || null,
  remarks: props?.goodsReceiptNote?.remarks || null,
  _method: props?.goodsReceiptNote?.id ? "put" : "post",
});

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");

const goods_receipt_no = ref(props.goods_receipt_no || []);
const inwardGatepass = ref(props.inwardGatepass || []);

const selectedInwardGatepass = ref(null);
if (props?.goodsReceiptNote) {
  const inwardGatepass = props.inwardGatepass.find(
    (c) => c.id === props.goodsReceiptNote.inward_gatepass_id
  );
  if (inwardGatepass) {
    selectedInwardGatepass.value = inwardGatepass;
  }
}

const inwardGatepassOptions = computed(() => {
  return inwardGatepass.value && inwardGatepass.value.length ? inwardGatepass.value : [];
});

const onInwardGatepassSelect = async (selectedInwardGatepass) => {
  goodsReceiptNote.inward_gatepass_id = selectedInwardGatepass.id;
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