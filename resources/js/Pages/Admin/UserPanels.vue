<template>
  <Head title="Panels" />

  <div class="grid">
    <div class="col-12">
        <div class="card mb-0">
          <DataTable :value="user_panels" dataKey="id" v-model:selection="selectedPanels" v-model:filters="filters" :globalFilterFields="['name', 'domain', 'status', 'panel', 'user', 'expired_at']" paginator :rows="10" :rowsPerPageOptions="[10, 20, 50]" tableStyle="min-width: 50rem">
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
              <Column field="domain" sortable header="Domain"></Column>
              <Column field="user" sortable header="User">
                <template #body="{ data }">
                  {{ data.user.name }}
                </template>
              </Column>
              <Column field="panel" sortable header="Panel">
                <template #body="{ data }">
                  <Chip :label="data.panel.name" class="bg-bluegray-500 text-white font-bold px-3 text-sm" />
                </template>
              </Column>
              <Column field="status" sortable header="Status">
                <template #body="{ data }">
                  <Chip v-if="data.status == 'active'" label="Active" class="bg-green-500 text-white font-bold px-3" />
                  <Chip v-if="data.status == 'expired'" label="Expired" class="bg-yellow-500 text-white font-bold px-3" />
                  <Chip v-if="data.status == 'inactive'" label="Inactive" class="bg-purple-500 text-white font-bold px-3" />
                </template>
              </Column>
              <Column field="expired_at" sortable header="Expired At">
                <template #body="{ data }">
                    {{ formatDate(data.expired_at) }}
                </template>
              </Column>
              <Column field="updated_at" sortable header="Updated At">
                <template #body="{ data }">
                    {{ formatDate(data.updated_at) }}
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
              <span class="font-semibold text-lg text-gray-600">Add User Panel</span>
              <Button @click="toggleAdd()" icon="pi pi-times" class="p-button-text p-button-plain p-button-rounded"></Button>
            </div>
            <form @submit.prevent="handleSubmit" class="flex flex-col gap-4">
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="name">Name</label>
                    <InputText id="name" v-model="form.name" placeholder="Name" aria-describedby="name-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="domain">Domain</label>
                    <InputText id="domain" v-model="form.domain" placeholder="Domain" aria-describedby="domain-help" />
                    <small v-if="$page.props.errors?.domain" class="text-red-500">{{ $page.props.errors?.domain }}</small>
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="user">User</label>
                    <Dropdown v-model="form.user_id" :options="userOptions" optionLabel="name" placeholder="Select user" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="panel">Panel</label>
                    <Dropdown v-model="form.panel_id" :options="panelOptions" optionLabel="name" placeholder="Select panel" />
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-bot-token">Telegram Bot Token</label>
                    <InputText id="telegram-bot-token" v-model="form.telegram_bot_token" placeholder="Telegram Bot Token" aria-describedby="telegram-bot-token-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-chat-id">Telegram Chat ID</label>
                    <InputText id="telegram-chat-id" v-model="form.telegram_chat_id" placeholder="Telegram Chat ID" aria-describedby="telegram-chat-id-help" />
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="status">Status</label>
                    <Dropdown v-model="form.status" :options="statuses" optionLabel="name" placeholder="Select status" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="expired-at">Expired At</label>
                    <Calendar v-model="form.expired_at" showIcon placeholder="Select expired date" />
                </div>
              </div>
              <Button type="submit" label="Save" class="border-round" />
            </form>
          </div>

          <div class="card mt-4 max-w-3xl" v-if="editPanel">
            <div class="flex justify-between">
              <span class="font-semibold text-lg text-gray-600">Edit User Panel</span>
              <Button @click="toggleEdit(null)" icon="pi pi-times" class="p-button-text p-button-plain p-button-rounded"></Button>
            </div>
            <form @submit.prevent="handleUpdate" class="flex flex-col gap-4">
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="name">Name</label>
                    <InputText id="name" v-model="editPanel.name" placeholder="Name" aria-describedby="name-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="domain">Domain</label>
                    <InputText id="domain" v-model="editPanel.domain" placeholder="Domain" aria-describedby="domain-help" />
                    <small v-if="$page.props.errors?.domain" class="text-red-500">{{ $page.props.errors?.domain }}</small>
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="user">User</label>
                    <Dropdown v-model="editPanel.user_id" :options="userOptions" optionLabel="name" placeholder="Select user" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="panel">Panel</label>
                    <Dropdown v-model="editPanel.panel_id" :options="panelOptions" optionLabel="name" placeholder="Select panel" />
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-bot-token">Telegram Bot Token</label>
                    <InputText id="telegram-bot-token" v-model="editPanel.telegram_bot_token" placeholder="Telegram Bot Token" aria-describedby="telegram-bot-token-help" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="telegram-chat-id">Telegram Chat ID</label>
                    <InputText id="telegram-chat-id" v-model="editPanel.telegram_chat_id" placeholder="Telegram Chat ID" aria-describedby="telegram-chat-id-help" />
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="status">Status</label>
                    <Dropdown v-model="editPanel.status" :options="statuses" optionLabel="name" placeholder="Select status" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="expired-at">Expired At</label>
                    <Calendar v-model="editPanel.expired_at" showIcon placeholder="Select expired date" />
                </div>
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
import Calendar from 'primevue/calendar';
import Avatar from 'primevue/avatar';
import Tag from 'primevue/tag';

export default {
  layout: AppLayout,
  components: {
    Head, DataTable, Column, InputText, Button, Tag,
    Chip, Dropdown, Password, ConfirmPopup, Calendar, Avatar
  },
  props: {
    panels: {
      type: Array,
      default: () => []
    },
    user_panels: {
      type: Array,
      default: () => []
    },
    users: {
      type: Array,
      default: () => []
    }
  },
  mounted() {
    this.userOptions = this.users.map((user) => ({ code: user.id, name: user.name}));
    this.panelOptions = this.panels.map((panel) => ({ code: panel.id, name: panel.name}));

  },
  data() {
    return {
      filters: {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
      },
      selectedPanels: null,
      editPanel: null,
      form: null,
      icon: null,
      tempIcon: { name: null, src: null },
      userOptions: [],
      panelOptions: [],
      statuses: [
        {
          name: 'Active',
          code: 'active'
        },
        {
          name: 'Inactive',
          code: 'inactive'
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
        domain: null,
        status: null,
        user_id: null,
        panel_id: null,
        telegram_bot_token: null,
        telegram_chat_id: null,
        expired_at: null,
      })
    },
    toggleEdit(data) {
      this.editPanel = this.editPanel ? data ? {...data, status: this.statuses.filter((status) => status.code == data.status)[0], user_id: this.userOptions.filter((option) => option.code == data.user_id)[0], panel_id: this.panelOptions.filter((option) => option.code == data.panel_id)[0]} : null : {...data, status: this.statuses.filter((status) => status.code == data.status)[0], user_id: this.userOptions.filter((option) => option.code == data.user_id)[0], panel_id: this.panelOptions.filter((option) => option.code == data.panel_id)[0]};
    },
    handleSubmit(e) {
      e.preventDefault();
      if (this.form.name && this.form.domain && this.form.status && this.form.user_id && this.form.panel_id && this.form.expired_at) {
        this.form.transform((data) => ({...data, status: data.status.code, user_id: data.user_id.code, panel_id: data.panel_id.code })).post(route('users.panels.store', {user: this.form.user_id.code}), {
          onSuccess: () => this.toggleAdd(null)
        });
      }
    },
    handleUpdate(e) {
      e.preventDefault();
      this.editPanel = useForm({...this.editPanel});
      this.editPanel.transform((data) => ({ ...data, icon: this.icon, status: data.status.code, })).put(route('panels.update', this.editPanel.id), {
          onSuccess: () => this.toggleEdit(null)
        });
    },
    handleDelete(event) {
      this.$confirm.require({
        target: event.currentTarget,
        message: 'Are you sure you want to delete?',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
          if (this.selectedPanels?.length) {
            const form = useForm({
              ids: this.selectedPanels.map((panel) => panel.id)
            });
            form.delete(route('admin.panels.delete.bulk'));
          }
        }
      })
    },
  }
}
</script>