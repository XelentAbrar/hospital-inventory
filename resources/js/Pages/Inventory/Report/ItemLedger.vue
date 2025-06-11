<template>

  <Head title="Item Ledger" />
  <AppLayout title="Item Ledger">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <h1 class="font-semibold text-3xl text-primary">Item Ledger</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <form @submit.prevent="report.get(route('inventory.item-ledger'), report)">
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                    <div>
                    <label for="from_date" class="block text-sm md:text-base font-medium text-gray-900">Search</label>
                      <multiselect
                        v-model="productSelected"
                        :options="all_products"
                        @update:modelValue="onProductSelect($event)"
                        label="name"
                        track-by="id"
                        placeholder="Select a Product"
                      ></multiselect>
                  </div>
                  <div>
                    <label for="from_date" class="block text-sm md:text-base font-medium text-gray-900">From
                      Date</label>
                    <div class="mt-2">
                      <input id="from_date" name="from_date" type="text" ref="from_date" autocomplete="from_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        step="0.01" v-model="report.from_date" />
                    </div>
                  </div>
                  <div>
                    <label for="to_date" class="block text-sm md:text-base font-medium text-gray-900">To Date</label>
                    <div class="mt-2">
                      <input id="to_date" name="to_date" type="text" ref="to_date" autocomplete="to_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        step="0.01" v-model="report.to_date" />
                    </div>
                  </div>
                  <div class="mt-2">
                    <label for="to_date" class="block text-sm md:text-base font-medium text-gray-900">&nbsp;</label>
                    <button type="submit"
                      class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                      Search
                    </button>
                    <button
                      class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2"
                      @click="print()" v-if="reports.length > 0">
                      Print
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="mt-6 flow-root" id="printData">
            <div class="bg-white overflow-hidden a4-size relative mx-auto shadow-sm rounded-lg">
              <div
                style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
                <div class="print-only">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center relative space-x-4 mb-4 ml-4 pb-2 border-b-4 border-[#FFC470] w-3/5">
                      <img class="h-auto w-14" src="/build/assets/logo-20b0503a.png" alt="" />
                      <h1 class="doctor-info text-2xl font-bold">CHINIOT GENERAL HOSPITAL</h1>
                      <div class="border border-[#8B322C] w-1/2 h-0.5 absolute right-0 -bottom-[3px]"></div>
                    </div>
                    <div class="space-y-3 ml-4 w-2/5">
                      <div class="flex items-center space-x-3">
                        <div class="border border-[#FFC470] p-[3px] mt-1 rounded-full">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-3.5 h-3.5 text-[#FFC470]">
                            <path fill-rule="evenodd"
                              d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                              clip-rule="evenodd" />
                          </svg>
                        </div>
                        <p style="font-size:14px;">Ph: 041-8848060 | 8787231</p>
                      </div>
                      <div class="flex items-start space-x-3">
                        <div class="border border-[#FFC470] p-[3px] mt-1 rounded-full">
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-3.5 h-3.5 text-[#FFC470]">
                            <path fill-rule="evenodd"
                              d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                              clip-rule="evenodd" />
                          </svg>
                        </div>
                        <p style="font-size:14px;">Nawaz Town Sargodha Road, Faisalabad</p>
                      </div>
                    </div>
                  </div>
                  <div class="border-dashed border-2 border-gray-400 rounded px-4 my-3">
                    <div
                      style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 5px auto;">
                      <div style="width: 50%;">
                        <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">
                            User</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                            :</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 14px;">
                            All</p>
                        </div>
                      </div>
                      <div style="width: 50%;display: flex;justify-content: start;">
                        <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">
                            Date & Time</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                            :</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;font-size: 14px;">
                            {{ new Date().toISOString().slice(0, 10) }} {{ new
                              Date().toTimeString().slice(0, 5) }}</p>
                        </div>
                      </div>
                    </div>
                    <div
                      style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 5px auto">
                      <div style="width: 50%;">
                        <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">
                            From</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                            :</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;font-size: 14px;">
                            {{ new Date(report?.from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                          </p>
                        </div>
                      </div>
                      <div style="width: 50%;display: flex;justify-content: start;">
                        <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">
                            To</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">
                            :</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 14px;">
                            {{ new Date(report?.to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <p style="font-size: 22px;font-weight: 600;text-align: center;margin: 10px 0;">
                      Item Ledger</p>
                  </div>
                </div>
                <div class="ring-1 mb-6 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                  <h2 class="px-4 py-2 uppercase font-bold text-2xl">Product Stock </h2>
                  <div class="grid lg:grid-cols-4 gap-4 px-4 py-4">
                    <div class="font-bold text-gray-700">
                      Total Opening Quantity
                    </div>
                    <div class="text-gray-900">
                      {{ totalReport.total_opening_qty }}
                    </div>
                    <div class="font-bold text-gray-700">
                      Total Inward Quantity
                    </div>
                    <div class="text-gray-900">
                      {{ totalReport.total_purchase_qty }}
                    </div>
                    <div class="font-bold text-gray-700">
                      Total Inward Price
                    </div>
                    <div class="text-gray-900">
                      {{ totalReport.total_purchase_price }}
                    </div>
                    <div class="font-bold text-gray-700">
                      Total Material Issue Quantity
                    </div>
                    <div class="text-gray-900">
                      {{ totalReport.total_sale_qty }}
                    </div>
                    <div class="font-bold text-gray-700">
                      Total MaterialReturn Quantity
                    </div>
                    <div class="text-gray-900">
                      {{ totalReport.total_return_qty }}
                    </div>
                    <div class="font-bold text-gray-700">
                      Current Stock
                    </div>
                    <div class="text-gray-900">
                      {{ formatNumber(totalReport.current_qty) }}
                    </div>

                  </div>

                </div>
                <div class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                  <h2 class="uppercase font-bold text-2xl px-2 py-2">Inward Report</h2>
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-200">
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Sr#</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Date</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Supplier Name</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Qty</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Tax</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Rate</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template v-for="(report, reportIndex) in reports" :key="reportIndex">
                        <tr class="divide-x divide divide-gray-200"
                          v-for="(inward, inwardIndex) in report.inward_details" :key="inwardIndex">
                          <td class="border border-gray-100 text-center">{{ inward.index }}</td>
                          <td class="border border-gray-100 text-center">{{ inward.created_at }}</td>
                          <td class="border border-gray-100">{{ inward.supplier_name }}</td>
                          <td class="border border-gray-100 text-center">{{ formatNumber(inward.qty) }}</td>
                          <td class="border border-gray-100 text-center">{{ formatNumber(inward.tax_rate) }}</td>
                          <td class="border border-gray-100 text-center">{{ formatNumber(inward.price) }}</td>
                          <td class="border border-gray-100 text-center">
                            {{ formatNumber(parseFloat((inward.price * inward.qty) + ((inward.price * inward.qty) * (inward.tax_rate / 100))).toFixed(2)) }}
                          </td>
                        </tr>
                      </template>
                    </tbody>
                    <tfoot>
                      <tr class="divide-x divide-gray-200 bg-secondary text-black">
                        <td class="whitespace-nowrap relative text-center" colspan="1">Total</td>
                        <td class="whitespace-nowrap relative">Total Items: {{ reports.flatMap(report =>
                          report.inward_details).length }}</td>
                        <td class="whitespace-nowrap relative"></td>
                        <td class="whitespace-nowrap relative">{{ formatNumber(sum(reports.flatMap(report => report.inward_details),
                          'qty', 'report')) }}</td>
                        <td class="whitespace-nowrap relative">{{ formatNumber(sum(reports.flatMap(report => report.inward_details),
                          'tax_rate', 'report')) }}</td>
                        <td class="whitespace-nowrap relative">{{ formatNumber(sum(reports.flatMap(report => report.inward_details),
                          'price', 'report')) }}</td>
                        <td class="whitespace-nowrap relative">{{ formatNumber(sumtotal(reports.flatMap(report =>
                          report.inward_details), 'report', 'totalAmount')) }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="mt-8 ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                  <h2 class="uppercase font-bold text-2xl px-2 py-2">Material Issue Report</h2>
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-200">
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Sr#</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Date</th>

                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Department</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Qty</th>

                      </tr>
                    </thead>
                    <tbody>
                      <template v-for="(report, reportIndex) in reports" :key="reportIndex">
                        <tr class="divide-x divide divide-gray-200"
                          v-for="(issue, issueIndex) in report.issue_details" :key="issueIndex">
                          <td class="border border-gray-100 text-center">{{ issueIndex+1 }}</td>
                          <td class="border border-gray-100 text-center">{{ issue.created_at }}</td>
                          <td class="border border-gray-100 text-center">{{ issue.department }}</td>
                          <td class="border border-gray-100 text-center">{{ formatNumber(issue.qty) }}</td>

                        </tr>
                      </template>
                    </tbody>
                    <tfoot>
                      <tr class="divide-x divide-gray-200 bg-secondary text-black">
                        <td class="whitespace-nowrap relative text-center" colspan="1">Total</td>
                        <td class="whitespace-nowrap relative">Total Items: {{ reports.flatMap(report =>
                          report.issue_details).length }}</td>
                          <td></td>
                        <td class="whitespace-nowrap relative">{{ formatNumber(sum(reports.flatMap(report => report.issue_details),
                          'qty', 'report')) }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div><div class="mt-8 ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                  <h2 class="uppercase font-bold text-2xl px-2 py-2">Material Return Report</h2>
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-200">
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Sr#</th>
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Date</th>

                        <!-- <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Department</th> -->
                        <th scope="col"
                          class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-medium text-white border-b border-gray-200">
                          Qty</th>

                      </tr>
                    </thead>
                    <tbody>
                      <template v-for="(report, reportIndex) in reports" :key="reportIndex">
                        <tr class="divide-x divide divide-gray-200"
                          v-for="(return_detail, return_detailIndex) in report.return_details" :key="return_detailIndex">
                          <td class="border border-gray-100 text-center">{{ return_detailIndex + 1 }}</td>
                          <td class="border border-gray-100 text-center">{{ return_detail.created_at }}</td>
                          <!-- <td class="border border-gray-100">{{ return_detail.department }}</td> -->
                          <td class="border border-gray-100 text-center">{{ formatNumber(return_detail.qty) }}</td>

                        </tr>
                      </template>
                    </tbody>
                    <tfoot>
                      <tr class="divide-x divide-gray-200 bg-secondary text-black">
                        <td class="whitespace-nowrap relative text-center" >Total</td>
                        <td class="whitespace-nowrap relative">Total Items: {{ reports.flatMap(report =>
                          report.return_details).length }}</td>
                       <td class="whitespace-nowrap relative">{{ formatNumber(sum(reports.flatMap(report => report.return_details),
                          'qty', 'report')) }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="print-only" style="position:absolute;bottom: 0;width: 100%;">
                  <div class="text-black h-auto w-full flex items-center justify-end py-6 px-12 pt-14">
                    <div>
                      <h3 class="doctor-info">-----------------------</h3>
                      <h2 class="doctor-info text-lg text-center font-semibold">
                        Authorized By
                      </h2>
                    </div>
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
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const from_date = ref(null);
const to_date = ref(null);
const props = defineProps({
  reports: Object,
  items: Array,
  totalReport: Object,
  from_date: String,
  to_date: String,
  search: String,
  all_products: { type: Array, required: true, default: () => [] },
});

const report = useForm({
  from_date: props?.from_date || null,
  to_date: props?.to_date || null,
  search: props?.search || null,

});
onMounted(() => {

const flatpickrOptions = (defaultDate) => ({
    dateFormat: "d-m-Y",
    allowInput: true, // Allows manual typing
    clickOpens: true, // Ensures the picker still works
    defaultDate: defaultDate || new Date(), // Use provided default date or current date
});

flatpickr(to_date.value, flatpickrOptions(report.to_date));
flatpickr(from_date.value, flatpickrOptions(report.from_date));

});
const productSelected = ref(
  props?.all_products
    ? props.all_products.find((product) => product.id == props.search)
    : null
);
const onProductSelect = async (selectedProduct) => {
  report.search = selectedProduct.id
};
const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};
const sumtotal = (reports, type = null, check = null) => {
  let total = 0;
  reports.forEach(res => {
    let totalAmount = (res.price || 0) * (res.qty || 0) + ((res.price || 0) * (res.qty || 0) * (res.tax_rate || 0) / 100);
    if (type === 'report' && check === 'totalAmount') {
      total += totalAmount;
    } else if (type === 'report' && res[check] !== null && res[check] !== undefined && !isNaN(res[check])) {
      total += parseFloat(res[check]);
    }
  });
  return isNaN(total) ? '0.00' : total.toFixed(2);
}

const sum = (reports, val, type = null, check = null) => {
  let total = 0;

  reports.forEach(res => {
    if (type === 'report') {
      if (res[val] !== null && res[val] !== undefined && !isNaN(res[val])) {
        total += parseFloat(res[val]);
      }
    } else {
      if (check && res[check] !== null && res[check] !== undefined && !isNaN(res[val])) {
        total += parseFloat(res[val]);
      }
    }
  });

  return isNaN(total) ? 0 : total;
}



const print = () => {
  var header_str = '<html><head><title>' + document.title + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById('printData').innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();

  return false;
}


</script>
<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect,
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
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

table th {
  padding: 14px 16px;
  font-size: 18px;
}

table tfoot td {
  padding: 14px 16px;
  font-size: 18px !important;
}

table td {
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
  font-size: 16px;
  font-weight: 600;
  text-align: center;
}

.print-only {
  display: none;
}

/* CSS to show the element only when printing */
@media print {
  @page {
    size: A4;
    margin: 0;
  }

  body {
    margin: 0;
  }

  .a4-size {
    width: 210mm;
    height: 297mm;
  }

  .print-only {
    display: block;
  }

  .no-print {
    display: none;
  }

  table th {
    padding: 4px;
    font-size: 12px !important;
    line-height: 10px !important;
  }

  table td {
    padding: 4px 8px;
    font-size: 10px !important;
    line-height: 10px !important;
  }

  table tfoot td {
    padding: 6px;
    font-size: 12px !important;
    line-height: 14px !important;
  }
}
</style>
