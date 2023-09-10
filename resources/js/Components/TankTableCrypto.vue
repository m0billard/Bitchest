<script setup>

import {
  FlexRender,
  getCoreRowModel,
  useVueTable
} from '@tanstack/vue-table';
import { ref, h } from 'vue';
import ViewButton from '@/Components/ViewButton.vue';
import { usePage } from '@inertiajs/vue3';


defineProps({
  tableData: {
    type: Object
  },
  tableColumns: {
    type: Object,
  },
  type: {
    type: String,
  }
});


const tableData = ref(usePage().props.tableData);
const module = ref(usePage().props.type);
console.log(module.value);
const columnsCrypto = ref([
  {
    accessorKey: 'code',
    header: 'Code'
  },
  {
    accessorKey: 'name',
    header: 'Name'
  },
  {
    accessorKey: 'description',
    header: 'Description'
  },
  {
    accessorKey: 'current_price',
    header: 'Last Price',
    cell: ({ row }) => '€' + row.original.current_price
  },
  {
    accessorKey: 'id',
    header: ' ',
    cell: ({ row }) => h(ViewButton, {
      id: row.original.id,
      code: row.original.code,
    })

  }

]);


const table = useVueTable({
  data: tableData.value,
  columns: columnsCrypto.value,
  getCoreRowModel: getCoreRowModel()
})




</script>

<template>
  <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
      <tr v-for="headerGroup  in table.getHeaderGroups()" :key="headerGroup.id">
        <th v-for="header in headerGroup.headers" :key="header.id" scope="col" class="px-6 py-3">
          <FlexRender :render="header.column.columnDef.header" :props="header.getContext()" />
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="row in table.getRowModel().rows" :key="row.index"
        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
        <td v-for="cell in row.getVisibleCells()" :key="cell.id" class="px-6 py-4">
          <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" :key="row.index" />
        </td>
      </tr>

    </tbody>
  </table>
</template>