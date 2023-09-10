<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import RemoveBtn from '@/Components/RemoveBtn.vue';
import TankTable from '@/Components/TankTable.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
import { ref, h } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
defineProps({
  id: {
    type: Number
  },
  code: {
    type: String
  },
  cryptName: {
    type: String
  },
  cryptPrice: {
    type: Number
  },
  boughtPrice: {
    type: Number
  },
  quantity: {
    type: Number
  },
  totalBoughtAmount: {
    type: Number
  }
});

const buyCryptoModalShow = ref(false);
const sellCryptoModalShow = ref(false);
const cryptCurrentPrice = ref(usePage().props.cryptPrice);
const form = useForm({
  bought_price: cryptCurrentPrice.value,
  hold_qty: 0,
  current_price: 0,
  quantity: 0,
  total_bought_amount: 0,
  total_amount: 0,
});

const form2 = useForm({
  bought_price: cryptCurrentPrice.value,
  hold_qty: 0,
  current_price: 0,
  quantity: 0,
  total_bought_amount: 0,
  total_amount: 0,
});

const modalShow = (price) => {
  form.current_price = price.toFixed(2);
  buyCryptoModalShow.value = true;
}

const modalShow2 = (bought_price, hold_qty, price, total_bought_amount) => {
  form2.bought_price = bought_price.toFixed(2);
  form2.hold_qty = hold_qty;
  form2.quantity = hold_qty;
  form2.total_amount = (price.toFixed(2) * hold_qty).toFixed(2);
  form2.current_price = price.toFixed(2);
  form2.total_bought_amount = total_bought_amount.toFixed(2);
  sellCryptoModalShow.value = true;
}


const updateTotalAmount = (event) => {
  var total = (form.current_price * event.target.value);
  form.total_amount = total.toFixed(2);
}

const updateSellingTotalAmount = (event) => {
  var total = (form2.current_price * event.target.value);
  form2.total_amount = total.toFixed(2);
  if (event.target.value > form2.hold_qty) {
    form2.errors.quantity = "Not enough holding quantity"
  } else {
    form2.errors.quantity = ""
  }
}

const buyNow = (id) => {
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
      buy(id);
    }
  })
}

const buy = (id) => {
  axios.post(route('buy.crypto'), {
    'current_price': form.current_price,
    'crypto_id': id,
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
          showDenyButton: false,
          confirmButtonText: 'Ok',
        }).then(() => location.reload())
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
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Sell it!'
  }).then((result) => {
    if (result.isConfirmed) {
      sell(id);
    }
  })
}

const sell = (id) => {
  axios.post(route('sell.crypto'), {
    'current_price': form2.current_price,
    'crypto_id': id,
    'quantity': form2.quantity,
    'total_selling_amount': form2.total_amount,
  }, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res.data)
      if (res.data.isSellingSuccess) {
        Swal.fire({
          title: 'Selling Success',
          showDenyButton: false,
          confirmButtonText: 'Ok',
        }).then(() => location.reload())
      } else {
        Swal.fire({
          title: 'Selling Failed',
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
</script>

<template>
  <div class="flex">
    <button @click="modalShow(cryptPrice)"
      class="inline-flex items-center px-3 py-1 text-xs mr-1 text-gray-900 bg-green-300 border border-gray-200 rounded hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-400 focus:text-blue-700 ">
      Buy
    </button>
    <button @click="modalShow2(boughtPrice, quantity, cryptPrice, totalBoughtAmount)"
      class="inline-flex items-center px-3 py-1 text-xs text-gray-900 bg-red-400 border border-gray-200 rounded hover:bg-red-600 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-400 focus:text-blue-700 ">
      Sell
    </button>
  </div>
  <Modal :show="buyCryptoModalShow" :maxWidth="'sm'" closeable>
    <div class="px-6 py-4">
      <div>
        <h3 class="font-semibold text-sm text-gray-800 leading-tight">Buy Crypto</h3>
      </div>
      <form @submit.prevent="buyCrypto" class="grid grid-cols-2 mt-4 pb-4 mt-4">
        <div class="col-span-2">
          <h3>{{ cryptName }}</h3>
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
          <button @click="buyNow(id)"
            class="inline-flex items-center px-3 py-1 text-xs  text-gray-900 bg-green-300 border border-gray-200 rounded hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-400 focus:text-blue-700 ">
            Buy Now
          </button>
        </div>
      </form>
    </div>
  </Modal>

  <Modal :show="sellCryptoModalShow" :maxWidth="'sm'" closeable>
    <div class="px-6 py-4">
      <div>
        <h3 class="font-semibold text-sm text-gray-800 leading-tight">Sell Crypto</h3>
      </div>
      <form @submit.prevent="sellNow(id)" class="grid grid-cols-2 mt-4 pb-4 mt-4">
        <div class="col-span-2">
          <h3>{{ cryptName }}</h3>
        </div>

        <div class="mt-4 mr-4">
          <InputLabel for="total_amount" value="Total Bought Amount" />

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
              v-model="form2.total_bought_amount" readonly>
          </div>
          <InputError class="mt-2" :message="form2.errors.total_bought_amount" />
        </div>
        <div class="mt-4">
          <InputLabel for="quantity" value="Hold Quantity" />

          <TextInput id="quantity" type="number" class="mt-1 block w-full bg-gray-50" v-model="form2.hold_qty" readonly />

          <InputError class="mt-2" :message="form2.errors.hold_qty" />
        </div>


        <div class="mt-4 mr-4">
          <InputLabel for="current_price" value="Selling Price" />
          <div class="flex">
            <span
              class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.25 7.756a4.5 4.5 0 100 8.488M7.5 10.5h5.25m-5.25 3h5.25M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>

            </span>
            <input type="number" id="selling_price"
              class="rounded-none rounded-r-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              v-model="form2.current_price" readonly>
          </div>

          <InputError class="mt-2" :message="form2.errors.current_price" />
        </div>

        <div class="mt-4">
          <InputLabel for="quantity" value="Quantity" />

          <TextInput id="quantity" type="number" class="mt-1 block w-full" v-model="form2.quantity" required
            @keyup="updateSellingTotalAmount" autocomplete="" />

          <InputError class="mt-2" :message="form2.errors.quantity" />
        </div>
        <div></div>

        <div class="mt-4">
          <InputLabel for="total_amount" value="Total Selling Amount" />

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
              v-model="form2.total_amount" readonly>
          </div>
          <InputError class="mt-2" :message="form2.errors.total_amount" />
        </div>
        <div class="col-span-2">
          <hr class="mt-2">
        </div>
        <div class="col-span-2 flex items-center justify-end mt-4">
          <!-- <SecondaryButton @click.prevent="buyCryptoModalShow=false">Cancel</SecondaryButton> -->

          <!-- <PrimaryButton class="ml-4" :disabled="form.processing">
                                    Save
                                </PrimaryButton> -->
          <button @click.prevent="sellCryptoModalShow = false"
            class="inline-flex items-center mr-4 px-3 py-1 bg-white border border-gray-300 rounded-md  text-xs text-gray-700 tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            Cancel
          </button>
          <button type="submit"
            class="inline-flex items-center px-3 py-1 text-xs text-gray-900 bg-red-400 border border-gray-200 rounded hover:bg-red-600 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-400 focus:text-blue-700 ">
            Sell Now
          </button>
        </div>
      </form>
    </div>
  </Modal>
</template>