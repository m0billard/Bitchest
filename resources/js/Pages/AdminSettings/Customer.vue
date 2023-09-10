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
import { getCurrentInstance, ref, watch, h } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';





const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const user = usePage().props.auth.user;
const form = useForm({
  name: user.name,
  email: user.email,
  contact_number: user.contact_number,
  address: user.address
});
const form2 = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});
const registrationForm = useForm({
  name: '',
  email: '',
  contact_number: '',
  address: ''
});


defineProps({
  checkIfAdmin: {
    type: Boolean
  },
  tableData: {
    type: Object
  },
  modalShow: {
    type: Boolean,
  },
  passwordUpdate: {
    type: String
  }
});


const customerTableData = ref(usePage().props.tableData);
const renderComponent = ref(true);
const customer = ref('customer');




const customerModalShow = ref(false);
// const adminTable =ref();


const registerCustomer = () => {
  axios.post(route('register.user'), {
    "name": registrationForm.name,
    "email": registrationForm.email,
    "contact_number": registrationForm.contact_number,
    "address": registrationForm.address,
    "role": "customer",
  }, {
    headers: {
      'Authorization': 'Basic ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res);
      var isUserCreated = res.data.isUserCreated;
      if (isUserCreated) {
        registrationForm.reset();
        customerModalShow.value = false;

        registrationForm.errors.name = "";
        registrationForm.errors.email = "";
        registrationForm.errors.contact_number = "";
        registrationForm.errors.address = "";
        //    getAllAdmins();

        Swal.fire({
          title: 'Success',
          text: 'Customer Successfully Created',
          icon: 'success',
          confirmButtonText: 'Ok'
        }).then(() => {
          location.reload();
        })
      } else {
        var failed = res.data.error
        registrationForm.errors.email = failed.email;
        if (failed.error != undefined) {
          Swal.fire({
            title: 'Failed',
            text: failed.error,
            icon: 'error',
            confirmButtonText: 'Ok'
          })
        }
      }

    })
    .catch((error) => {
      console.log(error);
    })
};




</script>

<template>
  <Head title="CUSTOMER MANAGEMENT" />

  <AuthenticatedLayout :checkIfAdmin=checkIfAdmin :activeCustomer=true>
    <template #header>
      <h3 class="font-semibold text-sm text-gray-800 leading-tight">Customer Management</h3>
    </template>
    <div class="">

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 pb-4">
          <div>
            <h3 class="font-semibold text-sm text-gray-800 leading-tight">List of Customer(s)</h3>
          </div>
          <div class="flex justify-end">
            <div class="inline-flex rounded-md shadow-sm" role="group">
              <button @click="customerModalShow = true" type="button"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-green-300 border border-gray-200 rounded-lg hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                <svg class="w-3 h-3 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                  viewBox="0 0 20 20">
                  <path
                    d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                </svg>
                Add Customer
              </button>
              <!-- <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                <svg class="w-3 h-3 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12.25V1m0 11.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M4 19v-2.25m6-13.5V1m0 2.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M10 19V7.75m6 4.5V1m0 11.25a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM16 19v-2"/>
                                </svg>
                                Settings
                            </button> -->
              <!-- <button type="button" @click="getAllAdmins" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-red-500 border border-gray-200 rounded-r-md hover:bg-red-600 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 mr-2">
                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                </svg>

                                Refresh
                            </button> -->
            </div>

          </div>

          <hr class="col-span-2 h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <TankTable v-if="renderComponent" :tableData="customerTableData.value" :module="customer.value"></TankTable>
      </div>
    </div>

    <template #footer>
      <h3 class="font-semibold text-sm text-gray-800 leading-tight">Admin Settings</h3>
    </template>

    <Modal :show="customerModalShow" closeable>
      <div class="px-6 py-4">
        <div>
          <h3 class="font-semibold text-sm text-gray-800 leading-tight">Add New Customer</h3>
        </div>
        <form @submit.prevent="registerCustomer" class="mt-4">
          <div>
            <h3 class="font-semibold text-xs text-red-800 leading-tight">* The Default password of customer is 12345678
            </h3>
          </div>
          <div>
            <InputLabel for="name" value="Name" />

            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="registrationForm.name" required
              autofocus />

            <InputError class="mt-2" :message="registrationForm.errors.name" />
          </div>

          <div class="mt-4">
            <InputLabel for="email" value="E-mail" />

            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="registrationForm.email" required
              autocomplete="false" />

            <InputError class="mt-2" :message="registrationForm.errors.email" />
          </div>

          <div class="mt-4">
            <InputLabel for="contact_number" value="Contact Number" />

            <TextInput id="contact_number" type="text" class="mt-1 block w-full" v-model="registrationForm.contact_number"
              autocomplete="contact_number" />

            <InputError class="mt-2" :message="registrationForm.errors.contact_number" />
          </div>

          <div class="mt-4">
            <InputLabel for="address" value="Address" />
            <TextInput id="address" type="text" class="mt-1 block w-full" v-model="registrationForm.address"
              autocomplete="address" />

            <InputError class="mt-2" :message="registrationForm.errors.address" />
          </div>

          <div class="flex items-center justify-end mt-4">
            <SecondaryButton @click.prevent="customerModalShow = false">Cancel</SecondaryButton>

            <PrimaryButton class="ml-4" :disabled="registrationForm.processing">
              Save
            </PrimaryButton>
          </div>
        </form>
      </div>
    </Modal>

  </AuthenticatedLayout>
</template>
<style></style>
