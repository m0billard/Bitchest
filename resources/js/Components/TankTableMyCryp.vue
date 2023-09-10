<script setup>
import {
  FlexRender, // Composant pour le rendu flexible des cellules et en-têtes du tableau
  getCoreRowModel, // Fonction pour obtenir le modèle de ligne de base
  useVueTable // Hook pour initialiser et utiliser le tableau Vue
} from '@tanstack/vue-table';
import { ref, h } from 'vue';
import BuySellButton from '@/Components/BuySellButton.vue';
import { usePage } from '@inertiajs/vue3'; // Fonction d'Inertia.js pour accéder aux propriétés de la page actuelle

// Définition des propriétés du composant
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

// Initialisation et récupération des données
const tableData = ref(usePage().props.tableData);
const module = ref(usePage().props.type);

console.log(module.value);

// Initialisation des données
const columnsCrypto = ref([
{
    accessorKey: 'code', // Clé récupérant la valeur
    header: 'Code'
  },
  {
    accessorKey: 'name',
    header: 'Name'
  },
  {
    accessorKey: 'quantity',
    header: 'Quantity'
  },
  {
    accessorKey: 'bought_price',
    header: 'Bought Price',
    cell: ({ row }) => {
      return h('div', {
        class: 'w-full text-center ',
        innerHTML: row.original.bought_price + '€'
      })
    }
  },
  {
    accessorKey: 'total_bought_amount',
    header: 'Total Bought Amount',
    cell: ({ row }) => {
      return h('div', {
        class: 'w-full text-center ',
        innerHTML: row.original.total_bought_amount.toFixed(2) + '€'
      })
    }
  },
  {
    accessorKey: 'created_at',
    header: 'Date'
  },
  {
    accessorKey: 'id',
    header: ' ',
    cell: ({ row }) => h(BuySellButton, {
      id: row.original.crypto_id,
      code: row.original.code,
      cryptName: row.original.name,
      cryptPrice: row.original.current_price,
      boughtPrice: row.original.bought_price,
      quantity: row.original.quantity,
      totalBoughtAmount: row.original.total_bought_amount
    })
  },
]);

// Initialisation du tableau avec les données et les colonnes
const table = useVueTable({
  data: tableData.value,
  columns: columnsCrypto.value,
  getCoreRowModel: getCoreRowModel()
})
</script>

<template>
  <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 bg-gray-50 ">
      <tr v-for="headerGroup  in table.getHeaderGroups()" :key="headerGroup.id">
        <th v-for="header in headerGroup.headers" :key="header.id" scope="col" class="px-6 py-3">
          <FlexRender :render="header.column.columnDef.header" :props="header.getContext()" />
        </th>
      </tr>
    </thead>
    <tbody>
      <!-- Génère dynamiquement les lignes en fonction des données -->
      <tr v-for="row in table.getRowModel().rows" :key="row.index"
        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
        <td v-for="cell in row.getVisibleCells()" :key="cell.id"
          :class="{ 'text-center': (typeof cell.getValue() == 'number') }" class="px-6 py-4">
          <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" :key="cell.index" />
        </td>
      </tr>
    </tbody>
  </table>
</template>