<template>
  <Head title="Panels" />

  <div class="grid">
    <div class="col-12">
        <div class="card mb-0">
          <DataTable :value="panels" dataKey="id" v-model:selection="selectedPanels" v-model:filters="filters" :globalFilterFields="['name', 'domain', 'status', 'total_user_panels']" paginator :rows="10" :rowsPerPageOptions="[10, 20, 50]" tableStyle="min-width: 50rem">
              <template #header>
                  <div class="flex justify-content-end">
                      <Button @click="toggleAdd" icon="pi pi-plus" severity="primary" class="border-round mr-2" />
                      <Button @click="handleDelete($event)" icon="pi pi-trash" severity="danger" class="border-round mr-3" />
                      <ConfirmPopup></ConfirmPopup>
                      <span class="p-input-icon-left">
                          <i class="pi pi-search" />
                          <InputText v-model="filters['global'].value" class="border-round" placeholder="Keyword Search" />
                      </span>
                  </div>
              </template>
              <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
              <Column field="name" sortable header="Name"></Column>
              <Column field="domain" sortable header="Domain"></Column>
              <Column field="total_user_panels" sortable header="Total User Panels">
                <template #body="{ data }">
                  {{ data.user_panels.length }}
                </template>
              </Column>
              <Column field="status" sortable header="Status">
                <template #body="{ data }">
                  <Chip v-if="data.status == 'active'" label="Active" class="bg-green-500 text-white font-bold px-3" />
                  <Chip v-if="data.status == 'expired'" label="Expired" class="bg-yellow-500 text-white font-bold px-3" />
                  <Chip v-if="data.status == 'inactive'" label="Inactive" class="bg-purple-500 text-white font-bold px-3" />
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
              <span class="font-semibold text-lg text-gray-600">Add Panel</span>
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
                    <label for="icon">Icon</label>
                    <div v-if="tempIcon.name && tempIcon.src" class="border border-round p-2 flex justify-content-between">
                      <div class="flex gap-2 items-center">
                        <Avatar :image="tempIcon.src" class="flex align-items-center justify-content-center mr-2" size="small" />
                        <p v-if="tempIcon.name" class="font-medium truncate">{{ tempIcon.name }}</p>
                      </div>
                      <Button @click="deleteUpload(tempIcon.pathFile)" icon="pi pi-times" severity="danger" text rounded aria-label="Cancel" />
                    </div>
                    <FileUpload v-if="!tempIcon.name || !tempIcon.src" mode="basic" name="icon" :url="route('admin.panels.upload.icon')" :auto="true" chooseLabel="Browse" accept="image/*" @upload="onUpload($event)" :maxFileSize="1000000" />
                    <small v-if="$page.props.errors?.icon" class="text-red-500">{{ $page.props.errors?.icon }}</small>
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="status">Status</label>
                    <Dropdown v-model="form.status" :options="statuses" optionLabel="name" placeholder="Select status" />
                </div>
              </div>
              <Button type="submit" label="Save" class="border-round" />
            </form>
          </div>

          <div class="card mt-4 max-w-3xl" v-if="editPanel">
            <div class="flex justify-between">
              <span class="font-semibold text-lg text-gray-600">Edit Panel</span>
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
                </div>
              </div>
              <div class="flex flex-col lg:flex-row justify-between gap-4 lg:gap-8">
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="icon">Icon</label>
                    <div v-if="tempIcon.name && tempIcon.src" class="border border-round p-2 flex justify-content-between">
                      <div class="flex gap-2 items-center">
                        <Avatar :image="tempIcon.src" class="flex align-items-center justify-content-center mr-2" size="small" />
                        <p v-if="tempIcon.name" class="font-medium truncate">{{ tempIcon.name }}</p>
                      </div>
                      <Button @click="deleteUpload(tempIcon.pathFile)" icon="pi pi-times" severity="danger" text rounded aria-label="Cancel" />
                    </div>
                    <FileUpload v-if="!tempIcon.name || !tempIcon.src" mode="basic" name="icon" :url="route('admin.panels.upload.icon')" :auto="true" chooseLabel="Browse" accept="image/*" @upload="onUpload($event)" :maxFileSize="1000000" />
                </div>
                <div class="flex flex-column gap-2 w-full border-round">
                    <label for="status">Status</label>
                    <Dropdown v-model="editPanel.status" :options="statuses" optionLabel="name" placeholder="Select status" />
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
import FileUpload from 'primevue/fileupload';
import Avatar from 'primevue/avatar';

import axios from 'axios';

export default {
  layout: AppLayout,
  components: {
    Head, DataTable, Column, InputText, Button, FileUpload,
    Chip, Dropdown, Password, ConfirmPopup, Calendar, Avatar
  },
  props: {
    panels: {
      type: Array,
      default: () => []
    }
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
      })
    },
    toggleEdit(data) {
      if (data?.icon) {
        this.tempIcon = {
          name: 'Current Icon',
          src: data.icon,
          pathFile: data.icon,
        };
        this.icon = data.icon;
      }
      this.editPanel = this.editPanel ? data ? {...data, status: this.statuses.filter((status) => status.code == data.status)[0]} : null : {...data, status: this.statuses.filter((status) => status.code == data.status)[0]};
    },
    handleSubmit(e) {
      e.preventDefault();
      if (this.form.name && this.form.domain && this.form.status) {
        this.form.transform((data) => ({...data, icon: this.icon, status: data.status.code })).post(route('panels.store'), {
          onSuccess: () => this.toggleAdd(null)
        });
      }
      this.icon = null;
      this.tempIcon = { name: null, src: null, pathFile: null, }
    },
    handleUpdate(e) {
      e.preventDefault();
      this.editPanel = useForm({...this.editPanel});
      this.editPanel.transform((data) => ({ ...data, icon: this.icon, status: data.status.code, })).put(route('panels.update', this.editPanel.id), {
          onSuccess: () => this.toggleEdit(null)
        });
      this.icon = null;
      this.tempIcon = { name: null, src: null, pathFile: null, }
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
    onUpload(event) {
      const data = JSON.parse(event.xhr.response);
      this.icon = data.url;
      this.tempIcon = {
        name: event.files[0].name,
        src: event.files[0].objectURL,
        pathFile: data.pathFile
      }
    },
    async deleteUpload(path) {
      await axios.delete(route('admin.panels.delete.icon'), {
        data: {
          pathFile: path
        }
      });
      this.tempIcon =  { name: null, src: null };
    }
  }
}
</script>