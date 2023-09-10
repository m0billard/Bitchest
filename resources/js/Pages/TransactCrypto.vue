<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Table, TableHead, TableBody, TableHeadCell, TableRow, TableCell } from 'flowbite-vue'

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import RemoveBtn from '@/Components/RemoveBtn.vue';
import TankTable from '@/Components/TankTable.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
import { Toast } from 'flowbite-vue';
import { Dropdown, ListGroup, ListGroupItem } from 'flowbite-vue'
import { getCurrentInstance, ref, watch, h, onBeforeMount } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
defineProps({
  checkIfAdmin: {
    type: Boolean
  },
  labels: {
    type: Object
  },
  datas: {
    type: Object
  },
  cryptoInfo: {
    type: Object
  },
});

const years = ref(["2019", "2020", "2021", "2022", "2023"])
const months = ref([
  {
    number: "01",
    name: "January"
  },
  {
    number: "02",
    name: "February"
  },
  {
    number: "03",
    name: "March"
  },
  {
    number: "04",
    name: "April"
  },
  {
    number: "05",
    name: "May"
  },
  {
    number: "06",
    name: "June"
  },
  {
    number: "07",
    name: "July"
  },
  {
    number: "08",
    name: "August"
  },
  {
    number: "09",
    name: "September"
  },
  {
    number: "10",
    name: "October"
  },
  {
    number: "11",
    name: "November"
  },
  {
    number: "12",
    name: "December"
  },
]);

const selectedYear = ref();
const selectedMonth = ref();
const lables = ref(usePage().props.labels);
const datas = ref(usePage().props.datas);
const cryptoInfo = ref(usePage().props.cryptoInfo);
const chart = ref();
const buyCryptoModalShow = ref(false);
const sellCryptoModalShow = ref(false);
const total_amount = ref(0);
const quantity = ref(0);


const form = useForm({
  current_price: cryptoInfo.value.current_price.toFixed(2),
  quantity: 0,
  total_amount: 0,
});
const chartData = ref({
  labels: lables.value,
  datasets: [
    {
      label: cryptoInfo.value.name,
      backgroundColor: '#f87979',
      data: datas.value
    }
  ]
});
const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: true
});

const generate = (cryptoInfo) => {
  axios.post(route('generateTransactions'), {
    "year": selectedYear.value.value,
    "month": selectedMonth.value.value,
    "code": cryptoInfo.code,
    "crypto_id": cryptoInfo.id
  }, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res);
      // var alreadyGenerated =res.data.alreadyGenerated;

      var labels = [];
      var datas = [];
      var graphLabel = cryptoInfo.name + " prices for the month " + selectedMonth.value.text + " " + selectedYear.value.value
      res.data.forEach(movement => {
        console.log(movement);
        labels.push(movement.entry_date);
        datas.push(movement.ended_price);
      });

      fetch(labels, datas, graphLabel);

    })
    .catch((error) => {
      console.log(error)
    })

}

const fetch = (labels, data, graphLabel) => {

  chartData.value = {
    labels: labels,
    datasets: [
      {
        label: graphLabel,
        backgroundColor: '#f87979',
        data: data
      }
    ]
  }
}

const updateTotalAmount = (event) => {
  var total = (cryptoInfo.value.current_price.toFixed(2) * event.target.value);
  form.total_amount = total.toFixed(2);
}

const buyNow = () => {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Buy it!'
  }).then((result) => {
    if (result.isConfirmed) {
      buy();
    }
  })
}

const buy = () => {
  axios.post(route('buy.crypto'), {
    'current_price': cryptoInfo.value.current_price,
    'crypto_id': cryptoInfo.value.id,
    'quantity': form.quantity,
    'total_amount': form.total_amount,
  }, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res.data)
      if (res.data.isBuyingSuccess) {
        Swal.fire({
          title: 'Buying Success',
          showDenyButton: true,
          confirmButtonText: 'Redirect to My Cryto',
          denyButtonText: `Stay on This page`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            location.href = "/mycrypto"
          } else if (result.isDenied) {
            location.reload();
          }
        })
      } else {
        Swal.fire({
          title: 'Buying Failed',
          text: res.data.message,
          icon: 'error',
          confirmButtonText: 'Ok'
        })
      }
    })
    .catch((error) => {
      console.log(error)
    })

}

const sellNow = (id) => {

}




</script>
<template>
  <Head title="Crypto Transactions" />
  <AuthenticatedLayout :checkIfAdmin=checkIfAdmin>
    <template #header>
      <h3 class="font-semibold text-sm text-gray-800 leading-tight">Crypto Currency Transactions</h3>
    </template>
    <div class="grid grid-cols-6 pb-4">
      <div class="col-span-3 flex">
        <h3 class="font-semibold text-sm text-gray-800 leading-tight">
          30 days ago up to now - {{ cryptoInfo.name }}</h3>
      </div>
      <div class="col-span-3 flex justify-end">
        <button @click="buyCryptoModalShow = true" type="button"
          class="px-2 py-0 text-xs ml-4 font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 mr-2">Buy
          Now</button>
        <h3 class="font-semibold text-sm text-gray-800 leading-tight mr-2">
          Current Price:
        </h3>
        <h2 class="font-bold text-sm text-gray-800 leading-tight item-center">€{{ cryptoInfo.current_price.toFixed(2) }}
        </h2>

      </div>

      <hr class="col-span-6 h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
    </div>

    <Bar id="my-chart-id" ref="chart" style="position: relative; height:40vh; width:80vw" :options="chartOptions"
      :data="chartData" />

    <div class="grid grid-cols-6 mt-4 pb-4">
      <div class="flex">
        <h3 class="font-bold text-lg text-gray-800 leading-tight">
          Description : </h3>
      </div>
      <div class="col-span-5">
        {{ cryptoInfo.description }}
      </div>

    </div>
    <Modal :show="buyCryptoModalShow" :maxWidth="'sm'" closeable>
      <div class="px-6 py-4">
        <div>
          <h3 class="font-semibold text-sm text-gray-800 leading-tight">Buy Crypto</h3>
        </div>
        <form @submit.prevent="buyCrypto" class="grid grid-cols-2 mt-4 pb-4 mt-4">
          <div class="col-span-2">
            <h3>{{ cryptoInfo.name }}</h3>
          </div>

          <div class="mt-4 mr-4">
            <InputLabel for="current_price" value="Current Price" />

            <!-- <TextInput
                                    id="current_price"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.current_price"
                                    :value="cryptoInfo.current_price"
                                    readonly
                                /> -->
            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.25 7.756a4.5 4.5 0 100 8.488M7.5 10.5h5.25m-5.25 3h5.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

              </span>
              <input type="number" id="current_price"
                class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                v-model="form.current_price" readonly>
            </div>

            <InputError class="mt-2" :message="form.errors.current_price" />
          </div>

          <div class="mt-4">
            <InputLabel for="quantity" value="Quantity" />

            <TextInput id="quantity" type="number" class="mt-1 block w-full" v-model="form.quantity" required
              @keyup="updateTotalAmount" autocomplete="" />

            <InputError class="mt-2" :message="form.errors.quantity" />
          </div>
          <div></div>
          <div class="mt-4">
            <InputLabel for="total_amount" value="Total Amount" />

            <div class="flex">
              <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.25 7.756a4.5 4.5 0 100 8.488M7.5 10.5h5.25m-5.25 3h5.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

              </span>
              <input type="number" id="total_amount"
                class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                v-model="form.total_amount" readonly>
            </div>
            <InputError class="mt-2" :message="form.errors.total_amount" />
          </div>
          <div class="col-span-2">
            <hr class="mt-2">
          </div>
          <div class="col-span-2 flex items-center justify-end mt-4">
            <!-- <SecondaryButton @click.prevent="buyCryptoModalShow=false">Cancel</SecondaryButton> -->

            <!-- <PrimaryButton class="ml-4" :disabled="form.processing">
                                    Save
                                </PrimaryButton> -->
            <button @click.prevent="buyCryptoModalShow = false"
              class="inline-flex items-center mr-4 px-3 py-1 bg-white border border-gray-300 rounded-md  text-xs text-gray-700 tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
              Cancel
            </button>
            <button @click="buyNow"
              class="inline-flex items-center px-3 py-1 text-xs  text-gray-900 bg-green-300 border border-gray-200 rounded hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-400 focus:text-blue-700 ">
              Buy Now
            </button>
          </div>
        </form>
      </div>
    </Modal>


  </AuthenticatedLayout>
</template>