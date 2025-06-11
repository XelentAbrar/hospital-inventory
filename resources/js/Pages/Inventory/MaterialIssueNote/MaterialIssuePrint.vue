<template>
  <div class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
    <div class="bg-white overflow-hidden a4-size relative mx-auto shadow-sm rounded-lg">
      <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
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
                <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px; white-space: nowrap;">Issue Note #</h4>
                <h4 style="margin:0  10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 14px;">{{ materialIssueNote.min_no }}</p>
              </div>
            </div>
            <div style="width: 50%;display: flex;justify-content: start;">
              <div style="display: flex;align-items: center;">
                <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">Date</h4>
                <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;font-size: 14px;">   {{ new Date(materialIssueNote.date).toLocaleDateString('en-GB').replace(/\//g, '-') }} </p>
              </div>
            </div>
          </div>
          <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 5px auto;">
            <div style="width: 50%;display: flex;justify-content: start;">
              <div style="display: flex;align-items: center;">
                <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">Department</h4>
                <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;font-size: 14px;">{{ materialIssueNote?.department?.name }}</p>
              </div>
            </div>
            <div style="width: 50%;display: flex;justify-content: start;">
              <div style="display: flex;align-items: center;">
                <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 14px;">Printed ON</h4>
                <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 14px;">{{ print_date_time }}</p>
              </div>
            </div>
          </div>
        </div>
        <div>
          <p  style="font-size: 22px;font-weight: 600;text-align: center;margin: 10px 0;">MATERIAL ISSUE DETAIL</p>
        </div>
        <div class="ring-1 ring-gray-300 sm:rounded-lg overflow-x-auto overflow-y-hidden">
          <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto">
            <thead>
              <tr class="divide-x divide-gray-300">
                <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300">#</th>
                <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300">Product</th>
                <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300">Quantity</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="materialIssueNote.detail && materialIssueNote.detail.length" class="odd:bg-gray-50 even:bg-white divide-x divide-gray-300" v-for="(material, index) in materialIssueNote.detail" :key="material.id">
                <td class="whitespace-nowrap relative text-center border-b border-gray-300">{{index + 1 }}</td>
                <td class="whitespace-nowrap relative text-center border-b border-gray-300">{{ material?.product?.name }}</td>
                <td class="whitespace-nowrap relative text-center border-b border-gray-300">{{ material?.qty }}</td>
              </tr>
              <tr v-else>
                <td colspan="3" class="text-center text-gray-500">No data available</td>
              </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="whitespace-nowrap relative text-center border-b border-gray-300 font-bold py-1 bg-gray-300">Total :</td>
                    <td class="whitespace-nowrap relative text-center border-b border-gray-300 bg-gray-300"></td>
                    <td class="whitespace-nowrap relative text-center border-b border-gray-300 bg-gray-300 font-bold py-1">  {{ sum(materialIssueNote, 'qty', 'material') }}</td>
                </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div style="position:absolute;bottom: 0;width: 100%;">
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
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";

const sum = (materialIssueNote, val) => {
  let total = 0;

  if (materialIssueNote && Array.isArray(materialIssueNote.detail) && materialIssueNote.detail.length > 0) {
    materialIssueNote.detail.forEach(res => {
      if (res[val] !== undefined && !isNaN(res[val])) {
        total += parseFloat(res[val]);
      }
    });
  }

  return isNaN(total) ? '0.00' : total.toFixed(2);
}

</script>
<script>
export default {
  props: {
    materialIssueNote: Array,
    print_date_time: Object,
  },
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
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
        .text_white{
          color: #fff;
        }

          /* CSS to show the element only when printing */
          @media print {
              table th{
          padding: 4px;
          font-size: 12px !important;
          line-height: 12px !important;
        }
        table td{
          padding: 4px;
          font-size: 10px !important;
          line-height: 12px !important;
        }
          .text_white{
            color: #000 !important;
          }
          }
</style>
