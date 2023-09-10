<script setup>
import { ref, h } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
defineProps({
  id: {
    type: Number
  },
  module: {
    type: String
  },
  wallet_cash: {
    type: Number
  }
});
// const wallet_cash = ref();
const addCashModalShow = ref(false);
const form = useForm({
  wallet_cash: '',

});



const remove = (id, module) => {
  console.log(module);
  switch (module) {
    case "admin":
      console.log("admin Remove");

      Swal.fire({
        title: 'Warning',
        text: 'Are you sure you want to delete this admin?',
        icon: 'warning',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: `No`,
      }).then((res) => {
        if (res.isConfirmed) {
          emit(route('admin.remove', { 'id': id }));
        } else if (res.isDenied) {
          Swal.fire('Changes are not saved', '', 'info')
        }
      })
      break;
    case "customer":
      console.log("customer remove")
      Swal.fire({
        title: 'Warning',
        text: 'Are you sure you want to delete this customer?',
        icon: 'warning',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: `No`,
      }).then((res) => {
        if (res.isConfirmed) {
          emit(route('customer.remove', { 'id': id }));
        } else if (res.isDenied) {
          Swal.fire('Changes are not saved', '', 'info')
        }
      })
      break;
  }

}

const emit = (route) => {
  console.log(route);
  axios.post(route, null, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res);
      var isAdminRemoved = res.data.isAdminRemoved;
      if (isAdminRemoved) {

        Swal.fire({
          title: 'Success',
          text: 'User Successfully Removed',
          icon: 'success',
          confirmButtonText: 'Ok'
        }).then(() => {
          location.reload();
        })
      } else {
        Swal.fire({
          title: 'Failed',
          text: failed.error,
          icon: 'error',
          confirmButtonText: 'Ok'
        })
      }

    })
    .catch((error) => {
      console.log(error)
    })

}

const openCashModal = (wallet_cash) => {
  addCashModalShow.value = true;
  form.wallet_cash = wallet_cash;
}

const updateCash = (id) => {
  axios.post(route('cash.update'), {
    id: id,
    wallet_cash: form.wallet_cash
  }, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.token
    }
  })
    .then((res) => {
      console.log(res);
      var isCashUpdated = res.data.isCashUpdated;
      if (isCashUpdated) {

        Swal.fire({
          title: 'Success',
          text: 'Cash Wallet Successfully Updaetd',
          icon: 'success',
          confirmButtonText: 'Ok'
        }).then(() => {
          location.reload();
        })
      } else {
        Swal.fire({
          title: 'Failed',
          text: 'Cash is not updated',
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
  <button @click="openCashModal(wallet_cash)"
    class="inline-flex items-center mr-2 px-3 py-1 text-xs  text-gray-900 bg-green-300 border border-gray-200 rounded hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-400 focus:text-blue-700 ">
    Add Cash
  </button>
  <button @click="remove(id, module)"
    class="inline-flex items-center px-2 py-1 bg-red-600 border border-transparent rounded-md  text-xs text-white tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
    Remove
  </button>
  <Modal :show="addCashModalShow" closeable>
    <div class="px-6 py-4">
      <div>
        <h3 class="font-semibold text-sm text-gray-800 leading-tight">Update Cashier</h3>
      </div>
      <form @submit.prevent="updateCash(id)" class="mt-6 space-y-6">
        <div>
          <InputLabel for="wallet_cash" value="Wallet Cash" />

          <TextInput id="wallet_cash" ref="walletCash" v-model="form.wallet_cash" type="number"
            class="mt-1 block w-full" />

          <InputError :message="form.errors.wallet_cash" class="mt-2" />
        </div>
        <div class="flex justify-end gap-4">
          <SecondaryButton @click.prevent="addCashModalShow = false">Cancel</SecondaryButton>
          <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

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
</template>