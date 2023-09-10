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
import { getCurrentInstance, ref, watch, h, onBeforeMount } from 'vue';
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
  type: {
    type: String
  },
  passwordUpdate: {
    type: String
  }
});
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
    accessorKey: 'contact_number',
    header: 'Contact Number'
  },
  {
    accessorKey: 'address',
    header: 'Address'
  },
  {
    accessorKey: 'address',
    header: ' ',
    cell: ({ row }) => h('<button @click="removeAdmin" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" ></button>')
  }

]);


const adminTableData = ref(usePage().props.tableData);
const renderComponent = ref(true);
const admin = ref(null);
onBeforeMount(() => {
  admin.value = "admin";
})






const changePassModalShow = ref(false);
const adminModalShow = ref(false);
// const adminTable =ref();




const getAllAdmins = () => {
  axios.get(route('getAllAdmin'), {
    headers: {
      'Authorization': 'Bearer ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res.data);
      adminTableData.value = res.data;
    })
    .catch((error) => {
      console.log(error)
    })
};

const removeAdmin = () => {

  console.log("sample remove");
  // axios.put(route('admin.remove'), {
  //     "current_password":form2.current_password,
  //     "password":form2.password,
  //     "password_confirmation":form2.password_confirmation
  // },{
  // headers: {
  //     'Authorization': 'Basic '+localStorage.token
  // }
  // })
  // .then((res) => {
  // console.log(res);
  // var isUpdateSuccess =res.data.isUpdateSuccess;
  // console.log(isUpdateSuccess);
  // if(isUpdateSuccess){
  //     form2.reset();
  //    changePassModalShow.value =false;
  //    form2.errors.current_password ="";
  //    form2.errors.password ="";
  //    form2.errors.password_confirmation ="";
  //    Swal.fire({
  //     title: 'Success',
  //     text: 'Password Successfully Updated',
  //     icon: 'success',
  //     confirmButtonText: 'Ok'
  //     })
  // }else{
  //     var failed =res.data.error
  //    form2.errors.current_password =failed.current_password;
  //    form2.errors.password =failed.password;
  //    form2.errors.password_confirmation =failed.password_confirmation;
  // }

  // })
  // .catch((error) => {
  //         if (form2.errors.password) {
  //             form2.reset('password', 'password_confirmation');
  //             passwordInput.value.focus();
  //         }
  //         if (form2.errors.current_password) {
  //             form2.reset('current_password');
  //             currentPasswordInput.value.focus();
  //         }
  // })
};



const updatePassword = () => {
  axios.put(route('password.update'), {
    "current_password": form2.current_password,
    "password": form2.password,
    "password_confirmation": form2.password_confirmation
  }, {
    headers: {
      'Authorization': 'Basic ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res);
      var isUpdateSuccess = res.data.isUpdateSuccess;
      console.log(isUpdateSuccess);
      if (isUpdateSuccess) {
        form2.reset();
        changePassModalShow.value = false;
        form2.errors.current_password = "";
        form2.errors.password = "";
        form2.errors.password_confirmation = "";
        Swal.fire({
          title: 'Success',
          text: 'Password Successfully Updated',
          icon: 'success',
          confirmButtonText: 'Ok'
        })
      } else {
        var failed = res.data.error
        form2.errors.current_password = failed.current_password;
        form2.errors.password = failed.password;
        form2.errors.password_confirmation = failed.password_confirmation;
      }

    })
    .catch((error) => {
      if (form2.errors.password) {
        form2.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form2.errors.current_password) {
        form2.reset('current_password');
        currentPasswordInput.value.focus();
      }
    })
};

const registerAdmin = () => {
  renderComponent.value = false;
  axios.post(route('register.user'), {
    "name": registrationForm.name,
    "email": registrationForm.email,
    "contact_number": registrationForm.contact_number,
    "address": registrationForm.address,
    "role": "admin",
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
        adminModalShow.value = false;

        registrationForm.errors.name = "";
        registrationForm.errors.email = "";
        registrationForm.errors.contact_number = "";
        registrationForm.errors.address = "";
        //    getAllAdmins();

        Swal.fire({
          title: 'Success',
          text: 'Adminn Successfully Created',
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
      if (form2.errors.password) {
        form2.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form2.errors.current_password) {
        form2.reset('current_password');
        currentPasswordInput.value.focus();
      }
    })
};




const columns = [
  { data: 'email' },
  { data: 'name' },
  { data: 'contact_number' }
];



</script>

<template>
  <Head title="ADMIN SETTINGS" />

  <AuthenticatedLayout :checkIfAdmin=checkIfAdmin :activeAdmin=true>
    <template #header>
      <h3 class="font-semibold text-sm text-gray-800 leading-tight">Admin Settings</h3>
    </template>
    <div class="">

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 pb-4">
          <div>
            <h3 class="font-semibold text-sm text-gray-800 leading-tight">Admin Profile</h3>
          </div>
          <div class="col-span-2 flex justify-end">

            <div class="inline-flex rounded-md shadow-sm" role="group">
              <button @click="changePassModalShow = true" type="button"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-green-300 border border-gray-200 rounded hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 mr-2">
                  <path fill-rule="evenodd"
                    d="M15.75 1.5a6.75 6.75 0 00-6.651 7.906c.067.39-.032.717-.221.906l-6.5 6.499a3 3 0 00-.878 2.121v2.818c0 .414.336.75.75.75H6a.75.75 0 00.75-.75v-1.5h1.5A.75.75 0 009 19.5V18h1.5a.75.75 0 00.53-.22l2.658-2.658c.19-.189.517-.288.906-.22A6.75 6.75 0 1015.75 1.5zm0 3a.75.75 0 000 1.5A2.25 2.25 0 0118 8.25a.75.75 0 001.5 0 3.75 3.75 0 00-3.75-3.75z"
                    clip-rule="evenodd" />
                </svg>

                Change Password
              </button>

            </div>
          </div>

          <hr class="col-span-2 h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <form class="grid grid-cols-2 gap-4 mb-6" @submit.prevent="form.patch(route('profile.update'))">
          <div>
            <InputLabel for="name" value="Name" />

            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
              autocomplete="name" />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="email" value="E-mail" />

            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" readonly
              autocomplete="username" />

            <InputError class="mt-2" :message="form.errors.email" />
          </div>

          <div class="mt-4">
            <InputLabel for="contact_number" value="Contact Number" />

            <TextInput id="contact_number" type="text" class="mt-1 block w-full" v-model="form.contact_number" />

            <InputError class="mt-2" :message="form.errors.contact_number" />
          </div>

          <div class="mt-4">
            <InputLabel for="address" value="Address" />

            <TextInput id="address" type="text" class="mt-1 block w-full" v-model="form.address" />

            <InputError class="mt-2" :message="form.errors.address" />
          </div>

        </form>

        <div class="grid grid-cols-2 pb-4">
          <div>
            <h3 class="font-semibold text-sm text-gray-800 leading-tight">List of Admin(s)</h3>
          </div>
          <div class="flex justify-end">

            <button @click="adminModalShow = true" type="button"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-green-300 border border-gray-200 rounded-lg hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
              <svg class="w-3 h-3 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                  d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
              </svg>
              Add New Admin
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

          <hr class="col-span-2 h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">
        </div>
        <TankTable :tableData="adminTableData.value" :tableColumns="columnsAdmin.value" :type="type"></TankTable>
      </div>
    </div>

    <template #footer>
      <h3 class="font-semibold text-sm text-gray-800 leading-tight">Admin Settings</h3>
    </template>

    <Modal :show="changePassModalShow" closeable>
      <div class="px-6 py-4">
        <div>
          <h3 class="font-semibold text-sm text-gray-800 leading-tight">Change Password</h3>
          <Toast v-if="form2.recentlySuccessful" :type="success" closable divide class=" h-6">Success</Toast>
        </div>
        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
          <div>
            <InputLabel for="current_password" value="Current Password" />

            <TextInput id="current_password" ref="currentPasswordInput" v-model="form2.current_password" type="password"
              class="mt-1 block w-full" autocomplete="current-password" />

            <InputError :message="form2.errors.current_password" class="mt-2" />
          </div>

          <div>
            <InputLabel for="password" value="New Password" />

            <TextInput id="password" ref="passwordInput" v-model="form2.password" type="password"
              class="mt-1 block w-full" autocomplete="new-password" />

            <InputError :message="form2.errors.password" class="mt-2" />
          </div>

          <div>
            <InputLabel for="password_confirmation" value="Confirm Password" />

            <TextInput id="password_confirmation" v-model="form2.password_confirmation" type="password"
              class="mt-1 block w-full" autocomplete="new-password" />

            <InputError :message="form2.errors.password_confirmation" class="mt-2" />
          </div>

          <div class="flex justify-end gap-4">
            <SecondaryButton @click.prevent="changePassModalShow = false">Cancel</SecondaryButton>
            <PrimaryButton :disabled="form2.processing">Save</PrimaryButton>

            <!-- <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form2.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                        </Transition> -->
          </div>
        </form>
      </div>

    </Modal>

    <Modal :show="adminModalShow" closeable>
      <div class="px-6 py-4">
        <div>
          <h3 class="font-semibold text-sm text-gray-800 leading-tight">Add New Admin</h3>
        </div>
        <form @submit.prevent="registerAdmin" class="mt-4">
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
            <SecondaryButton @click.prevent="adminModalShow = false">Cancel</SecondaryButton>

            <PrimaryButton class="ml-4" :disabled="registrationForm.processing">
              Save
            </PrimaryButton>
          </div>
        </form>
      </div>
    </Modal>

  </AuthenticatedLayout>


  <!-- Modal toggle -->
  <!-- <button data-modal-target="changepass-modal" data-modal-toggle="changepass-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
  Toggle modal
</button> -->

  <!-- Main modal -->
</template>
<style></style>
