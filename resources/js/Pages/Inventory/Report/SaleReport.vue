<template>
  <Head title="Sale Report" />
  <AppLayout title="Sale Report">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between no-print">
            <h1 class="font-semibold text-3xl text-primary">Sale Report</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <form @submit.prevent="report.get(route('inventory.sale-report'), report)">
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                  <div>
                    <label for="from_date" class="block text-sm md:text-base font-medium text-gray-900">
                      Search
                    </label>
                    <div class="mt-2">
                      <input
                        id="search"
                        name="search"
                        type="text"
                        placeholder="search with department "
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="report.search"
                      />
                    </div>
                  </div>
                  <div>
                    <label for="from_date" class="block text-sm md:text-base font-medium text-gray-900">
                      From Date
                    </label>
                    <div class="mt-2">
                      <input
                        id="from_date"
                        name="from_date"
                        type="text" ref="from_date"
                        autocomplete="from_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        step="0.01"
                        v-model="report.from_date"
                      />
                    </div>
                  </div>
                  <div>
                    <label for="to_date" class="block text-sm md:text-base font-medium text-gray-900">
                      To Date
                    </label>
                    <div class="mt-2">
                      <input
                        id="to_date"
                        name="to_date"
                        type="text" ref="to_date"
                        autocomplete="to_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        step="0.01"
                        v-model="report.to_date"
                      />
                    </div>
                  </div>
                  <div class="mt-2">
                    <label for="to_date" class="block text-sm md:text-base font-medium text-gray-900">&nbsp;</label>
                    <button type="submit" class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                      Search
                    </button>
                    <button class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports.length > 0" >
                      Print
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="mt-6 flow-root" id="printData">
           <div class="bg-white a4-size relative mx-auto shadow-sm rounded-lg">
            <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
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
                <div class="border-dashed border-2 border-gray-400 rounded px-4 mt-3">
                  <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 5px auto;">
                    <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                        <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">User</h4>
                        <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                        <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 14px;">All</p>
                      </div>
                    </div>
                    <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                        <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">Date & Time</h4>
                        <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                        <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;font-size: 14px;">{{ new Date().getDate() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getFullYear() }} {{new Date().toTimeString().slice(0, 5)}}</p>
                      </div>
                    </div>
                  </div>
                  <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 5px auto">
                    <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                        <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">From</h4>
                        <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                        <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;font-size: 14px;">                            {{ new Date(report?.from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
</p>
                      </div>
                    </div>
                    <div style="width: 50%;display: flex;justify-content: start;">
                        <div style="display: flex;align-items: center;">
                            <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">To</h4>
                            <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                            <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 14px;">                            {{ new Date(report?.to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
</p>
                        </div>
                    </div>
                  </div>
                </div>
                <div>
                  <p style="font-size: 22px;font-weight: 600;text-align: center;margin: 10px 0;">Sale Report</p>
                </div>
              </div>
              <div class="ring-1 ring-gray-300">
                <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto">
                  <thead>
                    <tr class="divide-x divide-gray-300">
                      <th scope="col" class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10">
                        Sr#
                      </th>
                      <th scope="col" class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10">
                        Date
                      </th>
                      <th scope="col" class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10">
                        Name
                      </th>
                      <th scope="col" class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10">
                        Department Name
                      </th>
                      <th scope="col" class="bg-primary whitespace-nowrap text-center border-b border-gray-300 font-bold text_white sticky top-0 z-10">
                        Qty
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      class="divide-x divide-gray-300"
                      v-for="(report, index) in reports"
                      :key="index"
                      :class="[(index % 2 === 0 ? 'bg-gray-50' : 'bg-white'),]"
                    >
                      <td class="whitespace-nowrap relative border-b border-gray-300 text-center">
                        {{ index+1 }}
                      </td>
                      <td class="whitespace-nowrap relative border-b border-gray-300 text-center">
                        <!-- {{ report?.material_issue_note?.date.split(' ')[0] }} -->
                        {{ new Date(report?.material_issue_note.date).toLocaleDateString('en-GB').replace(/\//g, '-') }}

                      </td>
                      <td class="whitespace-nowrap relative border-b border-gray-300">
                        {{ report?.product?.name }}
                      </td>
                      <td class="whitespace-nowrap relative border-b border-gray-300">
                        {{ report?.material_issue_note?.department?.name }}
                      </td>
                      <td class="whitespace-nowrap relative border-b border-gray-300 text-center">
                        {{ formatNumber(report?.qty) }}
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr class="divide-x divide-gray-300 bg-secondary text-black">
                          <td class="whitespace-nowrap relative text-center" colspan="2">
                            Total
                          </td>
                          <td class="whitespace-nowrap relative">
                           Total Sales : {{ reports.length }}
                          </td>

                          <td class="whitespace-nowrap relative">
                        </td>
                        <td class="whitespace-nowrap relative">
                            {{ formatNumber(sum(reports, 'qty', 'report')) }}
                          </td>
                        </tr>
                  </tfoot>
                </table>
              </div>
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
    from_date: String,
    to_date: String,
    search: String,
  });
  const formatNumber = (number) => {
        return new Intl.NumberFormat().format(number);
    };
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
  const sum = (reports, val, type = null, check = null) => {
    let total = 0;
    reports.forEach(res => {
     if (type === 'report' && res[val] !== null && res[val] !== undefined && !isNaN(res[val])) {
        total += parseFloat(res[val]);
      } else {
        if (check && res[check] !== null && res[check] !== undefined && !isNaN(res[val])) {
          total += parseFloat(res[val]);
        }
      }
    });
    return total;
  }


  const print = () => {
    var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
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
          table th, table tfoot td{
          padding: 12px 14px;
          font-size:16px;
        }
        table td{
          padding: 10px 12px;
          font-size: 14px;
        }
        @media only screen and (min-width: 1600px) {
          table th, table tfoot td{
            padding: 14px 16px;
            font-size:18px;
          }
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
        .text_white{
          color: #fff;
        }

          /* CSS to show the element only when printing */
          @media print {
            @page { size: A4; margin: 0; }
            body { margin: 0; }
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
            table tfoot td{
              padding: 6px;
              font-size: 12px !important;
              line-height: 14px !important;
            }
          .text_white{
            color: #000 !important;
          }
          }
      </style>
