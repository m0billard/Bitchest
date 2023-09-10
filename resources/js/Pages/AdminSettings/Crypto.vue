<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// import { Table, TableHead, TableBody, TableHeadCell, TableRow, TableCell } from 'flowbite-vue'

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import RemoveBtn from '@/Components/RemoveBtn.vue';
import TankTableCrypto from '@/Components/TankTableCrypto.vue';
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


const CryptoTableData = ref(usePage().props.tableData);
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
  <Head title="CRYPTOCURRENCY" />

  <AuthenticatedLayout :checkIfAdmin=checkIfAdmin :activeCrypto=true>
    <template #header>
      <h3 class="font-semibold text-sm text-gray-800 leading-tight">Crypto Management</h3>
    </template>
    <div class="">

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="grid grid-cols-2 pb-4">
          <div>
            <h3 class="font-semibold text-sm text-gray-800 leading-tight">List of Cryptocurrency</h3>
          </div>
          <div class="flex justify-end">


          </div>

          <hr class="col-span-2 h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <TankTableCrypto :tableData="tableData"></TankTableCrypto>
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
            <InputLabel for="email" value="Email" />

            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="registrationForm.email" required
              autocomplete="false" />

            <InputError class="mt-2" :message="registrationForm.errors.email" />
          </div>

          <div class="mt-4">
            <InputLabel for="contact_number" value="Contact Number" />

            <TextInput id="contact_number" type="text" class="mt-1 block w-full" v-model="registrationForm.contact_number"
              required autofocus autocomplete="contact_number" />

            <InputError class="mt-2" :message="registrationForm.errors.contact_number" />
          </div>

          <div class="mt-4">
            <InputLabel for="address" value="Address" />

            <TextInput id="address" type="text" class="mt-1 block w-full" v-model="registrationForm.address" required
              autofocus autocomplete="address" />

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
