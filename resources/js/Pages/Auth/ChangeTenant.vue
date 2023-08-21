<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import Checkbox from "@/Components/Checkbox.vue";
import Badge from "@/Components/Badge.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
  tenants: {
    type: Object,
    required: true,
  },

  current_tenant_id: {
    type: [Number, null],
    required: true,
  },

  fav_tenant_id: {
    type: [Number, null],
    required: true,
  },
});

const form = useForm({
  tenant_id: '',
  fav_tenant_id: false,
});

const submit = () => {
  form.post(route('change-tenant.store'), {
      onFinish: () => form.reset(),
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Change Company" />

    <div class="m-4 text-sm text-gray-600 dark:text-gray-400">
      <div v-if="tenants.length > 0">
        <form @submit.prevent="submit">
          <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Please select the company you wish to connect to in the system.
          </div>

          <fieldset>
            <legend class="sr-only">Switch Company</legend>

            <div class="-space-y-px rounded-md bg-white dark:bg-gray-800">
              <label
                v-for="tenant in tenants" :key="tenant.id"
                :class="[
                  tenant.deleted_at !== null ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
                  form.tenant_id === tenant.id ? 'z-10 border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-950/50' : 'border-gray-200 dark:border-gray-600',
                  'first:rounded-t-md last:rounded-b-md relative flex cursor-pointer border p-4 focus:outline-none']"
              >
                <input
                  :disabled="tenant.deleted_at !== null"
                  type="radio"
                  name="change-company"
                  v-model="form.tenant_id"
                  :value="tenant.id"
                  class="mt-0.5 h-4 w-4 shrink-0 cursor-pointer text-indigo-600 dark:text-indigo-900 border-gray-300 dark:border-gray-900 focus:ring-indigo-600 dark:focus:ring-indigo-900 active:ring-2 active:ring-offset-2 active:ring-indigo-600 dark:active:ring-indigo-900"
                  :aria-labelledby="'change-company-' + tenant.id + '-label'"
                  :aria-describedby="'change-company-' + tenant.id + '-description'"
                >

                <span class="ml-3 flex flex-col">
                  <span
                    :id="'change-company-' + tenant.id + '-label'"
                    :class="[form.tenant_id === tenant.id ? 'text-indigo-900 dark:text-indigo-500' : 'text-gray-900 dark:text-gray-200', 'block text-sm font-medium']"
                  >
                    <span>{{ tenant.name }}</span>

                    <Badge v-if="current_tenant_id === tenant.id" type="small" color="sky" class="ml-1 inline-block"> Current </Badge>

                    <Badge v-if="fav_tenant_id === tenant.id" type="small" color="yellow" class="ml-1 inline-block"> Favorite </Badge>
                  </span>

                  <span
                    :id="'change-company-' + tenant.id + '-description'"
                    :class="[form.tenant_id === tenant.id ? 'text-indigo-700 dark:text-indigo-400' : 'text-gray-500', 'block text-sm mt-1']"
                  >
                    <Badge v-if="tenant.deleted_at !== null" type="small" color="red" class="inline-block">
                      {{ 'Deactivated on ' + new Date(tenant.deleted_at).toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                    </Badge>

                    <Badge v-else type="small" color="green" class="inline-block">
                      {{ 'Active' }}
                    </Badge>
                  </span>
                </span>
              </label>
            </div>
          </fieldset>

          <InputError class="mt-2" :message="form.errors.tenant_id" />

          <div class="block mt-4">
            <label class="flex items-center">
              <Checkbox name="Set as favorite" v-model:checked="form.fav_tenant_id" />
              <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Set as favorite</span>
            </label>

            <InputError :message="form.errors.fav_tenant_id" />
          </div>

          <div class="flex justify-end mt-4">
            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Confirm
            </PrimaryButton>
          </div>
        </form>
      </div>

      <div v-else>
        <p class="text-gray-900 dark:text-gray-200">No companies found.</p>

        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        >
          Log Out
        </Link>
      </div>
    </div>
  </GuestLayout>
</template>
