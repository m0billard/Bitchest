<script setup>

import {
  FlexRender,
  getCoreRowModel,
  useVueTable
} from '@tanstack/vue-table';
import { ref, h } from 'vue';
import RemoveBtn from '@/Components/RemoveBtn.vue';
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
const tableColumns = ref(usePage().props.tableColumns);
const module = ref(usePage().props.type);
console.log(module.value);
const columnsAdmin = ref([

  {
    accessorKey: 'name',
    header: 'Name'
  },
  {
    accessorKey: 'email',
    header: 'Email'
  },
  {
    accessorKey: 'wallet_cash',
    header: 'Wallet Cash',
    cell: ({ row }) => '€' + row.original.wallet_cash
  },
  {
    accessorKey: 'id',
    header: ' ',
    cell: ({ row }) => h(RemoveBtn, {
      id: row.original.id,
      module: module.value,
      wallet_cash: row.original.wallet_cash
    })


  }

]);


const table = useVueTable({
  data: tableData.value,
  columns: columnsAdmin.value,
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
        <td v-for="cell in row.getVisibleCells()" :key="cell.id"
          :class="{ 'text-center': (typeof cell.getValue() == 'number') }" class="px-6 py-4">
          <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" :key="row.index" />
        </td>
      </tr>
      <!-- <tr v-for="row in tableData" 
                        :key="row.index" 
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td v-for="cell in row.getVisibleCells()" 
                            :key="cell.id" 
                            class="px-6 py-4">
                            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext( )" />
                            </td>
                        </tr> -->

    </tbody>
  </table>
</template>