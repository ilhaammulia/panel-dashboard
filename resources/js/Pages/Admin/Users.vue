<template>
  <Head title="Users" />

  <div class="grid">
    <div class="col-12">
        <div class="card mb-0">
          <DataTable :value="users" dataKey="id" v-model:selection="selectedUsers" v-model:filters="filters" :globalFilterFields="['name', 'username', 'email', 'role']" paginator :rows="10" :rowsPerPageOptions="[10, 20, 50]" tableStyle="min-width: 50rem">
              <template #header>
                <div class="flex flex-col sm:flex-row justify-content-end gap-2">
                      <div class="order-last">
                        <Button @click="toggleAdd" icon="pi pi-plus" severity="primary" class="border-round mr-2" />
                        <Button @click="handleDelete($event)" icon="pi pi-trash" severity="danger" class="border-round mr-3" />
                        <ConfirmPopup></ConfirmPopup>
                      </div>
                      <span class="p-input-icon-left">
                          <i class="pi pi-search" />
                          <InputText v-model="filters['global'].value" class="border-round" placeholder="Keyword Search" />
                      </span>
                  </div>
              </template>
              <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
              <Column field="name" sortable header="Name"></Column>
              <Column field="username" sortable header="Username"></Column>
              <Column field="email" sortable header="Email"></Column>
              <Column field="role" sortable header="Role">
                <template #body="{ data }">
                  <Chip v-if="data.role_id == 'admin'" label="Admin" class="bg-purple-500 text-white font-bold px-3" />
                  <Chip v-if="data.role_id == 'user'" label="User" class="bg-indigo-500 text-white font-bold px-3" />
                </template>
              </Column>
              <Column field="panels" header="Panels">
                <template #body="{ data }">
                  <div class="max-w-xl space-x-1">
                    <Chip v-for="a in 3" label="Coinbase" class="text-white text-sm font-bold bg-blue-500" image="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTu8hhCX9IDHjOEfUPpYiSTr2rcaskY2FOrqrXsftRDcw&s" />
                  </div>
                </template>
              </Column>
              <Column field="created_at" sortable header="Created At">
                <template #body="{ data }">
                    {{ formatDate(data.created_at) }}
                </template>
              </Column>
              <Column field="action" header="Action">
                <template #body="{ data }">
                  <Button @click="toggleEdit(data)" icon="pi pi-pencil" class="p-button-text p-button-plain p-button-rounded"></Button>
                </template>
              </Column>
          </DataTable>
        </div>
        <div class="grid gap-6">
          <div class="card mt-4 max-w-3xl" v-if="form">
            <div class="flex justify-between">
              <span class="font-semibold text-lg text-gray-600">Add User</span>
              <Button @click="toggleAdd()" icon="pi pi-times" class="p-button-text p-button-plain p-button-rounded"></Button>
            </div>
            <form @submit.prevent="handleSubmit" class="flex flex-col gap-4">
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="name">Name</label>
                    <InputText id="name" v-model="form.name" placeholder="Name" aria-describedby="name-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="username">Username</label>
                    <InputText id="username" v-model="form.username" placeholder="Username" aria-describedby="username-help" />
                    <small v-if="$page.props.errors?.username" class="text-red-500">{{ $page.props.errors?.username }}</small>
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="email">Email</label>
                    <InputText id="email" v-model="form.email" placeholder="Email" aria-describedby="email-help" />
                    <small v-if="$page.props.errors?.email" class="text-red-500">{{ $page.props.errors?.email }}</small>
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="username">Role</label>
                    <Dropdown v-model="form.role_id" :options="roles" optionLabel="name" placeholder="Select role" />
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-token">Telegram Bot Token</label>
                    <InputText id="telegram-token" v-model="form.telegram_bot_token" placeholder="Telegram Bot Token" aria-describedby="telegram-bot-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-id">Telegram Chat ID</label>
                    <InputText id="telegram-id" v-model="form.telegram_chat_id" placeholder="Telegram Chat ID" aria-describedby="telegram-id-help" />
                </div>
              </div>
              <div class="flex flex-column gap-2 w-full border-round">
                  <label for="password">Password</label>
                  <Password id="password" v-model="form.password" placeholder="Password" :feedback="false" aria-describedby="password-help" />
              </div>
              <Button type="submit" label="Save" class="border-round" />
            </form>
          </div>

          <div class="card mt-4 max-w-3xl" v-if="editUser">
            <div class="flex justify-between">
              <span class="font-semibold text-lg text-gray-600">Edit User</span>
              <Button @click="toggleEdit(null)" icon="pi pi-times" class="p-button-text p-button-plain p-button-rounded"></Button>
            </div>
            <form @submit.prevent="handleUpdate" class="flex flex-col gap-4">
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="name">Name</label>
                    <InputText id="name" v-model="editUser.name" placeholder="Name" aria-describedby="name-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="username">Username</label>
                    <InputText id="username" v-model="editUser.username" placeholder="Username" aria-describedby="username-help" />
                    <small v-if="$page.props.errors?.username" class="text-red-500">{{ $page.props.errors?.username }}</small>
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="email">Email</label>
                    <InputText id="email" v-model="editUser.email" placeholder="Email" aria-describedby="email-help" />
                    <small v-if="$page.props.errors?.email" class="text-red-500">{{ $page.props.errors?.username }}</small>
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="username">Role</label>
                    <Dropdown v-model="editUser.role_id" :options="roles" optionLabel="name" placeholder="Select role" />
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-token">Telegram Bot Token</label>
                    <InputText id="telegram-token" v-model="editUser.telegram_bot_token" placeholder="Telegram Bot Token" aria-describedby="telegram-bot-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-id">Telegram Chat ID</label>
                    <InputText id="telegram-id" v-model="editUser.telegram_chat_id" placeholder="Telegram Chat ID" aria-describedby="telegram-id-help" />
                </div>
              </div>
              <div class="flex flex-column gap-2 w-full border-round">
                  <label for="password">Password</label>
                  <Password id="password" v-model="editUser.password" placeholder="Password" :feedback="false" aria-describedby="password-help" />
              </div>
              <Button type="submit" label="Update" class="border-round" />
            </form>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Chip from 'primevue/chip';
import Dropdown from 'primevue/dropdown';
import Password from 'primevue/password';
import ConfirmPopup from 'primevue/confirmpopup';

export default {
  layout: AppLayout,
  components: {
    Head, DataTable, Column, InputText, Button,
    Chip, Dropdown, Password, ConfirmPopup
  },
  props: {
    users: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      filters: {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
      },
      selectedUsers: null,
      editUser: null,
      form: null,
      roles: [
        {
          name: 'Administrator',
          code: 'admin'
        },
        {
          name: 'User',
          code: 'user'
        }
      ],
    }
  },
  methods: {
    formatDate(date) {
      const format = new Date(date);
      return Intl.DateTimeFormat('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      }).format(format);
    },
    toggleAdd() {
      this.form = this.form ? null : useForm({
        name: null,
        username: null,
        email: null,
        role_id: null,
        telegram_bot_token: null,
        telegram_chat_id: null,
        password: null,
        password_confirmation: this.password,
      })
    },
    toggleEdit(data) {
      this.editUser = this.editUser ? data ? {...data, role_id: this.roles.filter((role) => role.code == data.role_id)[0]} : null : {...data, role_id: this.roles.filter((role) => role.code == data.role_id)[0]};
    },
    handleSubmit(e) {
      e.preventDefault();
      if (this.form.name && this.form.username && this.form.email && this.form.role_id && this.form.password) {
        this.form.transform((data) => ({ ...data, role_id: data.role_id.code, password_confirmation: data.password })).post(route('users.store'), {
          onSuccess: () => this.toggleAdd(null)
        });
      }
    },
    handleUpdate(e) {
      e.preventDefault();
      this.editUser = useForm({...this.editUser});
      this.editUser.transform((data) => ({ ...data, role_id: data.role_id.code, password_confirmation: data.password })).put(route('users.update', this.editUser.id), {
          onSuccess: () => this.toggleEdit(null)
        });
    },
    handleDelete(event) {
      this.$confirm.require({
        target: event.currentTarget,
        message: 'Are you sure you want to delete?',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
          if (this.selectedUsers?.length) {
            const form = useForm({
              ids: this.selectedUsers.map((user) => user.id)
            });
            form.delete(route('admin.users.delete.bulk'));
          }
        }
      })
    }
  }
}
</script>